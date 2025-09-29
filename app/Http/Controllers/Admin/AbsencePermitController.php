<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AbsencePermit;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class AbsencePermitController extends Controller
{
    /**
     * Menampilkan daftar semua pengajuan izin dari siswa.
     */
    public function index(): View
    {
        $permits = AbsencePermit::with('student')->latest()->paginate(15);
        $pageTitle = 'Manajemen Izin Absen';
        return view('admin.permits.index', compact('pageTitle', 'permits'));
    }

    /**
     * PERUBAHAN: Menampilkan halaman detail untuk satu pengajuan izin.
     */
    public function show(AbsencePermit $permit): View
    {
        $pageTitle = 'Detail Pengajuan Izin';
        // Memuat relasi student untuk menampilkan data siswa di view
        $permit->load('student');
        return view('admin.permits.show', compact('pageTitle', 'permit'));
    }

    /**
     * PERUBAHAN: Memperbarui status pengajuan izin (Setuju/Tolak).
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

        return redirect()->route('admin.permits.index')->with('success', 'Status pengajuan izin berhasil diperbarui.');
    }
}

