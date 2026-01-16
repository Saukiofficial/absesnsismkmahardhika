<?php

namespace App\Http\Controllers\Guardian;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Attendance;
use App\Models\AbsencePermit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class StudentController extends Controller
{
    /**
     * Menampilkan halaman detail untuk seorang siswa.
     * Termasuk riwayat absensi, riwayat izin, dan statistik.
     */
    public function show(User $student): View
    {
        // Keamanan: Pastikan siswa yang diminta adalah anak dari wali murid yang sedang login.
        abort_if($student->guardian_id !== Auth::id(), 403, 'Anda tidak berhak mengakses data siswa ini.');

        // --- 1. DATA UNTUK TABEL (DENGAN PAGINASI) ---
        $attendances = $student->attendances()->latest('recorded_at')->paginate(10, ['*'], 'attendance_page');
        $groupedAttendances = $attendances->groupBy(fn($item) => $item->recorded_at->format('Y-m-d'));
        $permits = $student->absencePermits()->latest()->paginate(10, ['*'], 'permit_page');


        // --- 2. DATA UNTUK STATISTIK (BULAN INI) ---
        $startOfMonth = Carbon::now()->startOfMonth();
        $today = Carbon::now()->endOfDay();

        // [LOGIKA PERBAIKAN ALPA]
        // Tentukan tanggal mulai perhitungan. Default: Awal Bulan.
        $calculationStartDate = $startOfMonth->clone();

        // Jika siswa baru didaftarkan bulan ini (misal tgl 15),
        // maka hitung hari sekolah mulai dari tgl 15, bukan tgl 1.
        if ($student->created_at && $student->created_at > $startOfMonth) {
            $calculationStartDate = $student->created_at->startOfDay();
        }

        // Hitung Total Hari Sekolah (Senin-Sabtu) dari Tanggal Mulai s/d Hari Ini
        $schoolDays = 0;
        $dateIterator = $calculationStartDate->clone();
        while ($dateIterator <= $today) {
            if (!$dateIterator->isSunday()) {
                $schoolDays++;
            }
            $dateIterator->addDay();
        }

        // Ambil Data Kehadiran Bulan Ini
        $monthlyAttendances = $student->attendances()
            ->whereBetween('recorded_at', [$startOfMonth, $today])
            ->get();

        $hadirCount = $monthlyAttendances->where('status', 'in')
            ->unique(fn($item) => $item->recorded_at->format('Y-m-d'))
            ->count();

        // Ambil Data Izin Bulan Ini
        $izinCount = $student->absencePermits()
            ->where('status', 'disetujui')
            ->where(function($query) use ($startOfMonth, $today) {
                $query->whereDate('start_date', '<=', $today)
                      ->whereDate('end_date', '>=', $startOfMonth);
            })->count();

        // Hitung Alpa (Hari Sekolah - Hadir - Izin)
        // Gunakan max(0, ...) agar tidak negatif jika ada ketidaksesuaian data
        $alpaCount = max(0, $schoolDays - $hadirCount - $izinCount);

        $attendanceStats = [
            'hadir' => $hadirCount,
            'izin'  => $izinCount,
            'alpa'  => $alpaCount, // Hasil perhitungan yang sudah diperbaiki
        ];
        // ---------------------------------------------


        // --- 3. STATUS HARI INI (Logic Tambahan untuk UI) ---
        $todayDate = Carbon::today();
        $lateBoundary = '07:00:00'; // Batas jam terlambat

        $absensiHariIni = $student->attendances()->whereDate('recorded_at', $todayDate)->where('status', 'in')->first();
        $izinHariIni = $student->absencePermits()
            ->where('status', 'disetujui')
            ->whereDate('start_date', '<=', $todayDate)
            ->whereDate('end_date', '>=', $todayDate)
            ->first();

        $statusHariIni = 'Belum Absen';
        $detailHariIni = null;

        if ($todayDate->isSunday()) {
            $statusHariIni = 'Libur';
        } else {
            if ($absensiHariIni) {
                $statusHariIni = $absensiHariIni->recorded_at->format('H:i:s') > $lateBoundary ? 'Terlambat' : 'Hadir';
                $detailHariIni = $absensiHariIni->recorded_at->format('H:i');
            } elseif ($izinHariIni) {
                $statusHariIni = 'Izin';
                $detailHariIni = ucfirst($izinHariIni->permit_type);
            } elseif (now()->hour > 17) {
                // Jika sudah sore/malam belum absen, anggap Alpa untuk status hari ini
                $statusHariIni = 'Alpa';
            }
        }

        $pageTitle = 'Detail Siswa: ' . $student->name;

        return view('guardian.students.show', compact(
            'pageTitle',
            'student',
            'attendances',
            'groupedAttendances',
            'permits',
            'attendanceStats',
            'statusHariIni',
            'detailHariIni'
        ));
    }
}
