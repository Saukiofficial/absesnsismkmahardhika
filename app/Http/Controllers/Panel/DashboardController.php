<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache; // PENTING: Untuk fitur ON/OFF Admin
use Carbon\Carbon;
use App\Models\Attendance;
use App\Models\Holiday; // PENTING: Untuk fitur Hari Libur

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard untuk siswa dan wali murid.
     */
    public function index()
    {
        $user = Auth::user();
        $today = Carbon::today();
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        // -----------------------------------------------------------
        // 1. CEK STATUS MANUAL DARI ADMIN (Cache)
        // -----------------------------------------------------------
        // Mengambil status sistem. Default 'open' jika belum diset admin.
        $systemStatus = Cache::get('attendance_system_status', 'open');
        $isSystemClosed = $systemStatus === 'closed';

        // -----------------------------------------------------------
        // 2. CEK HARI LIBUR (Database)
        // -----------------------------------------------------------
        $todayHoliday = Holiday::whereDate('holiday_date', $today)->first();

        // -----------------------------------------------------------
        // 3. CEK AKHIR PEKAN
        // -----------------------------------------------------------
        $isWeekend = $today->isWeekend(); // True jika Sabtu/Minggu


        // ===========================================================
        // LOGIKA PANEL SISWA
        // ===========================================================
        if ($user->role === 'siswa') {
            // Ambil absensi hari ini
            $attendanceToday = $user->attendances()
                ->whereDate('recorded_at', $today)
                ->orderBy('recorded_at', 'asc')
                ->get();

            $checkIn = $attendanceToday->firstWhere('status', 'in');

            // Logika checkout: ambil yang terakhir dengan status 'out'
            $checkOut = $attendanceToday->last(function ($item) {
                return $item->status == 'out';
            });

            // Ambil riwayat bulanan
            $monthlyAttendances = $user->attendances()
                ->whereBetween('recorded_at', [$startOfMonth, $endOfMonth])
                ->orderBy('recorded_at', 'desc')
                ->get()
                ->groupBy(function($date) {
                    return Carbon::parse($date->recorded_at)->format('Y-m-d');
                });

            // Kirim semua variabel ke view
            return view('panel.dashboard', compact(
                'user',
                'checkIn',
                'checkOut',
                'monthlyAttendances',
                'todayHoliday',
                'isWeekend',
                'isSystemClosed' // <-- Variabel baru untuk view
            ));
        }

        // ===========================================================
        // LOGIKA PANEL WALI MURID
        // ===========================================================
        if ($user->role === 'wali') {
            $students = $user->students; // Mengambil semua anak
            $studentIds = $students->pluck('id');

            // Ambil absensi anak-anak bulan ini
            $monthlyAttendances = Attendance::whereIn('user_id', $studentIds)
                ->whereBetween('recorded_at', [$startOfMonth, $endOfMonth])
                ->with('user')
                ->orderBy('recorded_at', 'desc')
                ->get()
                ->groupBy('user_id');

            return view('panel.dashboard', compact(
                'user',
                'students',
                'monthlyAttendances',
                'todayHoliday',
                'isSystemClosed' // Wali juga perlu tahu jika sistem tutup
            ));
        }

        // Fallback jika role tidak dikenali
        return redirect('/login');
    }
}
