<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            // Relasi ke tabel users (siswa yang izin)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Jenis izin: sakit, izin keluarga, dll.
            $table->string('type'); // 'sakit', 'izin_lainnya'

            // Tanggal mulai dan selesai izin
            $table->date('start_date');
            $table->date('end_date');

            // Alasan detail
            $table->text('reason')->nullable();

            // Bukti (opsional, misalnya foto surat dokter)
            $table->string('attachment')->nullable();

            // Status persetujuan: pending, approved, rejected
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');

            // Siapa yang menyetujui (misal: ID guru/admin) - opsional
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};
