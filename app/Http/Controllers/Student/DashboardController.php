<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Carbon\Carbon; // 1. Import Carbon

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

        // 2. Mengambil 5 pengajuan izin terakhir
        $recentPermits = $student->absencePermits()->latest()->take(5)->get();

        // --- Logika Rekap Absensi ---
        $startOfMonth = Carbon::now()->startOfMonth();
        $today = Carbon::now()->endOfDay();

        // Ambil data absensi & izin BULAN INI (tanpa paginasi) untuk dihitung
        $monthlyAttendances = $student->attendances()
                                      ->whereBetween('recorded_at', [$startOfMonth, $today])
                                      ->get();

        // PERBAIKAN ERROR: Tambahkan $today ke 'use'
        $monthlyApprovedPermitsCount = $student->absencePermits()
                            ->where('status', 'disetujui')
                            ->where(function($query) use ($startOfMonth, $today) { // <--- $today DITAMBAHKAN DI SINI
                                // Cek izin yang mencakup bulan ini
                                $query->whereDate('start_date', '<=', $today)
                                      ->whereDate('end_date', '>=', $startOfMonth);
                            })
                            ->count();

        // 3. Hitung STATS

        // HADIR (Hari unik siswa melakukan absensi 'in')
        $hadirCount = $monthlyAttendances
                        ->where('status', 'in')
                        ->unique(fn($item) => $item->recorded_at->format('Y-m-d'))
                        ->count();

        // IZIN (Total izin yang disetujui)
        $izinCount = $monthlyApprovedPermitsCount;

        // ALPA (Total hari sekolah (Senin-Sabtu) dikurangi hadir & izin)

        // Hitung total hari sekolah (Senin-Sabtu) dari awal bulan sampai hari ini
        $schoolDays = 0;
        $dateIterator = $startOfMonth->clone();
        while ($dateIterator <= $today) {
            // Hanya hitung jika BUKAN hari Minggu
            if (!$dateIterator->isSunday()) {
                $schoolDays++;
            }
            $dateIterator->addDay();
        }

        // Alpa = Total Hari Sekolah - Hadir - Izin
        $alpaCount = $schoolDays - $hadirCount - $izinCount;
        if ($alpaCount < 0) {
            $alpaCount = 0; // Pastikan tidak negatif
        }

        $attendanceSummary = [
            'hadir' => $hadirCount,
            'izin'  => $izinCount,
            'alpa'  => $alpaCount, // Data baru untuk Alpa
        ];
        // --- AKHIR PERBAIKAN ---

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
