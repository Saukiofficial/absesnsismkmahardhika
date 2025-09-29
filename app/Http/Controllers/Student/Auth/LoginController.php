<?php

namespace App\Http\Controllers\Student\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class LoginController extends Controller
{
    /**
     * Menampilkan halaman formulir login untuk siswa.
     */
    public function showLoginForm(): View
    {
        return view('student.auth.login', [
            'pageTitle' => 'Login Siswa'
        ]);
    }

    /**
     * Menangani permintaan login dari siswa.
     */
    public function login(Request $request): RedirectResponse
    {
        // 1. Validasi input dari form
        $credentials = $request->validate([
            'identity' => 'required|string', // Bisa email atau NIS
            'password' => 'required|string',
        ]);

        // 2. Menentukan apakah input adalah email atau NIS
        $identityField = filter_var($credentials['identity'], FILTER_VALIDATE_EMAIL) ? 'email' : 'nis';

        // 3. Mencoba untuk melakukan otentikasi
        if (Auth::attempt([$identityField => $credentials['identity'], 'password' => $credentials['password']], $request->filled('remember'))) {

            // 4. PENTING: Periksa apakah pengguna yang login adalah 'siswa'
            if (Auth::user()->role !== 'siswa') {
                Auth::logout(); // Jika bukan siswa, langsung logout lagi
                return back()->withErrors([
                    'identity' => 'Akun ini tidak memiliki akses sebagai siswa.',
                ])->onlyInput('identity');
            }

            // 5. Jika berhasil dan adalah siswa, regenerasi session dan redirect
            $request->session()->regenerate();
            return redirect()->intended(route('student.dashboard'));
        }

        // 6. Jika otentikasi gagal, kembalikan ke form login dengan pesan error
        return back()->withErrors([
            'identity' => 'Identitas atau password yang diberikan tidak cocok.',
        ])->onlyInput('identity');
    }

    /**
     * Menangani proses logout siswa.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('student.login');
    }
}
