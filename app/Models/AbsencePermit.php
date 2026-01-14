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
     * Relasi ke User (Siswa)
     * Fungsi ini WAJIB ADA agar tidak error saat export data izin
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
