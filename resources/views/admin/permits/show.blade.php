@extends('layouts.admin')

@section('header', $pageTitle)

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Tombol Kembali -->
    <div class="mb-6">
        <a href="{{ route('admin.permits.index') }}" class="inline-flex items-center gap-2 text-slate-600 hover:text-slate-900 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span>Kembali ke Manajemen Izin</span>
        </a>
    </div>

    <div class="bg-white p-8 rounded-lg shadow-md">
        <!-- Header Detail -->
        <div class="flex items-center justify-between mb-6 pb-6 border-b border-gray-200">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Detail Pengajuan Izin</h2>
                <p class="text-sm text-gray-500">Diajukan oleh: <span class="font-semibold">{{ $permit->student->name ?? 'Siswa Dihapus' }}</span></p>
            </div>
            <div>
                @if($permit->status == 'disetujui') <span class="px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">Disetujui</span>
                @elseif($permit->status == 'ditolak') <span class="px-3 py-1 text-sm font-semibold rounded-full bg-red-100 text-red-800">Ditolak</span>
                @else <span class="px-3 py-1 text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">Menunggu</span>
                @endif
            </div>
        </div>

        <!-- Detail Konten -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
            <div class="space-y-1"><dt class="text-sm font-medium text-gray-500">Nama Siswa</dt><dd class="text-gray-900 font-semibold">{{ $permit->student->name ?? '-' }}</dd></div>
            <div class="space-y-1"><dt class="text-sm font-medium text-gray-500">Kelas</dt><dd class="text-gray-900">{{ $permit->student->class ?? '-' }}</dd></div>
            <div class="space-y-1"><dt class="text-sm font-medium text-gray-500">Jenis Izin</dt><dd class="text-gray-900">{{ ucfirst($permit->permit_type) }}</dd></div>
            <div class="space-y-1"><dt class="text-sm font-medium text-gray-500">Tanggal Izin</dt><dd class="text-gray-900">{{ \Carbon\Carbon::parse($permit->start_date)->isoFormat('D MMM YY') }} - {{ \Carbon\Carbon::parse($permit->end_date)->isoFormat('D MMM YY') }}</dd></div>
            <div class="md:col-span-2 space-y-1"><dt class="text-sm font-medium text-gray-500">Alasan</dt><dd class="text-gray-900 whitespace-pre-wrap">{{ $permit->reason }}</dd></div>
            <div class="md:col-span-2 space-y-1"><dt class="text-sm font-medium text-gray-500">Lampiran</dt>
                <dd>
                    @if($permit->attachment)
                    <a href="{{ Storage::url($permit->attachment) }}" target="_blank" class="text-indigo-600 hover:underline font-medium flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                        Lihat/Unduh Lampiran
                    </a>
                    @else
                    <span class="text-gray-500">Tidak ada lampiran</span>
                    @endif
                </dd>
            </div>
        </div>

        <!-- Formulir Aksi (Hanya jika status 'Menunggu') -->
        @if($permit->status == 'menunggu')
        <div class="mt-8 pt-6 border-t border-gray-200">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Aksi Persetujuan</h3>
            <form action="{{ route('admin.permits.updateStatus', $permit) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="flex items-center gap-4">
                    <input type="hidden" name="status" id="status_input">
                    <button type="submit" onclick="document.getElementById('status_input').value='disetujui'"
                            class="flex-1 bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-4 rounded-lg transition-colors">
                        Setujui
                    </button>
                    <button type="submit" onclick="document.getElementById('status_input').value='ditolak'"
                            class="flex-1 bg-red-500 hover:bg-red-600 text-white font-bold py-3 px-4 rounded-lg transition-colors">
                        Tolak
                    </button>
                </div>
            </form>
        </div>
        @endif
    </div>
</div>
@endsection
