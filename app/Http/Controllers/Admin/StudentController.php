<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    /**
     * Helper untuk menyediakan data kelas dan jurusan.
     * UPDATE: Daftar jurusan disesuaikan dengan kebutuhan terbaru (ada nomor kelas).
     */
    private function getClassData(): array
    {
        return [
            'grades' => ['X', 'XI', 'XII'],
            'majors' => [
                'AKUNTANSI 1',
                'AKUNTANSI 2',
                'MANAJEMEN PERKANTORAN 1',
                'MANAJEMEN PERKANTORAN 2',
                'MANAJEMEN PERKANTORAN 3',
                'MANAJEMEN PERKANTORAN 4',
                'DESAIN KOMUNIKASI VISUAL 1',
                'DESAIN KOMUNIKASI VISUAL 2',
                'PRODUKSI & SIARAN PROGRAM TELEVISI 1',
                'PRODUKSI & SIARAN PROGRAM TELEVISI 2',
                'ANIMASI',
            ]
        ];
    }

    /**
     * Menampilkan daftar semua siswa dengan fungsionalitas pencarian yang disempurnakan.
     */
    public function index(Request $request)
    {
        $searchName = $request->input('search_name');
        $filterGrade = $request->input('filter_grade');
        $filterMajor = $request->input('filter_major');

        $query = User::student();

        // Filter berdasarkan nama atau NIS
        if ($searchName) {
            $query->where(function ($q) use ($searchName) {
                $q->where('name', 'like', "%{$searchName}%")
                  ->orWhere('nis', 'like', "%{$searchName}%");
            });
        }

        // Filter berdasarkan tingkat kelas (contoh: 'X %')
        if ($filterGrade) {
            $query->where('class', 'like', "{$filterGrade} %");
        }

        // Filter berdasarkan jurusan (contoh: '% ANIMASI')
        if ($filterMajor) {
            // Karena jurusan ada di bagian belakang string kelas (misal "X AKUNTANSI 1")
            $query->where('class', 'like', "% {$filterMajor}");
        }

        $students = $query->orderBy('name', 'asc')
                          ->paginate(10)
                          ->withQueryString();

        $classData = $this->getClassData();

        return view('admin.students.index', array_merge([
            'students' => $students,
            'searchName' => $searchName,
            'filterGrade' => $filterGrade,
            'filterMajor' => $filterMajor,
        ], $classData));
    }

    /**
     * Menampilkan form untuk membuat siswa baru.
     * MENGGUNAKAN SATU VIEW FORM (admin.students.form)
     */
    public function create()
    {
        $guardians = User::where('role', 'wali')->orderBy('name')->get();

        return view('admin.students.form', array_merge($this->getClassData(), [
            'guardians' => $guardians,
            'student' => new User(), // Kirim objek kosong agar form tidak error saat akses properti
            'pageTitle' => 'Tambah Data Siswa',
            'isEdit' => false,
            'currentGrade' => '',
            'currentMajor' => '',
        ]));
    }

    /**
     * Menyimpan siswa baru yang baru dibuat ke dalam database.
     */
    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'nis' => ['required', 'string', 'max:20', 'unique:'.User::class],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'grade' => ['required', 'string'],
            'major' => ['required', 'string'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:10240'], // Max 10MB
            'card_uid' => ['nullable', 'string', 'max:255', 'unique:'.User::class],
            'guardian_id' => ['required', 'exists:users,id'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        try {
            // 2. Bersihkan Data Request dari field yang tidak perlu disimpan langsung
            $data = $request->except(['password', 'photo', 'grade', 'major', 'password_confirmation', '_token']);

            // 3. Format Data Tambahan
            // Menggabungkan Grade dan Major menjadi satu string 'class'
            $data['class'] = $request->grade . ' ' . $request->major;
            $data['role'] = 'siswa';
            $data['password'] = Hash::make($request->password);

            // Pastikan card_uid benar-benar NULL jika kosong (mencegah error duplikat string kosong)
            $data['card_uid'] = $request->filled('card_uid') ? $request->card_uid : null;

            // 4. Upload Foto Jika Ada
            if ($request->hasFile('photo')) {
                $data['photo'] = $request->file('photo')->store('photos', 'public');
            }

            // 5. Simpan ke Database
            User::create($data);

            return redirect()->route('admin.students.index')->with('success', 'Siswa berhasil ditambahkan.');

        } catch (\Exception $e) {
            // Jika terjadi error, kembali ke form dengan pesan error yang jelas
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambah siswa: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan form untuk mengedit data siswa.
     * MENGGUNAKAN SATU VIEW FORM (admin.students.form)
     */
    public function edit(User $student)
    {
        // Memecah string 'class' kembali menjadi Grade dan Major untuk form edit
        $parts = explode(' ', $student->class, 2);
        $currentGrade = $parts[0] ?? '';
        $currentMajor = $parts[1] ?? '';

        $guardians = User::where('role', 'wali')->orderBy('name')->get();

        return view('admin.students.form', array_merge([
            'student' => $student,
            'currentGrade' => $currentGrade,
            'currentMajor' => $currentMajor,
            'guardians' => $guardians,
            'pageTitle' => 'Edit Data Siswa',
            'isEdit' => true,
        ], $this->getClassData()));
    }

    /**
     * Memperbarui data siswa di database.
     */
    public function update(Request $request, User $student)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'nis' => ['required', 'string', 'max:20', Rule::unique(User::class)->ignore($student->id)],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique(User::class)->ignore($student->id)],
            'grade' => ['required', 'string'],
            'major' => ['required', 'string'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:10240'],
            'card_uid' => ['nullable', 'string', 'max:255', 'unique:'.User::class.',card_uid,'.$student->id],
            'guardian_id' => ['required', 'exists:users,id'],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        try {
            $data = $request->except(['password', 'photo', 'grade', 'major', 'password_confirmation', '_token']);

            // Menggabungkan kembali Grade dan Major saat update
            $data['class'] = $request->grade . ' ' . $request->major;

            // Pastikan card_uid benar-benar NULL jika kosong
            $data['card_uid'] = $request->filled('card_uid') ? $request->card_uid : null;

            if ($request->hasFile('photo')) {
                if ($student->photo) {
                    Storage::disk('public')->delete($student->photo);
                }
                $data['photo'] = $request->file('photo')->store('photos', 'public');
            }

            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            $student->update($data);

            return redirect()->route('admin.students.index')->with('success', 'Data siswa berhasil diperbarui.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal update siswa: ' . $e->getMessage());
        }
    }

    /**
     * Menghapus data siswa dari database.
     */
    public function destroy(User $student)
    {
        if ($student->photo) {
            Storage::disk('public')->delete($student->photo);
        }
        $student->delete();
        return redirect()->route('admin.students.index')->with('success', 'Data siswa berhasil dihapus.');
    }

    /**
     * Menghapus SEMUA data siswa dari database.
     */
    public function destroyAll()
    {
        try {
            // Ambil semua siswa
            $students = User::student()->get();

            // Hapus foto-foto siswa dari storage
            foreach ($students as $student) {
                if ($student->photo) {
                    Storage::disk('public')->delete($student->photo);
                }
            }

            // Hapus semua data siswa
            $deletedCount = User::student()->delete();

            return redirect()
                ->route('admin.students.index')
                ->with('success', "Berhasil menghapus {$deletedCount} data siswa.");

        } catch (\Exception $e) {
            return redirect()
                ->route('admin.students.index')
                ->with('error', 'Terjadi kesalahan saat menghapus data siswa.');
        }
    }
}
