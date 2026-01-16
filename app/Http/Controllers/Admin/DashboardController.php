<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\User;
use App\Models\AbsencePermit;
use App\Models\Holiday; // 1. JANGAN LUPA IMPORT MODEL HOLIDAY
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        // --- 1. DATA GLOBAL (Selalu Ada) ---
        // Total Siswa Aktif
        $totalStudents = User::where('role', 'siswa')->count();
        // Ambil semua ID siswa untuk perhitungan Alpa nanti
        $allStudentIds = User::where('role', 'siswa')->pluck('id');

        // Inisialisasi variabel agar tidak error "Undefined Variable"
        $presentCount = 0;
        $izinHariIni = 0;
        $absentCount = 0;
        $lateCount = 0;
        $latestAttendances = collect();
        $pendingPermissions = collect();
        $approvedPermitsToday = collect();
        $alpaStudentsToday = collect();

        // --- 2. CEK HARI LIBUR ---
        // Cek apakah tanggal hari ini ada di tabel 'holidays'
        $isHoliday = Holiday::whereDate('holiday_date', $today)->exists();

        // --- 3. LOGIKA HARI ---
        // Jika Hari Minggu ATAU Hari ini terdaftar sebagai Libur di database
        if ($today->dayOfWeek === Carbon::SUNDAY || $isHoliday) {
            // HARI LIBUR:
            // Biarkan semua data operasional tetap 0 / kosong.
            // Dengan begini, sistem TIDAK AKAN menghitung Alpa (absentCount tetap 0).

        } else {
            // HARI KERJA (Senin - Sabtu & Bukan Tanggal Merah)

            // A. DATA KEHADIRAN (Hadir & Terlambat)
            // Ambil data absensi hari ini (status 'in')
            $attendancesToday = Attendance::with('user')
                ->whereDate('recorded_at', $today)
                ->where('status', 'in')
                ->get();

            // Hitung Jumlah Hadir (Unique user_id)
            $presentStudentIds = $attendancesToday->pluck('user_id')->unique();
            $presentCount = $presentStudentIds->count();

            // Hitung Terlambat (Contoh: Lewat jam 07:00:00)
            $lateCount = $attendancesToday->filter(function ($att) {
                // Pastikan format jam sesuai dengan setting sekolah Anda
                return Carbon::parse($att->recorded_at)->format('H:i:s') > '07:00:00';
            })->count();

            // Data Absensi Terbaru untuk Widget
            $latestAttendances = Attendance::with('user')
                ->whereDate('recorded_at', $today)
                ->latest('recorded_at')
                ->take(5)
                ->get();

            // B. DATA IZIN / SAKIT (Disetujui & Aktif Hari Ini)
            $approvedPermitsToday = AbsencePermit::with('student') // Pastikan relasi 'student' ada di Model AbsencePermit
                ->where('status', 'disetujui')
                ->whereDate('start_date', '<=', $today)
                ->whereDate('end_date', '>=', $today)
                ->get();

            $permitStudentIds = $approvedPermitsToday->pluck('user_id')->unique();
            $izinHariIni = $permitStudentIds->count();

            // C. DATA ALPA (Tidak Hadir & Tidak Izin)
            // Gabungkan ID siswa yang Hadir dan Izin untuk pengecualian
            $excusedStudentIds = $presentStudentIds->merge($permitStudentIds)->unique();

            // PERBAIKAN UTAMA: Definisi variable $excusedStudentIds sebelum dipakai diff()
            $alpaStudentIds = $allStudentIds->diff($excusedStudentIds);

            // Ambil data detail siswa yang Alpa
            $alpaStudentsToday = User::whereIn('id', $alpaStudentIds)->get(['id', 'name', 'class']);
            $absentCount = $alpaStudentsToday->count();

            // D. Permintaan Izin Baru (Pending) - Widget Notifikasi
            $pendingPermissions = AbsencePermit::with('student')
                ->whereIn('status', ['diajukan', 'pending'])
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();
        }

        return view('admin.dashboard', compact(
            'totalStudents',
            'presentCount',
            'absentCount', // Jumlah Alpa
            'lateCount',
            'izinHariIni', // Jumlah Izin
            'latestAttendances',
            'pendingPermissions',
            'approvedPermitsToday', // List Detail Izin
            'alpaStudentsToday'     // List Detail Alpa
        ));
    }
}
