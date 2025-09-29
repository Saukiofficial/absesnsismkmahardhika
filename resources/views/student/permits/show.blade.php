@extends('student.layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 py-8 px-4">
    <div class="max-w-4xl mx-auto">
        <!-- Breadcrumb -->
        <nav class="mb-6">
            <div class="flex items-center space-x-2 text-sm">
                <a href="{{ route('student.permits.index') }}" class="text-blue-600 hover:text-blue-800 font-medium transition-colors">
                    Riwayat Izin
                </a>
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <span class="text-gray-600">Detail Pengajuan</span>
            </div>
        </nav>

        <!-- Main Card -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <!-- Header Section -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-8 py-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
                    <div class="flex items-center space-x-4">
                        <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-white">Detail Pengajuan Izin</h1>
                            <div class="flex items-center space-x-2 text-blue-100 mt-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-sm">Diajukan pada {{ $permit->created_at->isoFormat('D MMMM YYYY, HH:mm') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Status Badge -->
                    <div class="flex items-center">
                        @if($permit->status == 'disetujui')
                            <div class="bg-green-100 border border-green-200 rounded-full px-6 py-3 flex items-center space-x-2">
                                <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                                <span class="text-green-800 font-semibold">Disetujui</span>
                                <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        @elseif($permit->status == 'ditolak')
                            <div class="bg-red-100 border border-red-200 rounded-full px-6 py-3 flex items-center space-x-2">
                                <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                                <span class="text-red-800 font-semibold">Ditolak</span>
                                <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        @else
                            <div class="bg-yellow-100 border border-yellow-200 rounded-full px-6 py-3 flex items-center space-x-2">
                                <div class="w-3 h-3 bg-yellow-500 rounded-full animate-pulse"></div>
                                <span class="text-yellow-800 font-semibold">Menunggu</span>
                                <svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Content Section -->
            <div class="p-8">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Main Info -->
                    <div class="lg:col-span-2 space-y-8">
                        <!-- Jenis Izin -->
                        <div class="bg-gray-50 rounded-xl p-6">
                            <div class="flex items-center space-x-3 mb-4">
                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                    @if($permit->permit_type == 'sakit')
                                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                        </svg>
                                    @else
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                        </svg>
                                    @endif
                                </div>
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Jenis Izin</h3>
                                    <p class="text-lg font-bold text-gray-900 mt-1">{{ ucfirst($permit->permit_type) }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Periode Izin -->
                        <div class="bg-gray-50 rounded-xl p-6">
                            <div class="flex items-center space-x-3 mb-4">
                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Periode Izin</h3>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="bg-white rounded-lg p-4 border border-gray-200">
                                    <div class="text-xs text-gray-500 font-medium uppercase tracking-wide mb-2">Tanggal Mulai</div>
                                    <div class="text-lg font-semibold text-gray-900">
                                        {{ \Carbon\Carbon::parse($permit->start_date)->isoFormat('ddd') }}
                                    </div>
                                    <div class="text-sm text-gray-600">
                                        {{ \Carbon\Carbon::parse($permit->start_date)->isoFormat('D MMMM YYYY') }}
                                    </div>
                                </div>

                                <div class="bg-white rounded-lg p-4 border border-gray-200">
                                    <div class="text-xs text-gray-500 font-medium uppercase tracking-wide mb-2">Tanggal Selesai</div>
                                    <div class="text-lg font-semibold text-gray-900">
                                        {{ \Carbon\Carbon::parse($permit->end_date)->isoFormat('ddd') }}
                                    </div>
                                    <div class="text-sm text-gray-600">
                                        {{ \Carbon\Carbon::parse($permit->end_date)->isoFormat('D MMMM YYYY') }}
                                    </div>
                                </div>
                            </div>

                            <!-- Duration -->
                            <div class="mt-4 bg-blue-50 rounded-lg p-4 border border-blue-200">
                                <div class="flex items-center justify-center space-x-2">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="text-blue-800 font-medium">
                                        Durasi: {{ \Carbon\Carbon::parse($permit->start_date)->diffInDays(\Carbon\Carbon::parse($permit->end_date)) + 1 }} hari
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Alasan -->
                        <div class="bg-gray-50 rounded-xl p-6">
                            <div class="flex items-start space-x-3 mb-4">
                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-3">Alasan</h3>
                                    <div class="bg-white rounded-lg p-4 border border-gray-200">
                                        <p class="text-gray-900 leading-relaxed whitespace-pre-wrap">{{ $permit->reason }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="space-y-6">
                        <!-- Lampiran -->
                        <div class="bg-gray-50 rounded-xl p-6">
                            <div class="flex items-center space-x-3 mb-4">
                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                    </svg>
                                </div>
                                <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Lampiran</h3>
                            </div>

                            @if($permit->attachment)
                                <div class="bg-white rounded-lg p-4 border border-gray-200">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-gray-900">Dokumen tersedia</p>
                                            <p class="text-xs text-gray-500">Klik untuk melihat/unduh</p>
                                        </div>
                                    </div>
                                    <a href="{{ Storage::url($permit->attachment) }}" target="_blank"
                                       class="mt-3 w-full bg-blue-600 hover:bg-blue-700 text-white text-center py-2 px-4 rounded-lg text-sm font-medium transition-colors duration-200 block">
                                        <div class="flex items-center justify-center space-x-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            <span>Lihat Lampiran</span>
                                        </div>
                                    </a>
                                </div>
                            @else
                                <div class="bg-white rounded-lg p-4 border border-gray-200">
                                    <div class="text-center py-4">
                                        <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        </div>
                                        <p class="text-gray-500 text-sm">Tidak ada lampiran</p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Info Timeline -->
                        <div class="bg-white rounded-xl p-6 border border-gray-200">
                            <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-4">Timeline</h3>
                            <div class="space-y-3">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.293l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13a1 1 0 102 0V9.414l1.293 1.293a1 1 0 001.414-1.414z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Pengajuan Dibuat</p>
                                        <p class="text-xs text-gray-500">{{ $permit->created_at->isoFormat('D MMM YYYY, HH:mm') }}</p>
                                    </div>
                                </div>

                                @if($permit->status !== 'menunggu')
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 {{ $permit->status == 'disetujui' ? 'bg-green-100' : 'bg-red-100' }} rounded-full flex items-center justify-center">
                                        @if($permit->status == 'disetujui')
                                            <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                        @else
                                            <svg class="w-4 h-4 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                            </svg>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ ucfirst($permit->status) }}</p>
                                        <p class="text-xs text-gray-500">{{ $permit->updated_at->isoFormat('D MMM YYYY, HH:mm') }}</p>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Actions -->
            <div class="bg-gray-50 px-8 py-6 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <a href="{{ route('student.permits.index') }}"
                       class="inline-flex items-center space-x-2 text-gray-600 hover:text-gray-900 font-medium transition-colors duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        <span>Kembali ke Riwayat</span>
                    </a>

                    <div class="flex items-center space-x-4">
                        <div class="text-sm text-gray-500">
                            ID: #{{ str_pad($permit->id, 6, '0', STR_PAD_LEFT) }}
                        </div>
                        @if($permit->status == 'menunggu')
                            <div class="flex items-center space-x-2 text-amber-600 bg-amber-50 px-3 py-1 rounded-full text-sm">
                                <div class="w-2 h-2 bg-amber-400 rounded-full animate-pulse"></div>
                                <span>Sedang diproses</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
