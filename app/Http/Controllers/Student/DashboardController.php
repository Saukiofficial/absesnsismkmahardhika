<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Carbon\Carbon;

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

        $monthlyApprovedPermitsCount = $student->absencePermits()
                            ->where('status', 'disetujui')
                            ->where(function($query) use ($startOfMonth, $today) {
                                // Cek izin yang mencakup bulan ini
                                $query->whereDate('start_date', '<=', $today)
                                      ->whereDate('end_date', '>=', $startOfMonth);
                            })
                            ->count();

        // HADIR (Menghitung berapa hari unik siswa melakukan absensi 'in')
        $hadirCount = $monthlyAttendances
                        ->where('status', 'in')
                        ->unique(fn($item) => $item->recorded_at->format('Y-m-d'))
                        ->count();

        // IZIN (Total izin yang disetujui)
        $izinCount = $monthlyApprovedPermitsCount;

        // ALPA (Total hari sekolah dikurangi hadir & izin)

        // --- PERBAIKAN LOGIKA ALPA ---
        // Masalah: Kalau siswa baru masuk tgl 12, jangan hitung tgl 1-11 sebagai Alpa.
        // Solusi: Start perhitungan hari sekolah dimulai dari 'created_at' siswa jika ia baru masuk bulan ini.

        $calculationStartDate = $startOfMonth->clone();

        // Jika siswa dibuat/diinput SETELAH awal bulan ini, maka hitungan mulai dari tanggal dia dibuat
        if ($student->created_at && $student->created_at > $startOfMonth) {
            $calculationStartDate = $student->created_at->startOfDay();
        }

        // Hitung total hari sekolah (Senin-Sabtu) dari Tanggal Mulai Hitung sampai Hari Ini
        $schoolDays = 0;
        $dateIterator = $calculationStartDate->clone();

        // Pastikan iterator tidak melebihi hari ini (mencegah loop aneh jika jam server beda)
        while ($dateIterator <= $today) {
            // Hanya hitung jika BUKAN hari Minggu
            if (!$dateIterator->isSunday()) {
                $schoolDays++;
            }
            $dateIterator->addDay();
        }

        // Alpa = Total Hari Sekolah - Hadir - Izin
        $alpaCount = $schoolDays - $hadirCount - $izinCount;

        // Pastikan tidak negatif (jaga-jaga jika ada data aneh)
        if ($alpaCount < 0) {
            $alpaCount = 0;
        }

        $attendanceSummary = [
            'hadir' => $hadirCount,
            'izin'  => $izinCount,
            'alpa'  => $alpaCount,
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
