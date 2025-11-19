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

            $now = now();


            $isLate = false;
            if ($status === 'in' && $now->format('H:i:s') > self::LATE_BOUNDARY) {
                $isLate = true;
            }

            $attendance = Attendance::create([
                'user_id'     => $student->id,
                'card_uid'    => ($method === 'rfid') ? $identifier : null,
                'device_id'   => $deviceId,
                'method'      => $method,
                'status'      => $status,
                'recorded_at' => $now,
            ]);
            Log::info('Attendance record created for: ' . $student->name);


            try {
                broadcast(new NewAttendance($student, $attendance, $isLate));
                Log::info('Broadcast event success for user: ' . $student->name);
            } catch (\Exception $e) {
                Log::error('Pusher broadcast failed: ' . $e->getMessage());
            }


            if ($student->guardian && $student->guardian->guardian_phone) {
                Log::info('Guardian found with phone number.', ['guardian_name' => $student->guardian->name]);

                $studentName = $student->name;
                $studentClass = $student->class ?? '-';
                $message = "";

                if ($status === 'out') {

                    $message = "Halo Bapak/Ibu Wali Murid 😊\n" .
                               "Kami dari SMK Mahardhika menginformasikan bahwa:\n\n" .
                               "*{$studentName}*, kelas {$studentClass},\n" .
                               "sudah pulang dari sekolah dan tercatat melalui sistem presensi digital SMK Mahardhika hari ini.\n\n" .
                               "Pesan ini dikirim otomatis sebagai bentuk transparansi dan pemantauan kehadiran siswa secara real-time!\n\n" .
                               "Terima kasih, semoga anak - anak selamat sampai rumah dan kembali bertemu dengan keluarga";
                } else {

                    if ($isLate) {

                        $message = "Halo Bapak/Ibu Wali Murid 😊\n" .
                                   "Kami dari SMK Mahardhika menginformasikan bahwa:\n\n" .
                                   "*{$studentName}*, kelas {$studentClass},\n" .
                                   "sudah masuk dan tercatat *hadir terlambat* melalui sistem presensi digital SMK Mahardhika hari ini.\n\n" .
                                   "Pesan ini dikirim otomatis sebagai bentuk transparansi dan pemantauan kehadiran siswa secara real-time!\n\n" .
                                   "Terima kasih, semoga kedepannya bisa lebih disiplin waktu";
                    } else {

                        $message = "Halo Bapak/Ibu Wali Murid 😊\n" .
                                   "Kami dari SMK Mahardhika menginformasikan bahwa:\n\n" .
                                   "*{$studentName}*, kelas {$studentClass},\n" .
                                   "sudah masuk dan tercatat hadir melalui sistem presensi digital SMK Mahardhika hari ini.\n\n" .
                                   "Pesan ini dikirim otomatis sebagai bentuk transparansi dan pemantauan kehadiran siswa secara real-time!\n\n" .
                                   "Terima kasih, semoga aktivitas belajar berjalan lancar";
                    }
                }

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
                'is_late'      => $isLate,
            ];

            return [
                'success'        => true,
                'message'        => 'Absensi berhasil dicatat.',
                'is_late'        => $isLate,
                'attendanceData' => $responseData,
            ];

        } catch (\Exception $e) {
            Log::error('ProcessAttendanceAction Error: ' . $e->getMessage() . ' at ' . $e->getFile() . ':' . $e->getLine());
            return ['success' => false, 'message' => 'Terjadi kesalahan fatal di server.'];
        }
    }
}
