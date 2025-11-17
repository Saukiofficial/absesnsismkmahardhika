<?php

namespace App\Http\Controllers\Guardian;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Carbon\Carbon; // 1. Import Carbon

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard utama untuk wali murid.
     * Halaman ini akan menampilkan daftar siswa DAN status kehadiran hari ini.
     */
    public function index(): View
    {
        $guardian = Auth::user();
        $today = Carbon::today();
        $lateBoundary = '06:30:00'; // Sesuaikan dengan batas terlambat

        // 1. Ambil siswa + relasi absensi & izin HARI INI (Eager Loading)
        $students = $guardian->students()->with([
            'attendances' => function ($query) use ($today) {
                // Ambil absensi hanya untuk hari ini
                $query->whereDate('recorded_at', $today);
            },
            'absencePermits' => function ($query) use ($today) {
                // Ambil izin yang disetujui DAN aktif hari ini
                $query->where('status', 'disetujui')
                      ->whereDate('start_date', '<=', $today)
                      ->whereDate('end_date', '>=', $today);
            }
        ])->orderBy('name')->get();

        // 2. Tentukan status setiap siswa
        foreach ($students as $student) {
            $statusHariIni = '';
            $detailHariIni = null;

            if ($today->isSunday()) {
                $statusHariIni = 'Libur';
            } else {
                // Cek data relasi yang sudah di-load
                $absensiHariIni = $student->attendances->where('status', 'in')->first();
                $izinHariIni = $student->absencePermits->first();

                if ($absensiHariIni) {
                    // Jika ada absensi 'in'
                    if ($absensiHariIni->recorded_at->format('H:i:s') > $lateBoundary) {
                        $statusHariIni = 'Terlambat';
                    } else {
                        $statusHariIni = 'Hadir';
                    }
                    $detailHariIni = $absensiHariIni->recorded_at->format('H:i');
                } elseif ($izinHariIni) {
                    // Jika tidak hadir, tapi ada izin
                    $statusHariIni = 'Izin';
                    $detailHariIni = ucfirst($izinHariIni->permit_type); // Cth: "Sakit"
                } else {
                    // Jika tidak hadir dan tidak izin
                    // Jika jam sekolah sudah lewat (misal > 17:00), anggap Alpa
                    if (now()->hour > 17) {
                        $statusHariIni = 'Alpa';
                    } else {
                        $statusHariIni = 'Belum Absen';
                    }
                }
            }

            // Tambahkan status ini sebagai properti baru ke object $student
            $student->statusHariIni = $statusHariIni;
            $student->detailHariIni = $detailHariIni;
        }

        $pageTitle = 'Dashboard Wali Murid';

        // 3. Mengirim data ke file view 'guardian.dashboard'
        return view('guardian.dashboard', compact(
            'pageTitle',
            'students' // Collection $students kini berisi ->statusHariIni
        ));
    }
}
