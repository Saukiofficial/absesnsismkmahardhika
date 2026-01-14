<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\AbsencePermit;

class AbsencePermitController extends Controller
{
    /**
     * Menampilkan halaman riwayat pengajuan izin.
     */
    public function index(): View
    {
        $permits = Auth::user()->absencePermits()->latest()->paginate(10);

        return view('student.permits.index', [
            'pageTitle' => 'Riwayat Pengajuan Izin',
            'permits' => $permits,
        ]);
    }

    /**
     * Menampilkan detail satu pengajuan izin.
     */
    public function show(AbsencePermit $permit): View
    {
        // Keamanan: Pastikan siswa yang login adalah pemilik izin ini.
        abort_if(Auth::id() !== $permit->user_id, 403);

        return view('student.permits.show', [
            'pageTitle' => 'Detail Pengajuan Izin',
            'permit' => $permit,
        ]);
    }

    /**
     * Menampilkan formulir untuk membuat pengajuan izin baru.
     */
    public function create(): View
    {
        return view('student.permits.create', [
            'pageTitle' => 'Buat Pengajuan Izin'
        ]);
    }


    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'permit_type' => 'required|in:sakit,izin',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:1000',
            'attachment' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $validated['user_id'] = Auth::id();

        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('attachments', 'public');
            $validated['attachment'] = $path;
        }

        AbsencePermit::create($validated);

        return redirect()->route('student.dashboard')->with('success', 'Pengajuan izin berhasil dikirim dan sedang menunggu persetujuan.');
    }
}
