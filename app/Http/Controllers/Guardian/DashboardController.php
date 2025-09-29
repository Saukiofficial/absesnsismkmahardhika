<?php

namespace App\Http\Controllers\Guardian;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard utama untuk wali murid.
     * Halaman ini akan menampilkan daftar siswa yang berada di bawah perwaliannya.
     */
    public function index(): View
    {
        // 1. Mengambil data wali murid yang sedang login
        $guardian = Auth::user();

        // 2. Menggunakan relasi 'students' yang sudah kita buat di Model User
        // untuk mengambil semua siswa yang terkait dengan wali murid ini.
        $students = $guardian->students()->orderBy('name')->get();

        $pageTitle = 'Dashboard Wali Murid';

        // 3. Mengirim data ke file view 'guardian.dashboard'
        return view('guardian.dashboard', compact(
            'pageTitle',
            'students'
        ));
    }
}
