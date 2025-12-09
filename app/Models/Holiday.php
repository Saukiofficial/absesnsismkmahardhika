<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'holiday_date',
        'description',
    ];

    // Casting agar kolom tanggal otomatis jadi objek Carbon (mudah diformat)
    protected $casts = [
        'holiday_date' => 'date',
    ];
}
