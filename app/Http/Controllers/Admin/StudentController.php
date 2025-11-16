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
     */
    private function getClassData(): array
    {
        return [
            'grades' => ['X', 'XI', 'XII'],
            'majors' => [
                'AKUNTANSI',
                'ANIMASI',
                'DESAIN KOMUNIKASI VISUAL',
                'MANAJEMEN PERKANTORAN',
                'PRODUKSI SIARAN PROGRAM TELEVISI',
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
            $query->where('class', 'like', $filterGrade . ' %');
        }

        // Filter berdasarkan jurusan (contoh: '% AKUNTANSI')
        if ($filterMajor) {
            $query->where('class', 'like', '% ' . $filterMajor);
        }

        $students = $query->latest()->paginate(10)->appends($request->all());

        // Mengirim data kelas dan jurusan untuk dropdown filter
        return view('admin.students.index', array_merge(
            compact('students'),
            $this->getClassData()
        ));
    }

    /**
     * Menampilkan form untuk membuat siswa baru.
     */
    public function create()
    {
        $pageTitle = 'Tambah Siswa';
        $guardians = User::query()->guardian()->orderBy('name')->get();
        return view('admin.students.form', array_merge(
            compact('pageTitle', 'guardians'),
            $this->getClassData()
        ));
    }

    /**
     * Menyimpan siswa baru ke database.
     */
    public function store(Request $request)
    {
        $classData = $this->getClassData();
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'nis' => ['required', 'string', 'max:255', 'unique:'.User::class],
            'grade' => ['required', Rule::in($classData['grades'])],
            'major' => ['required', Rule::in($classData['majors'])],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'card_uid' => ['nullable', 'string', 'max:255', 'unique:'.User::class],
            'fingerprint_id' => ['nullable', 'string', 'max:255', 'unique:'.User::class],
            'guardian_id' => ['required', 'exists:users,id'],
        ]);

        $data = $request->except('password', 'photo', 'grade', 'major');
        $data['password'] = Hash::make($request->password);
        $data['role'] = 'siswa';
        // Menggabungkan Grade dan Major menjadi satu string "class"
        $data['class'] = $request->grade . ' ' . $request->major;

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('photos', 'public');
        }

        User::create($data);

        return redirect()->route('admin.students.index')->with('success', 'Data siswa berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit data siswa.
     */
    public function edit(User $student)
    {
        $pageTitle = 'Edit Siswa: ' . $student->name;
        $guardians = User::query()->guardian()->orderBy('name')->get();

        // --- PERBAIKAN LOGIKA UNTUK FORM EDIT ---
        // Memecah string 'class' dan membersihkan spasi (trim)
        // Agar "X  AKUNTANSI" (dobel spasi) tetap terbaca sebagai "X" dan "AKUNTANSI"
        if ($student->class) {
            // Limit 2 digunakan agar jurusan yang mengandung spasi tidak terpotong salah
            $parts = explode(' ', $student->class, 2);

            if (count($parts) === 2) {
                $student->grade = trim($parts[0]); // Tambahkan trim()
                $student->major = trim($parts[1]); // Tambahkan trim()
            } elseif (count($parts) === 1) {
                // Jaga-jaga jika formatnya salah (hanya kelas atau jurusan)
                $student->grade = trim($parts[0]);
            }
        }

        return view('admin.students.form', array_merge(
            compact('pageTitle', 'student', 'guardians'),
            $this->getClassData()
        ));
    }

    /**
     * Memperbarui data siswa di database.
     */
    public function update(Request $request, User $student)
    {
        $classData = $this->getClassData();
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class.',email,'.$student->id],
            'nis' => ['required', 'string', 'max:255', 'unique:'.User::class.',nis,'.$student->id],
            'grade' => ['required', Rule::in($classData['grades'])],
            'major' => ['required', Rule::in($classData['majors'])],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:10240'],
            'card_uid' => ['nullable', 'string', 'max:255', 'unique:'.User::class.',card_uid,'.$student->id],
            'fingerprint_id' => ['nullable', 'string', 'max:255', 'unique:'.User::class.',fingerprint_id,'.$student->id],
            'guardian_id' => ['required', 'exists:users,id'],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        $data = $request->except('password', 'photo', 'grade', 'major');
        // Menggabungkan kembali Grade dan Major saat update
        $data['class'] = $request->grade . ' ' . $request->major;

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
}
