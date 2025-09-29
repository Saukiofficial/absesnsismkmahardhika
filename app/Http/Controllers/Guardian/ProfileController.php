<?php

namespace App\Http\Controllers\Guardian;

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
     * Menampilkan halaman profil wali murid.
     */
    public function show(): View
    {
        return view('guardian.profile.show', [
            'pageTitle' => 'Profil Saya',
            'guardian' => Auth::user()
        ]);
    }

    /**
     * Memperbarui password wali murid.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $guardian = Auth::user();

        // 1. Validasi input
        $request->validate([
            'current_password' => ['required', 'string', 'current_password'],
            'password' => ['required', 'string', Password::defaults(), 'confirmed'],
        ]);

        // 2. Perbarui password
        $guardian->update([
            'password' => Hash::make($request->password),
        ]);

        // 3. Redirect dengan pesan sukses
        return back()->with('success', 'Password berhasil diperbarui!');
    }
}
