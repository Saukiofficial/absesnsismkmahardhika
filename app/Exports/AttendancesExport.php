<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize; // Agar lebar kolom otomatis
use Maatwebsite\Excel\Concerns\WithStyles;     // Agar header bisa di-bold
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Collection;

class AttendancesExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $attendances;

    /**
     * Menerima data absensi yang sudah diproses dari controller.
     *
     * @param array $attendances
     */
    public function __construct(array $attendances)
    {
        // Mengubah array biasa menjadi Laravel Collection
        $this->attendances = collect($attendances);
    }

    /**
    * Mengembalikan collection data yang akan diekspor.
    */
    public function collection(): Collection
    {
        return $this->attendances;
    }

    /**
     * Header Kolom Excel
     */
    public function headings(): array
    {
        return [
            'Tanggal',
            'NIS',        // Penting untuk identifikasi unik
            'Nama Siswa',
            'Kelas',
            'Jam Masuk',
            'Jam Pulang',
            'Status Kehadiran', // Hadir / Izin / Sakit
            'Catatan',          // Tepat Waktu / Terlambat
        ];
    }

    /**
     * Memetakan data per baris
     */
    public function map($attendance): array
    {
        if (!is_array($attendance)) {
            return [];
        }

        // Ambil status utama dari controller (Hadir/Izin/Sakit)
        $statusUtama = $attendance['keterangan'] ?? 'Alpa';

        $jamMasuk = $attendance['check_in'] ?? '-';
        $jamPulang = $attendance['check_out'] ?? '-';
        $catatan = '-';

        if ($statusUtama == 'Hadir') {
            // Jika Hadir, cek apakah ada jam masuknya
            if ($jamMasuk !== '-' && $jamMasuk !== null) {
                // Ambil status keterlambatan (Tepat Waktu/Terlambat)
                // Jika kosong, default ke 'Tepat Waktu' (asumsi sistem)
                $catatan = $attendance['status_in'] ?? 'Tepat Waktu';
            } else {
                // Jika jam masuk kosong (data hantu), catatan strip
                $catatan = '-';
            }
        }
        elseif ($statusUtama == 'Izin' || $statusUtama == 'Sakit') {
            // Jika Izin/Sakit, jam masuk/pulang dikosongkan agar rapi
            $jamMasuk = '-';
            $jamPulang = '-';
            // Catatan diisi jenis izinnya
            $catatan = $statusUtama;
        }

        return [
            $attendance['date'],
            $attendance['user_nis'] ?? '-', // Menggunakan key user_nis dari controller
            $attendance['user_name'],
            $attendance['class'],
            $jamMasuk,
            $jamPulang,
            $statusUtama,
            $catatan,
        ];
    }

    /**
     * Styling Excel (Header Bold)
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Baris 1 (Header) di-bold
            1 => ['font' => ['bold' => true]],
        ];
    }
}
