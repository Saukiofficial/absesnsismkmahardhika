<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\User;
// PERBAIKAN 1: Gunakan Model yang benar (AbsencePermit), bukan Permission
use App\Models\AbsencePermit;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Set Tanggal Hari Ini
        $today = Carbon::today();

        // 2. Hitung Total Siswa
        $totalStudents = User::where('role', 'siswa')->count();

        // 3. Hitung Siswa Hadir (Status 'in')
        $presentStudentIds = Attendance::whereDate('recorded_at', $today)
            ->where('status', 'in')
            ->distinct('user_id')
            ->pluck('user_id');
        $presentCount = $presentStudentIds->count();

        // 4. Hitung Siswa Izin Hari Ini (PERBAIKAN LOGIKA & STATUS INDONESIA)
        // Status 'disetujui' adalah status resmi dari AbsencePermitController
        // Kita hitung yang 'disetujui' ATAU 'diajukan' sebagai orang yang tidak ada di kelas
        $izinHariIni = AbsencePermit::whereIn('status', ['disetujui', 'diajukan', 'pending'])
                        ->whereDate('start_date', '<=', $today)
                        ->whereDate('end_date', '>=', $today)
                        ->count();

        // 5. Hitung Siswa Belum Hadir / Absen
        $absentCount = $totalStudents - ($presentCount + $izinHariIni);
        if ($absentCount < 0) {
            $absentCount = 0;
        }

        // 6. Hitung Keterlambatan
        $lateBoundary = '07:15:00';
        $lateCount = Attendance::whereDate('recorded_at', $today)
            ->where('status', 'in')
            ->whereTime('recorded_at', '>', $lateBoundary)
            ->distinct('user_id')
            ->count();

        // 7. Ambil Data Absensi Terakhir
        $latestAttendances = Attendance::with('user')
            ->latest('recorded_at')
            ->take(10)
            ->get();

        // 8. Ambil Data Izin Pending (PERBAIKAN RELASI & STATUS)
        // Menggunakan relasi 'student' sesuai AbsencePermitController
        // Mencari status 'diajukan' (bahasa baku) atau 'pending' (jaga-jaga)
        $pendingPermissions = AbsencePermit::with('student')
            ->whereIn('status', ['diajukan', 'pending', 'Pending'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // 9. Kirim Semua Variabel ke View
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
