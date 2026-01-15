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

    public function export(Request $request)
    {
        $processedAttendances = $this->getFilteredAttendances($request);
        $fileName = 'rekap_absensi_' . Carbon::now()->format('d-m-Y') . '.xlsx';

        return Excel::download(new AttendancesExport($processedAttendances), $fileName);
    }

    private function getFilteredAttendances(Request $request)
    {
        // 1. Tentukan Range Tanggal
        $startDate = null;
        $endDate = null;

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $endDate = Carbon::parse($request->end_date)->endOfDay();
        } else {
            // Default: Semester Ini
            $currentMonth = Carbon::now()->month;
            $currentYear = Carbon::now()->year;
            if ($currentMonth >= 7) {
                $startDate = Carbon::create($currentYear, 7, 1)->startOfDay();
                $endDate = Carbon::create($currentYear, 12, 31)->endOfDay();
            } else {
                $startDate = Carbon::create($currentYear, 1, 1)->startOfDay();
                $endDate = Carbon::create($currentYear, 6, 30)->endOfDay();
            }
        }

        // Cegah data masa depan
        $todayEnd = Carbon::now()->endOfDay();
        if ($endDate > $todayEnd) {
            $endDate = $todayEnd;
        }

        // 2. Query Data HADIR
        $attendanceQuery = Attendance::with('user')
            ->whereBetween('recorded_at', [$startDate, $endDate]);

        if ($request->filled('class')) {
            $attendanceQuery->whereHas('user', function ($q) use ($request) {
                $q->where('class', $request->class);
            });
        }
        $attendances = $attendanceQuery->orderBy('recorded_at', 'desc')->get();

        // 3. Query Data IZIN/SAKIT
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
            $checkIn = $group->first(function ($item) {
                return strtolower($item->status) === 'in' || strtolower($item->status) === 'masuk';
            });
            $checkOut = $group->last(function ($item) {
                return strtolower($item->status) === 'out' || strtolower($item->status) === 'pulang';
            });

            $user = $group->first()->user;

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

        // B. Masukkan Data Izin
        foreach ($permits as $permit) {
            $periodStart = Carbon::parse($permit->start_date);
            $periodEnd = Carbon::parse($permit->end_date);
            $loopStart = $periodStart->max($startDate);
            $loopEnd = $periodEnd->min($endDate);

            while ($loopStart <= $loopEnd) {
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
                        'status_in' => ucfirst($permit->permit_type),
                        'status_out' => '-',
                        'keterangan' => ucfirst($permit->permit_type),
                    ];
                }
                $loopStart->addDay();
            }
        }

        usort($processedData, function ($a, $b) {
            if ($a['sort_date'] == $b['sort_date']) {
                return strcmp($a['user_name'], $b['user_name']);
            }
            return $b['sort_date'] <=> $a['sort_date'];
        });

        return $processedData;
    }

    /**
     * API Detail Siswa (Sidebar) - SEKARANG MENGGUNAKAN LOGIKA SEMESTER
     */
    public function getStudentDetail(User $student)
    {
        $today = Carbon::now();
        $currentMonth = $today->month;
        $currentYear = $today->year;

        // 1. Tentukan Awal Semester (Ganjil/Genap)
        if ($currentMonth >= 7) {
            // Semester Ganjil (Mulai 1 Juli)
            $startPeriod = Carbon::create($currentYear, 7, 1)->startOfDay();
        } else {
            // Semester Genap (Mulai 1 Januari)
            $startPeriod = Carbon::create($currentYear, 1, 1)->startOfDay();
        }

        // 2. Tentukan Tanggal Mulai Hitung (Untuk Siswa Baru)
        $calculationStartDate = $startPeriod->clone();
        if ($student->created_at && $student->created_at > $startPeriod) {
            $calculationStartDate = $student->created_at->startOfDay();
        }

        // 3. Hitung Hari Sekolah (Senin-Sabtu) sampai Hari Ini
        $schoolDays = 0;
        $dateIterator = $calculationStartDate->clone();
        while ($dateIterator <= $today) {
            if (!$dateIterator->isSunday()) {
                $schoolDays++;
            }
            $dateIterator->addDay();
        }

        // 4. Hitung Hadir (Semester Ini)
        $semesterAttendances = $student->attendances()
            ->whereBetween('recorded_at', [$startPeriod, $today->endOfDay()])
            ->get();

        $hadirCount = $semesterAttendances->where('status', 'in')
            ->unique(fn($i) => $i->recorded_at->format('Y-m-d'))
            ->count();

        // 5. Hitung Izin/Sakit (Semester Ini)
        $permits = $student->absencePermits()
            ->where('status', 'disetujui')
            ->where(function($q) use ($startPeriod, $today) {
                 $q->whereDate('start_date', '<=', $today)
                   ->whereDate('end_date', '>=', $startPeriod);
            })->get();

        $permitDays = 0;
        $sakitCount = 0;
        $izinCount = 0;

        foreach($permits as $permit) {
            if($permit->permit_type == 'sakit') $sakitCount++;
            else $izinCount++;

            $start = Carbon::parse($permit->start_date);
            $end = Carbon::parse($permit->end_date);

            // Batasi perhitungan agar tidak keluar dari semester ini
            if ($start < $startPeriod) $start = $startPeriod;
            if ($end > $today) $end = $today;

            $diff = $start->diffInDays($end) + 1;
            $permitDays += $diff;
        }

        // 6. Hitung Alpa
        $alpaCount = max(0, $schoolDays - $hadirCount - $permitDays);

        // 7. Riwayat Terakhir
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
