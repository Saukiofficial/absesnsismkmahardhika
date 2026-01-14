<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\User;
use App\Models\Holiday;
use App\Models\AbsencePermit;
use Carbon\Carbon;
use App\Exports\AttendancesExport;
use Maatwebsite\Excel\Facades\Excel;

class AttendanceController extends Controller
{
    /**
     * Menampilkan daftar rekapitulasi absensi.
     */
    public function index(Request $request)
    {
        $processedAttendances = $this->getFilteredAttendances($request);

        $classes = User::where('role', 'siswa')
                      ->select('class')
                      ->whereNotNull('class')
                      ->distinct()
                      ->orderBy('class')
                      ->pluck('class');

        $holidays = Holiday::orderBy('holiday_date', 'desc')->get();

        return view('admin.attendances.index', [
            'attendances' => $processedAttendances,
            'classes' => $classes,
            'holidays' => $holidays,
            'filters' => $request->only(['start_date', 'end_date', 'class']),
        ]);
    }

    /**
     * Export data ke Excel.
     */
    public function export(Request $request)
    {
        $processedAttendances = $this->getFilteredAttendances($request);
        $fileName = 'rekap_absensi_' . Carbon::now()->format('d-m-Y') . '.xlsx';

        return Excel::download(new AttendancesExport($processedAttendances), $fileName);
    }

    /**
     * Logika Inti Pemrosesan Data Absensi & Izin
     */
    private function getFilteredAttendances(Request $request)
    {
        // 1. Tentukan Range Tanggal (Default: Semester Ini)
        $startDate = null;
        $endDate = null;

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $endDate = Carbon::parse($request->end_date)->endOfDay();
        } else {
            // Logika Semester Otomatis
            $currentMonth = Carbon::now()->month;
            $currentYear = Carbon::now()->year;
            if ($currentMonth >= 7) {
                // Semester Ganjil (Juli - Des)
                $startDate = Carbon::create($currentYear, 7, 1)->startOfDay();
                $endDate = Carbon::create($currentYear, 12, 31)->endOfDay();
            } else {
                // Semester Genap (Jan - Jun)
                $startDate = Carbon::create($currentYear, 1, 1)->startOfDay();
                $endDate = Carbon::create($currentYear, 6, 30)->endOfDay();
            }
        }

        // PENTING: Jangan ambil data masa depan (cegah tgl 14 muncul di tgl 13)
        $todayEnd = Carbon::now()->endOfDay();
        if ($endDate > $todayEnd) {
            $endDate = $todayEnd;
        }

        // 2. Query Data HADIR (Attendance)
        $attendanceQuery = Attendance::with('user')
            ->whereBetween('recorded_at', [$startDate, $endDate]);

        if ($request->filled('class')) {
            $attendanceQuery->whereHas('user', function ($q) use ($request) {
                $q->where('class', $request->class);
            });
        }
        $attendances = $attendanceQuery->orderBy('recorded_at', 'desc')->get();

        // 3. Query Data IZIN/SAKIT (AbsencePermit)
        $permitQuery = AbsencePermit::with('user')
            ->where('status', 'disetujui')
            ->where(function($q) use ($startDate, $endDate) {
                $q->whereBetween('start_date', [$startDate, $endDate])
                  ->orWhereBetween('end_date', [$startDate, $endDate])
                  ->orWhere(function($sub) use ($startDate, $endDate) {
                      $sub->where('start_date', '<=', $startDate)
                          ->where('end_date', '>=', $endDate);
                  });
            });

        if ($request->filled('class')) {
            $permitQuery->whereHas('user', function ($q) use ($request) {
                $q->where('class', $request->class);
            });
        }
        $permits = $permitQuery->get();

        // 4. Proses Penggabungan Data
        $processedData = [];

        // A. Masukkan Data Hadir
        $groupedAttendances = $attendances->groupBy(function ($item) {
            return $item->recorded_at->format('Y-m-d') . '_' . $item->user_id;
        });

        foreach ($groupedAttendances as $key => $group) {
            // Cari data IN dan OUT (Case Insensitive agar 'IN', 'In', 'in' terbaca semua)
            $checkIn = $group->first(function ($item) {
                return strtolower($item->status) === 'in' || strtolower($item->status) === 'masuk';
            });
            $checkOut = $group->last(function ($item) {
                return strtolower($item->status) === 'out' || strtolower($item->status) === 'pulang';
            });

            $user = $group->first()->user;

            // FILTER HANTU: Hanya masukkan data jika User Ada DAN (Ada In ATAU Ada Out)
            // Ini akan membuang baris yang jamnya kosong semua (penyebab -- -- Tepat Waktu)
            if ($user && ($checkIn || $checkOut)) {
                 $processedData[] = [
                    'sort_date' => $group->first()->recorded_at->timestamp,
                    'user_id' => $user->id,
                    'user_nis' => $user->nis,
                    'date' => $group->first()->recorded_at->translatedFormat('d F Y'),
                    'raw_date' => $group->first()->recorded_at->format('Y-m-d'),
                    'user_name' => $user->name,
                    'class' => $user->class,
                    'check_in' => $checkIn ? $checkIn->recorded_at->format('H:i:s') : null,
                    'check_out' => $checkOut ? $checkOut->recorded_at->format('H:i:s') : null,
                    'status_in' => $checkIn ? $checkIn->status_CheckIn : null,
                    'status_out' => $checkOut ? $checkOut->status_CheckIn : null,
                    'keterangan' => 'Hadir',
                ];
            }
        }

