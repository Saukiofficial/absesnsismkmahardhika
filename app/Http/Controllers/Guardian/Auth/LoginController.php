<?php

namespace App\Http\Controllers\Guardian\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class LoginController extends Controller
{
    /**
     * Menampilkan halaman formulir login untuk wali murid.
     */
    public function showLoginForm(): View
    {
        return view('guardian.auth.login', [
            'pageTitle' => 'Login Wali Murid'
        ]);
    }

    /**
     * Menangani permintaan login dari wali murid.
     */
    public function login(Request $request): RedirectResponse
    {
        // 1. Validasi input dari form
        // PERBAIKAN: Validasi sekarang menggunakan 'guardian_phone' bukan 'email'
        $request->validate([
            'guardian_phone' => 'required|string|numeric',
            'password' => 'required|string',
        ]);

        // 2. Mencoba untuk melakukan otentikasi
        // Sistem akan mencocokkan 'guardian_phone' dari form dengan kolom 'email' di database
        if (Auth::attempt(['email' => $request->guardian_phone, 'password' => $request->password], $request->filled('remember'))) {

            // 3. PENTING: Periksa apakah pengguna yang login adalah 'wali'
            if (Auth::user()->role !== 'wali') {
                Auth::logout();
                return back()->withErrors([
                    'guardian_phone' => 'Akun ini tidak memiliki akses sebagai wali murid.',
                ])->onlyInput('guardian_phone');
            }

            // 4. Jika berhasil dan adalah wali, regenerasi session dan redirect
            $request->session()->regenerate();
            return redirect()->intended(route('guardian.dashboard'));
        }

        // 5. Jika otentikasi gagal, kembalikan ke form login dengan pesan error
        return back()->withErrors([
            'guardian_phone' => 'Nomor WhatsApp atau password yang diberikan tidak cocok.',
        ])->onlyInput('guardian_phone');
    }

    /**
     * Menangani proses logout wali murid.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('guardian.login');
    }
}

