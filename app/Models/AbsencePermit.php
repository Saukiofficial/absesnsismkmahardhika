<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbsencePermit extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'permit_type', // sakit, izin
        'start_date',
        'end_date',
        'reason',
        'attachment',
        'status', // pending, disetujui, ditolak
        'notes',  // catatan admin
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Relasi ke User (Siswa) - Default Laravel biasanya 'user'
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Alias Relasi: 'student'
     * Menambahkan ini agar pemanggilan $permit->student->name tidak error
     * Berguna jika di view atau controller menggunakan kata 'student'
     */
    public function student()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
