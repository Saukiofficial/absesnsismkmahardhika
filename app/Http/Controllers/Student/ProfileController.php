<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ProfileController extends Controller
{
    /**
     * Menampilkan halaman profil siswa.
     */
    public function show(): View
    {
        return view('student.profile.show', [
            'pageTitle' => 'Profil Saya',
            'student' => Auth::user()->load('guardian') // Memuat relasi wali murid
        ]);
    }

    /**
     * Memperbarui password siswa.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $student = Auth::user();

        // 1. Validasi input
        $request->validate([
            'current_password' => ['required', 'string', 'current_password'],
            'password' => ['required', 'string', Password::defaults(), 'confirmed'],
        ]);

        // 2. Perbarui password
        $student->update([
            'password' => Hash::make($request->password),
        ]);

        // 3. Redirect dengan pesan sukses
        return back()->with('success', 'Password berhasil diperbarui!');
    }
}
