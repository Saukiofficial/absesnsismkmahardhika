<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Illuminate\Validation\Rule;

class StudentsImport implements ToModel, WithHeadingRow, WithValidation, SkipsEmptyRows
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // 1. DATA CLEANING (PEMBERSIHAN DATA)

        // Bersihkan NIS: Ambil angka depan saja dari format "7623 / 4535 .102"
        $rawNis = trim($row['nis']);
        $cleanNis = $rawNis;
        if (preg_match('/^(\d+)/', $rawNis, $matches)) {
            $cleanNis = $matches[1];
        }

        // Cek Data Dummy (Budi Doremi) agar tidak ikut terimpor berulang kali
        if (strtolower($row['nama_siswa']) == 'budi doremi' || $cleanNis == '12345') {
            return null;
        }

        // Cek Manual Duplikasi NIS di Database (untuk menghindari error SQL)
        if (User::where('nis', $cleanNis)->exists()) {
            return null; // Skip siswa ini jika NIS sudah ada
        }

        // 2. LOGIKA WALI MURID (Cerdas)
        $guardian = null;

        // Hanya proses wali jika data nomor HP ada
        if (!empty($row['nomor_wa_wali'])) {
            // Bersihkan nomor HP (hapus spasi/strip, ubah 08 jadi 628)
            $cleanPhone = $this->formatPhoneNumber($row['nomor_wa_wali']);

            // Cari wali berdasarkan nomor HP, atau buat baru jika belum ada
            $guardian = User::firstOrCreate(
                ['guardian_phone' => $cleanPhone, 'role' => 'wali'], // Kunci pencarian
                [
                    'name' => $row['nama_wali'] ?? 'Wali Murid',
                    'email' => $cleanPhone . '@wali.com', // Email dummy unik dari no HP
                    'password' => isset($row['password_wali']) ? Hash::make($row['password_wali']) : Hash::make('password123'),
                ]
            );
        }

        // 3. SIMPAN SISWA BARU
        return new User([
            'name'          => $row['nama_siswa'],
            'email'         => $row['email_siswa'], // Pastikan email unik di Excel
            'password'      => Hash::make($row['password_siswa'] ?? 'password123'),
            'role'          => 'siswa',
            'nis'           => $cleanNis, // Masukkan NIS yang sudah bersih
            'class'         => $row['tingkat_kelas'] . ' ' . $row['jurusan'],
            'card_uid'      => isset($row['card_uid']) ? (string) $row['card_uid'] : null,
            'guardian_id'   => $guardian ? $guardian->id : null,
            'guardian_phone'=> $guardian ? $guardian->guardian_phone : null, // Opsional: simpan juga no hp di tabel siswa jika perlu
        ]);
    }

    /**
     * Rules validasi.
     * PENTING: Saya melonggarkan beberapa aturan agar ditangani logic di atas.
     */
    public function rules(): array
    {
        return [
            'nama_siswa' => 'required',
            // Hapus 'numeric' dari NIS karena data mentah Excel mengandung spasi/garis miring
            'nis' => 'required',

            'email_siswa' => 'required|email|unique:users,email',

            // PENTING: Hapus 'unique' dari nomor_wa_wali agar 1 wali bisa untuk banyak siswa
            'nomor_wa_wali' => 'nullable',
        ];
    }

    /**
     * Helper untuk memformat nomor WA (mengubah 08xx jadi 628xx)
     */
    private function formatPhoneNumber($number)
    {
        $number = preg_replace('/[^0-9]/', '', $number); // Hapus karakter selain angka
        if (substr($number, 0, 1) === '0') {
            $number = '62' . substr($number, 1);
        }
        return $number;
    }
}
