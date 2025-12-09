<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\User;
use App\Models\Holiday; // <--- TAMBAHAN: Import Model Holiday
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
        // Ambil data absensi yang sudah difilter/diproses
        $processedAttendances = $this->getFilteredAttendances($request);

        // Ambil daftar kelas untuk filter
        $classes = User::where('role', 'siswa')
                      ->select('class')
                      ->whereNotNull('class')
                      ->distinct()
                      ->orderBy('class')
                      ->pluck('class');

        // <--- TAMBAHAN: Ambil data Hari Libur untuk ditampilkan di View --->
        // Berguna agar Admin tahu kenapa tanggal tertentu kosong (ternyata libur)
        $holidays = Holiday::orderBy('holiday_date', 'desc')->get();

        return view('admin.attendances.index', [
            'attendances' => $processedAttendances,
            'classes' => $classes,
            'holidays' => $holidays, // Kirim data libur ke view
            'filters' => $request->only(['start_date', 'end_date', 'class']),
        ]);
    }

    /**
     * Menangani permintaan ekspor data ke Excel.
     */
    public function export(Request $request)
    {
        $processedAttendances = $this->getFilteredAttendances($request);
        $fileName = 'rekap_absensi_' . Carbon::now()->format('d-m-Y') . '.xlsx';

        return Excel::download(new AttendancesExport($processedAttendances), $fileName);
    }

    /**
     * Logika Filter dan Pemrosesan Data (Private Helper)
     * Memisahkan logika ini agar bisa dipakai oleh index() dan export()
     */
    private function getFilteredAttendances(Request $request)
    {
        $query = Attendance::query()->with('user');

        // Filter Tanggal
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $endDate = Carbon::parse($request->end_date)->endOfDay();
            $query->whereBetween('recorded_at', [$startDate, $endDate]);
        } else {
            // Default: Tampilkan data bulan ini jika tidak ada filter
            $query->whereMonth('recorded_at', Carbon::now()->month);
        }

        // Filter Kelas
        if ($request->filled('class')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('class', $request->class);
            });
        }

        $attendances = $query->orderBy('recorded_at', 'desc')->get();

        // Grouping berdasarkan Tanggal & User ID untuk menyatukan Masuk & Keluar
        $groupedAttendances = $attendances->groupBy(function ($item) {
            return $item->recorded_at->format('Y-m-d') . '_' . $item->user_id;
        });

        $processedAttendances = [];
        foreach ($groupedAttendances as $key => $group) {
            $checkIn = $group->where('status', 'masuk')->first(); // Asumsi status 'masuk'
            $checkOut = $group->where('status', 'keluar')->last(); // Asumsi status 'keluar'
            $user = $group->first()->user;

            if ($user) {
                 $processedAttendances[] = [
                    'date' => $group->first()->recorded_at->translatedFormat('d F Y'),
                    'raw_date' => $group->first()->recorded_at->format('Y-m-d'), // Untuk sorting/cek libur
                    'user_name' => $user->name,
                    'class' => $user->class,
                    'check_in' => $checkIn ? $checkIn->recorded_at->format('H:i:s') : '-',
                    'check_out' => $checkOut ? $checkOut->recorded_at->format('H:i:s') : '-',
                    'status_in' => $checkIn ? $checkIn->status_note : null, // Misal: Telat/Tepat Waktu
                ];
            }
        }

        return $processedAttendances;
    }
}
