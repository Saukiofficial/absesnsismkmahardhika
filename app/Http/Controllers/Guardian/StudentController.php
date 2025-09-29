<?php

namespace App\Http\Controllers\Guardian;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class StudentController extends Controller
{
    /**
     * Menampilkan halaman detail untuk seorang siswa.
     * Termasuk riwayat absensi dan riwayat izin.
     */
    public function show(User $student): View
    {
        // Keamanan: Pastikan siswa yang diminta adalah anak dari wali murid yang sedang login.
        abort_if($student->guardian_id !== Auth::id(), 403, 'Anda tidak berhak mengakses data siswa ini.');

        // Mengambil riwayat absensi dan mengelompokkannya per tanggal
        $attendances = $student->attendances()->latest('recorded_at')->paginate(10, ['*'], 'attendance_page');
        $groupedAttendances = $attendances->groupBy(fn($item) => $item->recorded_at->format('Y-m-d'));

        // Mengambil riwayat izin
        $permits = $student->absencePermits()->latest()->paginate(10, ['*'], 'permit_page');

        $pageTitle = 'Detail Siswa: ' . $student->name;

        return view('guardian.students.show', compact(
            'pageTitle',
            'student',
            'attendances',
            'groupedAttendances',
            'permits'
        ));
    }
}
