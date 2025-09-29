<?php

namespace App\Actions;

use App\Models\User;
use App\Models\Attendance;
use App\Jobs\SendWhatsappNotification;
use App\Events\NewAttendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ProcessAttendanceAction
{
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

            $attendance = Attendance::create([
                'user_id'     => $student->id,
                'card_uid'    => $identifier,
                'device_id'   => $deviceId,
                'method'      => $method,
                'status'      => $status,
                'recorded_at' => now(),
            ]);
            Log::info('Attendance record created for: ' . $student->name);

            broadcast(new NewAttendance($student, $attendance));

            // --- PERBAIKAN: Menambahkan Log untuk Pengecekan WhatsApp ---
            if ($student->guardian && $student->guardian->guardian_phone) {
                Log::info('Guardian found with phone number. Preparing to dispatch WhatsApp notification.', [
                    'guardian_name' => $student->guardian->name,
                    'guardian_phone' => $student->guardian->guardian_phone
                ]);
                $action = ($status === 'in') ? 'masuk' : 'pulang';
                $message = "Halo {$student->guardian->name}, putra/putri Anda {$student->name} sudah {$action} sekolah pada jam " . $attendance->recorded_at->format('H:i');
                SendWhatsappNotification::dispatch($student, $student->guardian, $message);
            } else {
                Log::warning('WhatsApp notification not sent. Reason:', [
                    'has_guardian' => !empty($student->guardian),
                    'guardian_has_phone' => !empty($student->guardian->guardian_phone ?? null)
                ]);
            }

            $responseData = [
                'student_name' => $student->name,
                'class'        => $student->class ?? 'N/A',
                'status'       => $attendance->status,
                'time'         => $attendance->recorded_at->format('H:i:s'),
                'photo_url'    => $student->photo_url ?? 'https://placehold.co/128x128/0f0f23/64e5e5?text=' . substr($student->name, 0, 1),
            ];

            return [
                'success'        => true,
                'message'        => 'Absensi berhasil dicatat.',
                'attendanceData' => $responseData,
            ];

        } catch (\Exception $e) {
            Log::error('ProcessAttendanceAction Error: ' . $e->getMessage() . ' at ' . $e->getFile() . ':' . $e->getLine());
            return ['success' => false, 'message' => 'Terjadi kesalahan fatal di server.'];
        }
    }
}

