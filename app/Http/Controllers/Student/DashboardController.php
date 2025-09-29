<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman utama (dashboard) untuk siswa yang sedang login.
     *
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        // 1. Mengambil data siswa yang saat ini sedang login
        $student = Auth::user();

        // 2. Mengambil 5 pengajuan izin terakhir dari siswa tersebut
        // Diurutkan dari yang paling baru
        $recentPermits = $student->absencePermits()->latest()->take(5)->get();

        // 3. Menghitung rekap absensi untuk bulan ini
        // (Ini bisa dikembangkan lebih lanjut)
        $attendanceSummary = [
            'hadir' => $student->attendances()
                        ->whereMonth('recorded_at', now()->month)
                        ->whereYear('recorded_at', now()->year)
                        ->count(),
            'izin' => $student->absencePermits()
                       ->where('status', 'disetujui')
                       ->where(function($query) {
                           $query->whereMonth('start_date', now()->month)
                                 ->orWhereMonth('end_date', now()->month);
                       })
                       ->whereYear('start_date', now()->year)
                       ->count(),
        ];

        $pageTitle = 'Dashboard';

        // 4. Mengirim semua data ke file view 'student.dashboard'
        return view('student.dashboard', compact(
            'pageTitle',
            'student',
            'recentPermits',
            'attendanceSummary'
        ));
    }
}