        // B. Masukkan Data Izin (Looping range tanggal izin)
        foreach ($permits as $permit) {
            $periodStart = Carbon::parse($permit->start_date);
            $periodEnd = Carbon::parse($permit->end_date);

            // Batasi loop agar tidak keluar dari filter yang dipilih user
            $loopStart = $periodStart->max($startDate);
            $loopEnd = $periodEnd->min($endDate);

            while ($loopStart <= $loopEnd) {
                // Skip Hari Minggu
                if (!$loopStart->isSunday()) {
                    $processedData[] = [
                        'sort_date' => $loopStart->timestamp,
                        'user_id' => $permit->user_id,
                        'user_nis' => $permit->user ? $permit->user->nis : '-',
                        'date' => $loopStart->translatedFormat('d F Y'),
                        'raw_date' => $loopStart->format('Y-m-d'),
                        'user_name' => $permit->user ? $permit->user->name : 'Unknown',
                        'class' => $permit->user ? $permit->user->class : '-',
                        'check_in' => '-',
                        'check_out' => '-',
                        'status_in' => ucfirst($permit->permit_type), // Izin/Sakit
                        'status_out' => '-',
                        'keterangan' => ucfirst($permit->permit_type),
                    ];
                }
                $loopStart->addDay();
            }
        }

        // 5. Sorting Akhir (Tanggal Terbaru -> Nama A-Z)
        usort($processedData, function ($a, $b) {
            if ($a['sort_date'] == $b['sort_date']) {
                return strcmp($a['user_name'], $b['user_name']);
            }
            return $b['sort_date'] <=> $a['sort_date'];
        });

        return $processedData;
    }

    /**
     * API untuk mengambil detail statistik siswa (Sidebar Preview)
     */
    public function getStudentDetail(User $student)
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $today = Carbon::now();

        $calculationStartDate = $startOfMonth->clone();
        if ($student->created_at && $student->created_at > $startOfMonth) {
            $calculationStartDate = $student->created_at->startOfDay();
        }

        $schoolDays = 0;
        $dateIterator = $calculationStartDate->clone();
        while ($dateIterator <= $today) {
            if (!$dateIterator->isSunday()) {
                $schoolDays++;
            }
            $dateIterator->addDay();
        }

        $monthlyAttendances = $student->attendances()
            ->whereBetween('recorded_at', [$startOfMonth, $today->endOfDay()])
            ->get();

        $hadirCount = $monthlyAttendances->where('status', 'in')
            ->unique(fn($i) => $i->recorded_at->format('Y-m-d'))
            ->count();

        $permits = $student->absencePermits()
            ->where('status', 'disetujui')
            ->where(function($q) use ($startOfMonth, $today) {
                 $q->whereDate('start_date', '<=', $today)
                   ->whereDate('end_date', '>=', $startOfMonth);
            })->get();

        $permitDays = 0;
        $sakitCount = 0;
        $izinCount = 0;

        foreach($permits as $permit) {
            if($permit->permit_type == 'sakit') $sakitCount++;
            else $izinCount++;

            $start = Carbon::parse($permit->start_date);
            $end = Carbon::parse($permit->end_date);

            if ($start < $startOfMonth) $start = $startOfMonth;
            if ($end > $today) $end = $today;

            $diff = $start->diffInDays($end) + 1;
            $permitDays += $diff;
        }

        $alpaCount = max(0, $schoolDays - $hadirCount - $permitDays);

        $history = $student->attendances()
            ->latest()
            ->take(5)
            ->get()
            ->map(function($att) {
                return [
                    'date' => $att->recorded_at->translatedFormat('d M Y'),
                    'time' => $att->recorded_at->format('H:i'),
                    'status' => $att->status == 'in' ? 'Masuk' : 'Pulang',
                    'status_label' => $att->status == 'in' ? 'Absen Masuk' : 'Absen Pulang',
                    'color' => $att->status == 'in' ? 'text-emerald-600 bg-emerald-50' : 'text-amber-600 bg-amber-50'
                ];
            });

        return response()->json([
            'name' => $student->name,
            'nis' => $student->nis,
            'class' => $student->class,
            'photo' => $student->photo ? asset('storage/'.$student->photo) : null,
            'stats' => [
                'hadir' => $hadirCount,
                'sakit' => $sakitCount,
                'izin' => $izinCount,
                'alpa' => $alpaCount
            ],
            'history' => $history
        ]);
    }
}
