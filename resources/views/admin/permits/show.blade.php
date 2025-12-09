@extends('layouts.admin')

@section('header', $pageTitle)

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 p-6">
    <div class="max-w-5xl mx-auto">
        <!-- Tombol Kembali dengan Style Modern -->
        <div class="mb-8">
            <a href="{{ route('admin.permits.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white hover:bg-gray-50 text-slate-700 font-medium rounded-xl shadow-sm border border-gray-200 transition-all duration-200 hover:shadow-md hover:-translate-x-1 group">
                <svg class="w-5 h-5 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span>Kembali ke Manajemen Izin</span>
            </a>
        </div>

        <!-- Main Card -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
            <!-- Header Card dengan Gradient -->
            <div class="bg-gradient-to-r from-indigo-600 to-blue-600 px-8 py-8 relative overflow-hidden">
                <!-- Decorative Background Pattern -->
                <div class="absolute inset-0 opacity-10">
                    <div class="absolute top-0 left-0 w-40 h-40 bg-white rounded-full -translate-x-20 -translate-y-20"></div>
                    <div class="absolute bottom-0 right-0 w-64 h-64 bg-white rounded-full translate-x-32 translate-y-32"></div>
                </div>

                <div class="relative flex items-start justify-between">
                    <div class="flex items-start gap-4">
                        <!-- Avatar Circle -->
                        <div class="flex-shrink-0 h-16 w-16 bg-white bg-opacity-20 backdrop-blur-sm rounded-2xl flex items-center justify-center text-white font-bold text-2xl shadow-lg border-2 border-white border-opacity-30">
                            {{ substr($permit->student->name ?? 'S', 0, 1) }}
                        </div>
                        <div>
                            <h2 class="text-3xl font-bold text-white mb-2">Detail Pengajuan Izin</h2>
                            <div class="flex items-center gap-2 text-indigo-100">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-sm">Diajukan oleh: <span class="font-semibold text-white">{{ $permit->student->name ?? 'Siswa Dihapus' }}</span></span>
                            </div>
                        </div>
                    </div>

                    <!-- Status Badge -->
                    <div>
                        @if($permit->status == 'disetujui')
                            <span class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-bold bg-white text-green-600 shadow-lg border-2 border-green-100">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                Disetujui
                            </span>
                        @elseif($permit->status == 'ditolak')
                            <span class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-bold bg-white text-red-600 shadow-lg border-2 border-red-100">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                                Ditolak
                            </span>
                        @else
                            <span class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-bold bg-white text-yellow-600 shadow-lg border-2 border-yellow-100">
                                <svg class="w-4 h-4 mr-2 animate-pulse" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                </svg>
                                Menunggu
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Detail Konten -->
            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Card Info: Nama Siswa -->
                    <div class="bg-gradient-to-br from-purple-50 to-indigo-50 rounded-xl p-5 border border-purple-100 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-purple-500 to-indigo-500 rounded-lg flex items-center justify-center shadow-md">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <dt class="text-xs font-semibold text-purple-600 uppercase tracking-wider mb-1">Nama Siswa</dt>
                                <dd class="text-gray-900 font-bold text-lg">{{ $permit->student->name ?? '-' }}</dd>
                            </div>
                        </div>
                    </div>

                    <!-- Card Info: Kelas -->
                    <div class="bg-gradient-to-br from-blue-50 to-cyan-50 rounded-xl p-5 border border-blue-100 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-lg flex items-center justify-center shadow-md">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <dt class="text-xs font-semibold text-blue-600 uppercase tracking-wider mb-1">Kelas</dt>
                                <dd class="text-gray-900 font-bold text-lg">{{ $permit->student->class ?? '-' }}</dd>
                            </div>
                        </div>
                    </div>

                    <!-- Card Info: Jenis Izin -->
                    <div class="bg-gradient-to-br from-emerald-50 to-teal-50 rounded-xl p-5 border border-emerald-100 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-emerald-500 to-teal-500 rounded-lg flex items-center justify-center shadow-md">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M17.707 9.293a1 1 0 010 1.414l-7 7a1 1 0 01-1.414 0l-7-7A.997.997 0 012 10V5a3 3 0 013-3h5c.256 0 .512.098.707.293l7 7zM5 6a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <dt class="text-xs font-semibold text-emerald-600 uppercase tracking-wider mb-1">Jenis Izin</dt>
                                <dd class="text-gray-900 font-bold text-lg">{{ ucfirst($permit->permit_type) }}</dd>
                            </div>
                        </div>
                    </div>

                    <!-- Card Info: Tanggal Izin -->
                    <div class="bg-gradient-to-br from-orange-50 to-amber-50 rounded-xl p-5 border border-orange-100 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-orange-500 to-amber-500 rounded-lg flex items-center justify-center shadow-md">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <dt class="text-xs font-semibold text-orange-600 uppercase tracking-wider mb-1">Tanggal Izin</dt>
                                <dd class="text-gray-900 font-bold text-lg">{{ \Carbon\Carbon::parse($permit->start_date)->isoFormat('D MMM YY') }} - {{ \Carbon\Carbon::parse($permit->end_date)->isoFormat('D MMM YY') }}</dd>
                            </div>
                        </div>
                    </div>

                    <!-- Card Info: Alasan (Full Width) -->
                    <div class="md:col-span-2 bg-gradient-to-br from-slate-50 to-gray-50 rounded-xl p-6 border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-slate-600 to-gray-600 rounded-lg flex items-center justify-center shadow-md">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <dt class="text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">Alasan</dt>
                                <dd class="text-gray-900 text-base leading-relaxed whitespace-pre-wrap">{{ $permit->reason }}</dd>
                            </div>
                        </div>
                    </div>

                    <!-- Card Info: Lampiran (Full Width) -->
                    <div class="md:col-span-2 bg-gradient-to-br from-rose-50 to-pink-50 rounded-xl p-6 border border-rose-100 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-rose-500 to-pink-500 rounded-lg flex items-center justify-center shadow-md">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <dt class="text-xs font-semibold text-rose-600 uppercase tracking-wider mb-2">Lampiran</dt>
                                <dd>
                                    @if($permit->attachment)
                                        <a href="{{ Storage::url($permit->attachment) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            Lihat/Unduh Lampiran
                                        </a>
                                    @else
                                        <div class="flex items-center gap-2 text-gray-500">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                            </svg>
                                            <span class="font-medium">Tidak ada lampiran</span>
                                        </div>
                                    @endif
                                </dd>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Formulir Aksi (Hanya jika status 'Menunggu') -->
            @if($permit->status == 'menunggu')
            <div class="bg-gradient-to-r from-gray-50 to-slate-50 px-8 py-6 border-t-2 border-gray-200">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-blue-500 rounded-lg flex items-center justify-center shadow-md">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Aksi Persetujuan</h3>
                        <p class="text-sm text-gray-600">Pilih aksi untuk pengajuan izin ini</p>
                    </div>
                </div>

                <form action="{{ route('admin.permits.updateStatus', $permit) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" id="status_input">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Button Setujui -->
                        <button type="submit" onclick="document.getElementById('status_input').value='disetujui'"
                                class="group relative flex items-center justify-center gap-3 bg-gradient-to-r from-green-500 to-emerald-500 hover:from-green-600 hover:to-emerald-600 text-white font-bold py-4 px-6 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-200 overflow-hidden">
                            <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-10 transition-opacity"></div>
                            <svg class="w-6 h-6 relative z-10" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="relative z-10 text-lg">Setujui Pengajuan</span>
                        </button>

                        <!-- Button Tolak -->
                        <button type="submit" onclick="document.getElementById('status_input').value='ditolak'"
                                class="group relative flex items-center justify-center gap-3 bg-gradient-to-r from-red-500 to-rose-500 hover:from-red-600 hover:to-rose-600 text-white font-bold py-4 px-6 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-200 overflow-hidden">
                            <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-10 transition-opacity"></div>
                            <svg class="w-6 h-6 relative z-10" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            <span class="relative z-10 text-lg">Tolak Pengajuan</span>
                        </button>
                    </div>
                </form>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
