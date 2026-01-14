<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// use Laravel\Sanctum\HasApiTokens; // KOMENTAR: Baris ini dimatikan karena Sanctum belum diinstall

class User extends Authenticatable
{
    // use HasApiTokens, HasFactory, Notifiable; // SEBELUMNYA
    use HasFactory, Notifiable; // PERBAIKAN: Hapus HasApiTokens sementara

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',           // admin, siswa, wali
        'nis',            // Nomor Induk Siswa
        'class',          // Kelas & Jurusan
        'card_uid',       // UID Kartu RFID
        'fingerprint_id', // ID Fingerprint
        'guardian_id',    // ID Wali (Relasi ke user lain)
        'guardian_phone', // No HP Wali
        'photo',          // Foto Profil
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * PENTING: Fungsi ini memperbaiki error "Call to undefined method hasRole"
     * Cek apakah user memiliki role tertentu.
     */
    public function hasRole($role)
    {
        return $this->role === $role;
    }

    /**
     * Scope query untuk mempermudah pengambilan data siswa
     * Digunakan di: StudentController::index -> User::student()
     */
    public function scopeStudent($query)
    {
        return $query->where('role', 'siswa');
    }

    /**
     * Scope Alias: Agar User::students() (jamak) juga bisa jalan
     * Memperbaiki jika ada kode yang memanggil versi jamak
     */
    public function scopeStudents($query)
    {
        return $query->where('role', 'siswa');
    }

    /**
     * Scope query untuk mempermudah pengambilan data wali
     * Digunakan di: GuardianController::index -> User::guardian()
     * INI YANG MEMPERBAIKI ERROR "Call to undefined method Builder::guardian"
     */
    public function scopeGuardian($query)
    {
        return $query->where('role', 'wali');
    }

    /**
     * Relasi: User (Siswa) memiliki banyak Absensi
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Relasi: User (Siswa) memiliki banyak Pengajuan Izin
     */
    public function absencePermits()
    {
        return $this->hasMany(AbsencePermit::class);
    }

    /**
     * Relasi: Siswa memiliki satu Wali (Parent)
     */
    public function guardian()
    {
        return $this->belongsTo(User::class, 'guardian_id');
    }

    /**
     * Relasi: Wali memiliki banyak Siswa (Children)
     */
    public function children()
    {
        return $this->hasMany(User::class, 'guardian_id');
    }

    /**
     * Relasi Alias: Wali memiliki banyak Siswa
     * Memperbaiki error jika dipanggil $guardian->students()
     */
    public function students()
    {
        return $this->hasMany(User::class, 'guardian_id');
    }
}
