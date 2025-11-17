<?php

namespace App\Actions;

use App\Models\User;
use App\Models\Attendance;
use App\Jobs\SendWhatsappNotification;
// PERUBAHAN: Menggunakan nama Event asli Anda
use App\Events\NewAttendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ProcessAttendanceAction
{
    /**
     * Batas waktu toleransi keterlambatan.
     */
    const LATE_BOUNDARY = '06:30:00';

    public function execute(string $identifier, string $method, string $deviceId): array
    {
        try {
            $student = User::student()
                ->where($method === 'rfid' ? 'card_uid' : 'fingerprint_id', $identifier)
                ->with('guardian')
                ->first();

            if (!$student) {
                Log::warning('Attendance failed: Student not found.', ['identifier' => $identifier]);
                return ['success' => false, 'message' => 'Siswa tidak ditemukan.'];
            }

            $lastAttendance = Attendance::where('user_id', $student->id)
                ->whereDate('recorded_at', Carbon::today())
                ->latest('recorded_at')
                ->first();

            $status = ($lastAttendance && $lastAttendance->status === 'in') ? 'out' : 'in';

            $now = now(); // Gunakan waktu yang konsisten

            // --- PERBAIKAN: LOGIKA CEK TERLAMBAT ---
            $isLate = false;
            // Cek terlambat HANYA jika statusnya 'in' (Masuk)
            if ($status === 'in' && $now->format('H:i:s') > self::LATE_BOUNDARY) {
                $isLate = true;
            }
            // --- AKHIR PERBAIKAN ---

            $attendance = Attendance::create([
                'user_id'     => $student->id,
                'card_uid'    => ($method === 'rfid') ? $identifier : null, // Simpan UID jika pakai rfid
                'device_id'   => $deviceId,
                'method'      => $method,
                'status'      => $status,
                'recorded_at' => $now,
            ]);
            Log::info('Attendance record created for: ' . $student->name);

            // --- PERBAIKAN ERROR (Baris 73): ---
            // Mengirim 3 argumen ke Event. File Event (NewAttendance.php) HARUS diupdate (lihat file berikutnya).
            try {
                broadcast(new NewAttendance($student, $attendance, $isLate));
                Log::info('Broadcast event success for user: ' . $student->name);
            } catch (\Exception $e) {
                Log::error('Pusher broadcast failed: ' . $e->getMessage());
            }

            // --- PERBAIKAN: Logika Notifikasi WhatsApp ---
            if ($student->guardian && $student->guardian->guardian_phone) {
                Log::info('Guardian found with phone number. Preparing to dispatch WhatsApp notification.', [
                    'guardian_name' => $student->guardian->name,
                    'guardian_phone' => $student->guardian->guardian_phone
                ]);
                $action = ($status === 'in') ? 'masuk' : 'pulang';
                $message = "Halo {$student->guardian->name}, putra/putri Anda {$student->name} sudah {$action} sekolah pada jam " . $attendance->recorded_at->format('H:i');

                // Tambahkan info terlambat jika $isLate true
                if ($isLate) {
                    $message .= " (TERLAMBAT)";
                }

                SendWhatsappNotification::dispatch($student, $student->guardian, $message);
            } else {
                Log::warning('WhatsApp notification not sent. Reason:', [
                    'has_guardian' => !empty($student->guardian),
                    'guardian_has_phone' => !empty($student->guardian->guardian_phone ?? null)
                ]);
            }

            // --- PERBAIKAN: Data untuk API Response ---
            // Data ini hanya untuk response ke scanner, BUKAN untuk broadcast
            $responseData = [
                'student_name' => $student->name,
                'class'        => $student->class ?? 'N/A',
                'status'       => $attendance->status,
                'time'         => $attendance->recorded_at->format('H:i:s'),
                'photo_url'    => $student->photo_url ?? 'https://placehold.co/128x128/0f0f23/64e5e5?text=' . substr($student->name, 0, 1),
                'is_late'      => $isLate, // Kirim status terlambat
            ];

            return [
                'success'        => true,
                'message'        => 'Absensi berhasil dicatat.',
                'is_late'        => $isLate, // Kirim status terlambat di response
                'attendanceData' => $responseData, // Kirim data lengkap
            ];

        } catch (\Exception $e) {
            Log::error('ProcessAttendanceAction Error: ' . $e->getMessage() . ' at ' . $e->getFile() . ':' . $e->getLine());
            return ['success' => false, 'message' => 'Terjadi kesalahan fatal di server.'];
        }
    }
}
