<?php

// PERBAIKAN: Menggunakan backslash '\' bukan titik '.'
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Attendance;
use App\Models\AbsencePermit;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        // Student specific
        'nis',
        'class',
        'photo',
        'card_uid',
        'fingerprint_id',
        'guardian_id',
        // Guardian specific
        'guardian_name',
        'guardian_phone',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Scope a query to only include students.
     */
    public function scopeStudent(Builder $query): void
    {
        $query->where('role', 'siswa');
    }

    /**
     * Scope a query to only include guardians.
     */
    public function scopeGuardian(Builder $query): void
    {
        $query->where('role', 'wali');
    }

    /**
     * Get the attendances for the user.
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Get the guardian for the student.
     */
    public function guardian(): BelongsTo
    {
        return $this->belongsTo(User::class, 'guardian_id');
    }

    /**
     * Get the students (wards) for the guardian.
     */
    public function students(): HasMany
    {
        return $this->hasMany(User::class, 'guardian_id');
    }

    /**
     * Get all of the absence permits for the User (student).
     */
    public function absencePermits(): HasMany
    {
        return $this->hasMany(AbsencePermit::class, 'user_id');
    }
}

