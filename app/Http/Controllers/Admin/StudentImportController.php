<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\StudentsImport;
use Maatwebsite\Excel\Excel as ExcelFormat;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class StudentImportController extends Controller
{
    public function show()
    {
        $pageTitle = 'Impor Data Siswa';
        return view('admin.students.import', compact('pageTitle'));
    }

    /**
     * PERBAIKAN: Menggunakan try-catch untuk menangani ValidationException secara langsung.
     * Ini adalah cara yang benar untuk mendapatkan pesan error yang spesifik.
     */
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        try {
            // Memulai proses impor
            Excel::import(new StudentsImport, $request->file('file'));

        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
             // Menangkap error validasi dari file Excel
             $failures = $e->failures();
             $errorMessages = [];
             foreach ($failures as $failure) {
                 // Membuat pesan error yang jelas untuk setiap baris yang gagal
                 // Contoh: "Baris ke-3: The card uid has already been taken."
                 $errorMessages[] = "Baris ke-{$failure->row()}: " . implode(', ', $failure->errors());
             }
             return back()->with('error', 'Gagal mengimpor data. Terdapat beberapa kesalahan: <br>' . implode('<br>', $errorMessages));

        } catch (\Exception $e) {
            // Menangkap error umum lainnya
            return back()->with('error', 'Terjadi kesalahan saat memproses file: ' . $e->getMessage());
        }

        // Jika tidak ada exception yang tertangkap, berarti impor berhasil.
        return redirect()->route('admin.students.index')->with('success', 'Data siswa berhasil diimpor!');
    }

    public function downloadTemplate(): BinaryFileResponse
    {
        $data = [
            ['nama_siswa', 'email_siswa', 'nis', 'tingkat_kelas', 'jurusan', 'password_siswa', 'nama_wali', 'nomor_wa_wali', 'password_wali', 'card_uid'],
            ['Budi Doremi', 'budi@example.com', '12345', 'X', 'AKUNTANSI', 'password123', 'Ahmad Sanjaya', '6281234567890', 'passwordwali123', '0001234567'],
        ];
        $export = new class($data) implements \Maatwebsite\Excel\Concerns\FromArray, \Maatwebsite\Excel\Concerns\WithHeadings {
            public function __construct(private array $data) {}
            public function headings(): array { return array_shift($this->data); }
            public function array(): array { return $this->data; }
        };
        return Excel::download($export, 'template_siswa_lengkap.xlsx', ExcelFormat::XLSX);
    }
}

