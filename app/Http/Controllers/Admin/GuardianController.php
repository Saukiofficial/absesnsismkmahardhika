<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class GuardianController extends Controller
{
    /**
     * Menampilkan daftar semua wali murid.
     */
    public function index()
    {
        $guardians = User::query()->guardian()->withCount('students')->latest()->paginate(10);
        return view('admin.guardians.index', compact('guardians'));
    }

    /**
     * Menampilkan form untuk membuat wali murid baru.
     */
    public function create()
    {
        $pageTitle = 'Tambah Wali Murid';
        return view('admin.guardians.form', compact('pageTitle'));
    }

    /**
     * Menyimpan wali murid baru ke database.
     */
    public function store(Request $request)
    {
        // PERBAIKAN: Hapus validasi dan pembuatan email dummy.
        // Login akan menggunakan nomor telepon sebagai 'email'.
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'guardian_phone' => ['required', 'numeric', 'unique:'.User::class.',guardian_phone'],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->guardian_phone, // Gunakan nomor telepon sebagai email unik
            'password' => Hash::make($request->password),
            'role' => 'wali',
            'guardian_phone' => $request->guardian_phone,
        ]);

        return redirect()->route('admin.guardians.index')->with('success', 'Data wali murid berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit data wali murid.
     */
    public function edit(User $guardian)
    {
        $pageTitle = 'Edit Wali Murid: ' . $guardian->name;
        return view('admin.guardians.form', compact('pageTitle', 'guardian'));
    }

    /**
     * Memperbarui data wali murid di database.
     */
    public function update(Request $request, User $guardian)
    {
        // PERBAIKAN: Hapus validasi dan pembuatan email dummy.
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'guardian_phone' => ['required', 'numeric', 'unique:'.User::class.',guardian_phone,'.$guardian->id],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        $guardian->update([
            'name' => $request->name,
            'email' => $request->guardian_phone, // Gunakan nomor telepon sebagai email unik
            'guardian_phone' => $request->guardian_phone,
        ]);

        if ($request->filled('password')) {
            $guardian->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('admin.guardians.index')->with('success', 'Data wali murid berhasil diperbarui.');
    }

    /**
     * Menghapus data wali murid dari database.
     */
    public function destroy(User $guardian)
    {
        if ($guardian->students()->count() > 0) {
            return redirect()->route('admin.guardians.index')->with('error', 'Tidak dapat menghapus wali murid yang masih memiliki siswa terdaftar.');
        }
        $guardian->delete();
        return redirect()->route('admin.guardians.index')->with('success', 'Data wali murid berhasil dihapus.');
    }

    /**
     * Menghapus SEMUA data wali murid dari database.
     */
    public function destroyAll()
    {
        try {
            // Cek apakah ada wali murid yang masih memiliki siswa
            $guardiansWithStudents = User::query()->guardian()->has('students')->count();

            if ($guardiansWithStudents > 0) {
                return redirect()
                    ->route('admin.guardians.index')
                    ->with('error', "Tidak dapat menghapus semua data. Terdapat {$guardiansWithStudents} wali murid yang masih memiliki siswa terdaftar. Hapus data siswa terlebih dahulu.");
            }

            // Hapus semua data wali murid yang tidak memiliki siswa
            $deletedCount = User::query()->guardian()->delete();

            return redirect()
                ->route('admin.guardians.index')
                ->with('success', "Berhasil menghapus {$deletedCount} data wali murid.");

        } catch (\Exception $e) {
            return redirect()
                ->route('admin.guardians.index')
                ->with('error', 'Terjadi kesalahan saat menghapus data wali murid.');
        }
    }

    /**
     * Menangani permintaan pencarian wali murid (AJAX) dari form siswa.
     */
    public function search(Request $request)
    {
        $search = $request->input('q');

        if (empty($search)) {
            return response()->json([]);
        }

        $guardians = User::query()
            ->guardian()
            ->where('name', 'LIKE', "%{$search}%")
            ->orWhere('guardian_phone', 'LIKE', "%{$search}%")
            ->limit(10)
            ->get(['id', 'name', 'guardian_phone']);

        return response()->json($guardians);
    }
}
