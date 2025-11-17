<?php

    namespace App\Http\Controllers\Admin;

    use App\Http\Controllers\Controller;
    use App\Models\AbsencePermit; // Pastikan model ini benar
    use Illuminate\Http\Request;
    use Illuminate\View\View;
    use Illuminate\Http\RedirectResponse;
    use Illuminate\Support\Facades\Auth;

    /**
     * Controller ini diganti namanya menjadi PermitController
     * untuk menghindari konflik dengan AbsencePermitController milik Student
     */
    class PermitController extends Controller
    {
        /**
         * Menampilkan daftar semua pengajuan izin dari SEMUA siswa.
         */
        public function index(Request $request): View
        {
            $query = AbsencePermit::with('student'); // Ambil relasi student

            // Filter berdasarkan status
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            // Filter berdasarkan tanggal
            if ($request->filled('date')) {
                $query->whereDate('start_date', '<=', $request->date)
                      ->whereDate('end_date', '>=', $request->date);
            }

            $permits = $query->latest()->paginate(15)->appends($request->all());

            $pageTitle = 'Manajemen Izin Absen';

            return view('admin.permits.index', compact('pageTitle', 'permits'));
        }

        /**
         * Menampilkan halaman detail untuk satu pengajuan izin.
         */
        public function show(AbsencePermit $permit): View
        {
            $pageTitle = 'Detail Pengajuan Izin';
            $permit->load('student'); // Pastikan data siswa dimuat
            return view('admin.permits.show', compact('pageTitle', 'permit'));
        }

        /**
         * Memperbarui status pengajuan izin (Setuju/Tolak).
         */
        public function updateStatus(Request $request, AbsencePermit $permit): RedirectResponse
        {
            $request->validate([
                'status' => 'required|in:disetujui,ditolak',
            ]);

            $permit->update([
                'status' => $request->status,
                'approved_by' => Auth::id(), // Menyimpan ID admin yang memproses
            ]);

            // TODO: Kirim notifikasi ke siswa/wali jika status berubah

            return redirect()->route('admin.permits.index')->with('success', 'Status pengajuan izin berhasil diperbarui.');
        }
    }
