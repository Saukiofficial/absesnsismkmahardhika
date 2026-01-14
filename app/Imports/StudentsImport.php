<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Validators\Failure;

class StudentsImport implements ToModel, WithHeadingRow, WithValidation, SkipsEmptyRows, SkipsOnFailure
{
    use SkipsFailures;

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {

        $cleanNis = trim($row['nis']);


        if (strtolower($row['nama_siswa']) == 'budi doremi') {
            return null;
        }


        if (User::where('nis', $cleanNis)->orWhere('email', $row['email_siswa'])->exists()) {
            return null;
        }


        $guardian = null;
        if (!empty($row['nomor_wa_wali'])) {
            $cleanPhone = $this->formatPhoneNumber($row['nomor_wa_wali']);


            $guardian = User::firstOrCreate(
                ['guardian_phone' => $cleanPhone],
                [
                    'name' => $row['nama_wali'] ?? 'Wali Murid',
                    'email' => $cleanPhone . '@wali.com',
                    'role' => 'wali',
                    'password' => isset($row['password_wali']) ? Hash::make($row['password_wali']) : Hash::make('password123'),
                ]
            );
        }


        $kelasString = $row['tingkat_kelas'] . ' ' . $row['jurusan'];

        return new User([
            'name'          => $row['nama_siswa'],
            'email'         => $row['email_siswa'],
            'password'      => Hash::make($row['password_siswa'] ?? 'password123'),
            'role'          => 'siswa',
            'nis'           => $cleanNis,
            'class'         => $kelasString,
            'card_uid'      => isset($row['card_uid']) ? (string) $row['card_uid'] : null,
            'guardian_id'   => $guardian ? $guardian->id : null,
            'guardian_phone'=> $guardian ? $guardian->guardian_phone : null,
        ]);
    }

    public function rules(): array
    {
        return [
            'nama_siswa' => 'required',
            'nis' => 'required',

            'email_siswa' => 'required|email|unique:users,email',
            'nomor_wa_wali' => 'nullable',
        ];
    }

    private function formatPhoneNumber($number)
    {
        // Hapus semua karakter selain angka
        $number = preg_replace('/[^0-9]/', '', $number);

        // Normalisasi 08 ke 628
        if (substr($number, 0, 2) == '08') {
            $number = '62' . substr($number, 1);
        }

        return $number;
    }
}
