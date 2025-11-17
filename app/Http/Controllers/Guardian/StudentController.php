<?php

namespace App\Http\Controllers\Guardian;

use App\Http\Controllers\Controller;
use App\Models\User; // Model Siswa
use App\Models\Attendance;
use App\Models\AbsencePermit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Pastikan Auth di-import
use Illuminate\View\View;

class StudentController extends Controller
{
    /**
     * Menampilkan halaman detail untuk seorang siswa.
     * Termasuk riwayat absensi dan riwayat izin.
     *
     * VERSI LENGKAP DENGAN LOGIKA STATISTIK + PAGINASI
     */
    public function show(User $student): View
    {
        // Keamanan: Pastikan siswa yang diminta adalah anak dari wali murid yang sedang login.
        abort_if($student->guardian_id !== Auth::id(), 403, 'Anda tidak berhak mengakses data siswa ini.');

        // --- 1. DATA UNTUK TABEL (DENGAN PAGINASI) ---
        // Ini adalah data yang sudah ada di file Anda, untuk mengisi tabel
        $attendances = $student->attendances()->latest('recorded_at')->paginate(10, ['*'], 'attendance_page');
        $groupedAttendances = $attendances->groupBy(fn($item) => $item->recorded_at->format('Y-m-d'));
        $permits = $student->absencePermits()->latest()->paginate(10, ['*'], 'permit_page');


        // --- 2. DATA UNTUK STATISTIK (BULAN INI) ---
        // Ini adalah logika baru untuk mengisi angka '--'
        $startOfMonth = Carbon::now()->startOfMonth();
        $today = Carbon::now()->endOfDay(); // Gunakan endOfDay() untuk rentang

        // Ambil data absensi & izin BULAN INI (tanpa paginasi) untuk dihitung
        $monthlyAttendances = $student->attendances()
                                      ->whereBetween('recorded_at', [$startOfMonth, $today])
                                      ->get();
        $monthlyPermits = $student->absencePermits()
                                  // Ambil izin yang disetujui dan relevan dengan bulan ini
                                  ->where('status', 'disetujui')
                                  ->where(function($query) use ($startOfMonth, $today) {
                                      $query->whereDate('start_date', '<=', $today)
                                            ->whereDate('end_date', '>=', $startOfMonth);
                                  })
                                  ->get();

        // --- 3. HITUNG STATISTIK IZIN (PERMIT STATS) ---
        $permitStats = [
            'approved' => $monthlyPermits->count(), // Sudah difilter 'disetujui'
            'pending'  => $student->absencePermits()->whereIn('status', ['diajukan', 'pending'])->where('start_date', '>=', $startOfMonth)->count(),
            'rejected' => $student->absencePermits()->where('status', 'ditolak')->where('start_date', '>=', $startOfMonth)->count(),
        ];

        // --- 4. HITUNG STATISTIK ABSENSI (ATTENDANCE STATS) ---

        $lateBoundary = '06:30:00';

        // Hitung total hari hadir (unik berdasarkan tanggal)
        $presentDays = $monthlyAttendances
                            ->where('status', 'in')
                            ->unique(fn($item) => $item->recorded_at->format('Y-m-d'))
                            ->count();

        // Hitung total kejadian terlambat
        $lateOccurrences = $monthlyAttendances
                                ->where('status', 'in')
                                ->where(fn($att) => $att->recorded_at->format('H:i:s') > $lateBoundary)
                                ->count();

        // Hitung total hari sekolah (Senin-Sabtu) dari awal bulan sampai hari ini
        $schoolDays = 0;
        $dateIterator = $startOfMonth->clone();
        while ($dateIterator <= $today) {
            if (!$dateIterator->isSunday()) { // Jika bukan hari Minggu
                $schoolDays++;
            }
            $dateIterator->addDay();
        }

        // Hitung hari alpa/tidak hadir
        $absentCount = $schoolDays - $presentDays - $permitStats['approved'];
        if ($absentCount < 0) {
            $absentCount = 0;
        }

        $attendanceStats = [
            'present' => $presentDays,
            'late'    => $lateOccurrences,
            'absent'  => $absentCount,
        ];

        // --- 5. LOGIKA BARU: STATUS HARI INI (UNTUK ORTU) ---
        $todayDate = Carbon::today();
        $statusHariIni = '';
        $detailHariIni = null; // Untuk menyimpan jam masuk jika hadir

        if ($todayDate->isSunday()) {
            $statusHariIni = 'Libur';
        } else {
            // Cek apakah hadir hari ini
            $absensiHariIni = $monthlyAttendances->where('status', 'in')
                                ->where('recorded_at', '>=', $todayDate->startOfDay())
                                ->first();

            // Cek apakah izin hari ini
            $izinHariIni = $monthlyPermits->where('start_date', '<=', $todayDate)
                                         ->where('end_date', '>=', $todayDate)
                                         ->first();

            if ($absensiHariIni) {
                if ($absensiHariIni->recorded_at->format('H:i:s') > $lateBoundary) {
                    $statusHariIni = 'Terlambat';
                } else {
                    $statusHariIni = 'Hadir';
                }
                $detailHariIni = $absensiHariIni->recorded_at->format('H:i');
            } elseif ($izinHariIni) {
                $statusHariIni = 'Izin';
                $detailHariIni = ucfirst($izinHariIni->permit_type); // Cth: "Sakit"
            } else {
                // Jika hari belum berakhir, statusnya "Belum Absen"
                // Jika hari sudah berakhir (misal jam 17:00), statusnya "Alpa"
                if (now()->hour > 17) {
                    $statusHariIni = 'Alpa';
                } else {
                    $statusHariIni = 'Belum Absen';
                }
            }
        }
        // --- AKHIR LOGIKA BARU ---

        $pageTitle = 'Detail Siswa: ' . $student->name;

        // --- 6. KIRIM SEMUA DATA KE VIEW ---
        return view('guardian.students.show', compact(
            'pageTitle',
            'student',
            'attendances',          // Data paginasi untuk tabel absensi
            'groupedAttendances',   // Data group untuk tabel absensi
            'permits',              // Data paginasi untuk tabel izin
            'permitStats',          // Data baru untuk statistik izin
            'attendanceStats',      // Data baru untuk statistik absensi
            'statusHariIni',      // Data baru untuk status hari ini
            'detailHariIni'       // Data baru untuk jam masuk/tipe izin
        ));
    }
}
