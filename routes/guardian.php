<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Guardian\DashboardController;
use App\Http\Controllers\Guardian\Auth\LoginController;
use App\Http\Controllers\Guardian\StudentController;
use App\Http\Controllers\Guardian\ProfileController;

/*
|--------------------------------------------------------------------------
| Guardian Routes
|--------------------------------------------------------------------------
|
| Rute-rute ini khusus untuk panel wali murid. Mereka akan memiliki
| prefix 'guardian' dan middleware otentikasi sendiri.
|
*/

// Grup untuk halaman yang tidak memerlukan login (tamu)
Route::middleware('guest:web')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
});

// Grup untuk halaman yang memerlukan login wali murid
Route::middleware(['auth:web', 'role:wali'])->group(function () {
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('students/{student}', [StudentController::class, 'show'])->name('students.show');

    // Rute untuk profil wali murid
    Route::get('profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
});

