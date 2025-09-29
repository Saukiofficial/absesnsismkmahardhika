<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class StudentsImport implements ToModel, WithHeadingRow, WithValidation, SkipsEmptyRows
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Jika kolom nama_siswa kosong, baris ini akan dilewati secara otomatis
        // berkat implementasi SkipsEmptyRows dan validasi 'required'.

        $guardian = null;
        // Cek jika ada data wali murid di baris ini
        if (!empty($row['nomor_wa_wali']) && !empty($row['nama_wali'])) {
            // Logika "Cari atau Buat" wali murid
            $guardian = User::updateOrCreate(
                [
                    'guardian_phone' => $row['nomor_wa_wali'],
                    'role' => 'wali'
                ],
                [
                    'name' => $row['nama_wali'],
                    'email' => $row['nomor_wa_wali'], // Gunakan nomor HP sebagai email unik
                    'password' => isset($row['password_wali']) && $row['password_wali'] ? Hash::make($row['password_wali']) : Hash::make('password123'),
                ]
            );
        }

        // Mengembalikan instance Model baru. Library akan menangani
        // penyimpanan massal di akhir proses.
        return new User([
            'name' => $row['nama_siswa'],
            'email' => $row['email_siswa'],
            'password' => Hash::make($row['password_siswa']),
            'role' => 'siswa',
            'nis' => $row['nis'],
            'class' => $row['tingkat_kelas'] . ' ' . $row['jurusan'],
            'card_uid' => isset($row['card_uid']) ? (string) $row['card_uid'] : null,
            'guardian_id' => $guardian ? $guardian->id : null,
        ]);
    }

    /**
     * Aturan validasi untuk setiap baris di file Excel.
     */
    public function rules(): array
    {
        return [
            'nama_siswa' => 'required|string',
            'email_siswa' => 'required|email|unique:users,email',
            'nis' => 'required|numeric|unique:users,nis',
            'password_siswa' => 'required|min:8',
            'nama_wali' => 'nullable|string',
            'nomor_wa_wali' => 'nullable|numeric|unique:users,guardian_phone',
            'password_wali' => 'nullable|min:8',
            'card_uid' => 'nullable|string|unique:users,card_uid',
        ];
    }
}

