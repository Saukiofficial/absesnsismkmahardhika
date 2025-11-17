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
        $totalStudents = User::where('role', 'siswa')->count();
        $allStudentIds = User::where('role', 'siswa')->pluck('id');

        // --- PERBAIKAN: BLOK LOGIKA HARI MINGGU ---
        if ($today->dayOfWeek === Carbon::SUNDAY) {
            // Jika hari ini Minggu, set semua data operasional ke 0 atau kosong
            $presentCount = 0;
            $izinHariIni = 0;
            $absentCount = 0;
            $lateCount = 0;
            $latestAttendances = collect();
            $pendingPermissions = collect();

            // Variabel baru
            $approvedPermitsToday = collect(); // Kosongkan daftar izin
            $alpaStudentsToday = collect(); // Kosongkan daftar alpa

        } else {
            // --- Logika Hari Normal (Senin - Sabtu) ---

            // 2. CARD: Hadir Hari Ini
            $presentStudentIds = Attendance::whereDate('recorded_at', $today)
                ->where('status', 'in')
                ->distinct('user_id')
                ->pluck('user_id');
            $presentCount = $presentStudentIds->count();

            // 3. CARD: Izin Hari Ini (Termasuk Pending)
            $onLeaveStudentIds = AbsencePermit::whereIn('status', ['disetujui', 'diajukan', 'pending'])
                            ->whereDate('start_date', '<=', $today)
                            ->whereDate('end_date', '>=', $today)
                            ->distinct('user_id')
                            ->pluck('user_id');
            $izinHariIni = $onLeaveStudentIds->count();

            // 4. CARD: Siswa Alpa / Belum Hadir
            // Siswa yang hadir ATAU izin
            $excusedStudentIds = $presentStudentIds->merge($onLeaveStudentIds)->unique();
            $absentCount = $totalStudents - $excusedStudentIds->count();

            // 5. CARD: Terlambat (Batas 06:30)
            $lateBoundary = '06:30:00';
            $lateCount = Attendance::whereIn('user_id', $presentStudentIds)
                ->where('status', 'in')
                ->whereTime('recorded_at', '>', $lateBoundary)
                ->distinct('user_id')
                ->count();

            // 6. TABEL: Aktivitas Absensi Terakhir
            $latestAttendances = Attendance::with('user')
                ->whereDate('recorded_at', $today) // Hanya tampilkan aktivitas hari ini
                ->latest('recorded_at')
                ->take(5)
                ->get();

            // 7. TABEL: Permintaan Izin Baru (Pending)
            $pendingPermissions = AbsencePermit::with('student')
                ->whereIn('status', ['diajukan', 'pending'])
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();

            // --- 8. LOGIKA BARU: TABEL Aktivitas Izin (Disetujui) ---
            $approvedPermitsToday = AbsencePermit::with('student')
                ->where('status', 'disetujui')
                ->whereDate('start_date', '<=', $today)
                ->whereDate('end_date', '>=', $today)
                ->get();

            // --- 9. LOGIKA BARU: TABEL Aktivitas Alpa ---
            $alpaStudentIds = $allStudentIds->diff($excusedStudentIds);
            $alpaStudentsToday = User::whereIn('id', $alpaStudentIds)->get(['id', 'name', 'class']);
        }
        // --- AKHIR PERBAIKAN ---

        return view('admin.dashboard', compact(
            'totalStudents',
            'presentCount',
            'absentCount',
            'lateCount',
            'latestAttendances',
            'izinHariIni',
            'pendingPermissions',
            'approvedPermitsToday', // Kirim data baru
            'alpaStudentsToday'     // Kirim data baru
        ));
    }
}
