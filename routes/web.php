<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
// Controller Admin
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\GuardianController;
use App\Http\Controllers\Admin\AttendanceController as AdminAttendanceController;
use App\Http\Controllers\Admin\SimulationController;
use App\Http\Controllers\Admin\StudentImportController;
use App\Http\Controllers\Admin\GuardianImportExportController;
use App\Http\Controllers\Admin\PermitController;
use App\Http\Controllers\Admin\HolidayController;
use App\Http\Controllers\Admin\SystemController;
// Controller Panel (Siswa/Wali)
use App\Http\Controllers\Panel\DashboardController as PanelDashboardController;
use App\Http\Controllers\Panel\LeaveController;
// Controller Umum
use App\Http\Controllers\MonitorController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Rute untuk Halaman Monitor Absensi (dapat diakses publik)
Route::get('/monitor', [MonitorController::class, 'index'])->name('monitor.index');

// Redirect root to login page
Route::get('/', function () {
    return redirect()->route('login');
});

// Laravel Breeze Authentication Routes
require __DIR__.'/auth.php';

// Rute "Gerbang" setelah login untuk mengarahkan user sesuai role
Route::get('/dashboard', function () {
    $user = Auth::user();
    if ($user->hasRole('admin')) {
        return redirect()->route('admin.dashboard');
    } elseif ($user->hasRole('siswa') || $user->hasRole('wali')) {
        return redirect()->route('panel.dashboard');
    }
    return abort(403);
})->middleware(['auth', 'verified'])->name('dashboard');


// ====================================================
// GROUP ROUTE ADMIN
// ====================================================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard Admin
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // --- MANAJEMEN ABSENSI (ATTENDANCE) ---
    // 1. Route Export Excel (WAJIB DITARUH PALING ATAS di grup attendance)
    Route::get('attendances/export', [AdminAttendanceController::class, 'export'])->name('attendances.export');

    // 2. Route Detail Siswa untuk Sidebar Preview (AJAX)
    Route::get('attendances/{student}/detail', [AdminAttendanceController::class, 'getStudentDetail'])->name('attendances.detail');

    // 3. Route Tampilan Tabel (Index)
    // Dibuat 2 jalur agar kompatibel dengan link sidebar lama & kode baru
    Route::get('attendance', [AdminAttendanceController::class, 'index'])->name('attendance.index');   // Jalur Tunggal
    Route::get('attendances', [AdminAttendanceController::class, 'index'])->name('attendances.index'); // Jalur Jamak (Standar)


    // --- MANAJEMEN SISWA ---
    // 1. Import Siswa & Template
    Route::get('students/import', [StudentImportController::class, 'show'])->name('students.import.show');
    Route::post('students/import', [StudentImportController::class, 'store'])->name('students.import.store');
    Route::get('students/import/template', [StudentImportController::class, 'downloadTemplate'])->name('students.import.template');

    // 2. Hapus Semua Siswa (Reset Data) - Letakkan SEBELUM resource students
    // Menggunakan nama 'students.destroyAll' sesuai permintaan error sebelumnya
    Route::delete('students/destroy-all', [StudentController::class, 'destroyAll'])->name('students.destroyAll');

    // 3. Resource Students (CRUD Siswa)
    Route::resource('students', StudentController::class);


    // --- MANAJEMEN WALI (GUARDIAN) ---
    // 1. Import/Export Wali
    Route::get('guardians/import', [GuardianImportExportController::class, 'showImportForm'])->name('guardians.import.show');
    Route::post('guardians/import', [GuardianImportExportController::class, 'import'])->name('guardians.import.store');
    Route::get('guardians/export', [GuardianImportExportController::class, 'export'])->name('guardians.export');

    // 2. Hapus Semua Wali
    Route::delete('guardians/destroy-all', [GuardianController::class, 'destroyAll'])->name('guardians.destroyAll');

    // 3. Resource Guardians
    Route::resource('guardians', GuardianController::class);


    // --- FITUR LAINNYA ---
    // Simulasi RFID (Development Only)
    Route::get('simulation', [SimulationController::class, 'index'])->name('simulation.index');
    Route::post('simulation', [SimulationController::class, 'store'])->name('simulation.store');

    // Manajemen Izin/Permits
    Route::get('permits', [PermitController::class, 'index'])->name('permits.index');
    Route::get('permits/{permit}', [PermitController::class, 'show'])->name('permits.show');
    Route::put('permits/{permit}/status', [PermitController::class, 'updateStatus'])->name('permits.updateStatus');

    // Manajemen Hari Libur
    Route::get('holidays', [HolidayController::class, 'index'])->name('holidays.index');
    Route::post('holidays', [HolidayController::class, 'store'])->name('holidays.store');
    Route::delete('holidays/{holiday}', [HolidayController::class, 'destroy'])->name('holidays.destroy');

    // On/Off Sistem Absensi (Global Switch)
    Route::post('system/toggle', [SystemController::class, 'toggle'])->name('system.toggle');
});


// ====================================================
// GROUP ROUTE PANEL (SISWA & WALI)
// ====================================================
Route::middleware(['auth', 'role:siswa,wali'])->prefix('panel')->name('panel.')->group(function () {
    Route::get('/dashboard', [PanelDashboardController::class, 'index'])->name('dashboard');

    // Group Khusus Siswa (Pengajuan Izin)
    Route::middleware('role:siswa')->prefix('leaves')->name('leaves.')->group(function () {
        Route::get('/', [LeaveController::class, 'index'])->name('index');
        Route::get('/create', [LeaveController::class, 'create'])->name('create');
        Route::post('/', [LeaveController::class, 'store'])->name('store');
    });
});
