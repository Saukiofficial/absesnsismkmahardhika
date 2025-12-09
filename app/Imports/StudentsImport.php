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
      
        $rawNis = trim($row['nis']);
        $cleanNis = $rawNis;
        if (preg_match('/^(\d+)/', $rawNis, $matches)) {
            $cleanNis = $matches[1];
        }


        if (strtolower($row['nama_siswa']) == 'budi doremi' || $cleanNis == '12345') {
            return null;
        }


        if (User::where('nis', $cleanNis)->exists()) {
            return null;
        }


        $guardian = null;

        if (!empty($row['nomor_wa_wali'])) {
            $cleanPhone = $this->formatPhoneNumber($row['nomor_wa_wali']);


            $guardian = User::firstOrCreate(
                ['guardian_phone' => $cleanPhone, 'role' => 'wali'],
                [
                    'name' => $row['nama_wali'] ?? 'Wali Murid',
                    'email' => $cleanPhone . '@wali.com',
                    'password' => isset($row['password_wali']) ? Hash::make($row['password_wali']) : Hash::make('password123'),
                ]
            );
        }

        // 3. SIMPAN SISWA BARU
        return new User([
            'name'          => $row['nama_siswa'],
            'email'         => $row['email_siswa'],
            'password'      => Hash::make($row['password_siswa'] ?? 'password123'),
            'role'          => 'siswa',
            'nis'           => $cleanNis,
            'class'         => $row['tingkat_kelas'] . ' ' . $row['jurusan'],
            'card_uid'      => isset($row['card_uid']) ? (string) $row['card_uid'] : null,
            'guardian_id'   => $guardian ? $guardian->id : null,
            'guardian_phone'=> $guardian ? $guardian->guardian_phone : null,
        ]);
    }

    public function rules(): array
    {
        return [
            'nama_siswa' => 'required',
            'nis' => 'required', // Hapus 'numeric' agar tidak error kena spasi
            'email_siswa' => 'required|email|unique:users,email',
            'nomor_wa_wali' => 'nullable', // Hapus 'unique' agar wali bisa dipakai barengan
        ];
    }

    private function formatPhoneNumber($number)
    {
        $number = preg_replace('/[^0-9]/', '', $number);
        if (substr($number, 0, 1) === '0') {
            $number = '62' . substr($number, 1);
        }
        return $number;
    }
}
