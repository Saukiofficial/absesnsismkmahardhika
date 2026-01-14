<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Models\Attendance;
use Illuminate\Support\Facades\Storage;

class NewAttendance implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Data absensi yang akan disiarkan.
     * Properti ini harus public agar bisa diakses oleh frontend.
     * @var array
     */
    public $attendanceData;

    /**
     * Create a new event instance.
     *
     * @param \App\Models\User $student
     * @param \App\Models\Attendance $attendance
     * @param bool $isLate
     */
    public function __construct(User $student, Attendance $attendance, bool $isLate)
    {
        // ✅ PERBAIKAN: Generate URL foto dengan benar
        $photoUrl = null;

        if ($student->photo) {
            // Cek apakah foto sudah full URL (http/https)
            if (filter_var($student->photo, FILTER_VALIDATE_URL)) {
                $photoUrl = $student->photo;
            } else {
                // Gunakan asset() untuk generate full URL
                $photoUrl = asset('storage/' . $student->photo);
            }
        } else {
            // Fallback ke placeholder jika tidak ada foto
            $photoUrl = 'https://ui-avatars.com/api/?name=' . urlencode($student->name) . '&size=200&background=3b82f6&color=fff&bold=true';
        }

        // Membangun data yang akan dikirim ke halaman monitor
        $this->attendanceData = [
            'student_name' => $student->name,
            'class' => $student->class,
            'status' => $attendance->status,
            'time' => $attendance->recorded_at->format('H:i:s'),
            'photo_url' => $photoUrl,
            'is_late' => $isLate,
        ];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('attendance-channel'),
        ];
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'new-attendance-event';
    }
}
