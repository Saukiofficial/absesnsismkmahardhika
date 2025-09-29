<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    /**
     * Menampilkan halaman riwayat absensi lengkap.
     */
    public function index(): View
    {
        $student = Auth::user();

        // Mengambil semua data absensi, diurutkan dari yang terbaru
        $attendances = $student->attendances()
            ->latest('recorded_at')
            ->paginate(15);

        // Mengelompokkan data absensi berdasarkan tanggal
        $groupedAttendances = $attendances->groupBy(function ($item) {
            return $item->recorded_at->format('Y-m-d');
        });

        return view('student.attendances.index', [
            'pageTitle' => 'Riwayat Absensi',
            'groupedAttendances' => $groupedAttendances,
            'attendances' => $attendances, // Kirim juga data paginasi
        ]);
    }
}
