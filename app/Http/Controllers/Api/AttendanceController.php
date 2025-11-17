<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Actions\ProcessAttendanceAction;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon; // 1. Tambahkan use Carbon

class AttendanceController extends Controller
{
    /**
     * Menerima dan memproses data absensi dari perangkat.
     */
    public function receive(Request $request, ProcessAttendanceAction $processAttendance)
    {
        // --- PERBAIKAN: BLOK ABSEN HARI MINGGU ---
        // Carbon::SUNDAY bernilai 0
        if (Carbon::now()->dayOfWeek === Carbon::SUNDAY) {
            // Jika hari ini Minggu, tolak permintaan
            return response()->json([
                'success' => false,
                'message' => 'Sistem absensi ditutup pada hari Minggu.'
            ], 403); // 403 Forbidden
        }
        // --- AKHIR PERBAIKAN ---

        // Validasi input
        $validated = $request->validate([
            'identifier' => 'required|string',
            'device_id' => 'required|string',
            'method' => 'required|string|in:rfid,fingerprint',
        ]);

        Log::info('Validation passed. Executing ProcessAttendanceAction...');

        // Memanggil Action untuk memproses logika
        $result = $processAttendance->execute(
            $validated['identifier'],
            $validated['method'],
            $validated['device_id']
        );

        // Mengembalikan response berdasarkan hasil dari Action
        if ($result['success']) {
            // PENTING: Mengembalikan data absensi agar bisa ditampilkan di popup
            return response()->json($result, 200);
        }

        // Jika gagal, kembalikan pesan error
        return response()->json($result, 404);
    }
}
