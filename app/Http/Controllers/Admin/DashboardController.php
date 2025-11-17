<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\User;
use App\Models\AbsencePermit; // Pastikan Model ini ada dan benar
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        // --- 1. CARD: Total Siswa ---
        // Ini dihitung kapan saja, libur atau tidak
        $totalStudents = User::where('role', 'siswa')->count();

        // --- PERBAIKAN: BLOK LOGIKA HARI MINGGU ---
        // Carbon::SUNDAY = 0
        if ($today->dayOfWeek === Carbon::SUNDAY) {
            // Jika hari ini Minggu, set semua data operasional ke 0 atau kosong
            $presentCount = 0;
            $izinHariIni = 0;
            $absentCount = 0;
            $lateCount = 0;
            $latestAttendances = collect(); // Kirim collection kosong
            $pendingPermissions = collect(); // Kirim collection kosong

        } else {
            // --- Logika Hari Normal (Senin - Sabtu) ---

            // 2. CARD: Hadir Hari Ini
            $presentStudentIds = Attendance::whereDate('recorded_at', $today)
                ->where('status', 'in')
                ->distinct('user_id')
                ->pluck('user_id');
            $presentCount = $presentStudentIds->count();

            // 3. CARD: Izin Hari Ini
            $izinHariIni = AbsencePermit::whereIn('status', ['disetujui', 'diajukan', 'pending'])
                            ->whereDate('start_date', '<=', $today)
                            ->whereDate('end_date', '>=', $today)
                            ->count();

            // 4. CARD: Siswa Alpa / Belum Hadir
            $absentCount = $totalStudents - ($presentCount + $izinHariIni);
            if ($absentCount < 0) {
                $absentCount = 0;
            }

            // 5. CARD: Terlambat (Batas 06:30)
            $lateBoundary = '06:30:00';
            $lateCount = Attendance::whereDate('recorded_at', $today)
                ->where('status', 'in')
                ->whereTime('recorded_at', '>', $lateBoundary)
                ->distinct('user_id')
                ->count();

            // 6. TABEL KIRI: Aktivitas Absensi Terakhir
            $latestAttendances = Attendance::with('user')
                ->latest('recorded_at')
                ->take(5)
                ->get();

            // 7. TABEL KANAN: Permintaan Izin Baru
            $pendingPermissions = AbsencePermit::with('student')
                ->whereIn('status', ['diajukan', 'pending'])
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();
        }
        // --- AKHIR PERBAIKAN ---

        return view('admin.dashboard', compact(
            'totalStudents',
            'presentCount',
            'absentCount',
            'lateCount',
            'latestAttendances',
            'izinHariIni',
            'pendingPermissions'
        ));
    }
}
