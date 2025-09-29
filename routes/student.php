<?php

use Illuminate\Support\Facades\Route;
// PERBAIKAN: Pastikan semua 'use' statement menggunakan backslash '\'
use App\Http\Controllers\Student\DashboardController;
use App\Http\Controllers\Student\Auth\LoginController;
use App\Http\Controllers\Student\AbsencePermitController;
use App\Http\Controllers\Student\AttendanceController;
use App\Http\Controllers\Student\ProfileController;

/*
|--------------------------------------------------------------------------
| Student Routes
|--------------------------------------------------------------------------
*/

// Grup untuk halaman yang tidak memerlukan login (tamu)
Route::middleware('guest:web')->group(function () {
    // Nama rute tidak perlu prefix 'student.' karena sudah diatur di RouteServiceProvider
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
});

// Grup untuk halaman yang memerlukan login siswa
Route::middleware(['auth:web', 'role:siswa'])->group(function () {
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Rute untuk fitur pengajuan izin
    Route::get('permits', [AbsencePermitController::class, 'index'])->name('permits.index');
    Route::get('permits/create', [AbsencePermitController::class, 'create'])->name('permits.create');
    Route::post('permits', [AbsencePermitController::class, 'store'])->name('permits.store');
    Route::get('permits/{permit}', [AbsencePermitController::class, 'show'])->name('permits.show');

    // Rute untuk riwayat absensi
    Route::get('attendances', [AttendanceController::class, 'index'])->name('attendances.index');

    // Rute untuk profil siswa
    Route::get('profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
});

