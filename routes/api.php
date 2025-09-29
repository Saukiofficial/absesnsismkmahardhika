<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AttendanceController;
use App\Http\Middleware\AuthenticateApiKey; // <-- PASTIKAN MEMANGGIL CLASS YANG BENAR

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Endpoint untuk menerima data absensi dari perangkat
Route::middleware(AuthenticateApiKey::class)->group(function () { // <-- PASTIKAN MEMANGGIL CLASS YANG BENAR
    Route::post('/attendance/receive', [AttendanceController::class, 'receive'])->name('api.attendance.receive');
});
