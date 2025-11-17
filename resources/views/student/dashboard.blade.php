@extends('student.layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Header Section -->
        <div class="mb-8">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
                <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-8 py-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-white mb-1">Selamat Datang, {{ $student->name }}</h1>
                            <p class="text-blue-100 opacity-90">Pantau aktivitas absensi dan izin Anda dengan mudah</p>
                        </div>
                        <div class="hidden md:block">
                            <div class="bg-white/10 backdrop-blur-sm rounded-xl px-4 py-2">
                                <div class="text-white text-sm font-medium">{{ \Carbon\Carbon::now()->format('d F Y') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- PERBAIKAN: Menghitung total hari untuk persentase progress bar -->
        @php
            // Total rekap adalah jumlah dari ketiganya, pastikan minimal 1 untuk menghindari pembagian nol
            $totalRekap = max($attendanceSummary['hadir'] + $attendanceSummary['izin'] + $attendanceSummary['alpa'], 1);
            $hadirPercent = ($attendanceSummary['hadir'] / $totalRekap) * 100;
            $izinPercent = ($attendanceSummary['izin'] / $totalRekap) * 100;
            $alpaPercent = ($attendanceSummary['alpa'] / $totalRekap) * 100;
        @endphp

        <!-- Statistics Cards -->
        <!-- PERBAIKAN: Mengubah grid dari md:grid-cols-2 menjadi md:grid-cols-3 -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

            <!-- Attendance Card (Hadir) -->
            <div class="group hover:scale-[1.02] transition-transform duration-200">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 p-6 h-full">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-4">
                            <div class="bg-emerald-50 p-3 rounded-xl border border-emerald-100">
                                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-slate-600 uppercase tracking-wider">Kehadiran</h3>
                                <p class="text-xs text-slate-500 mt-1">Bulan ini</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-3xl font-bold text-slate-800 mb-1">{{ $attendanceSummary['hadir'] }}</div>
                            <div class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                Hadir
                            </div>
                        </div>
                    </div>
                    <div class="h-2 bg-slate-100 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-emerald-400 to-emerald-500 rounded-full" style="width: {{ $hadirPercent }}%"></div>
                    </div>
                </div>
            </div>

            <!-- Permission Card (Izin) -->
            <div class="group hover:scale-[1.02] transition-transform duration-200">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 p-6 h-full">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-4">
                            <div class="bg-amber-50 p-3 rounded-xl border border-amber-100">
                                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-slate-600 uppercase tracking-wider">Izin</h3>
                                <p class="text-xs text-slate-500 mt-1">Disetujui</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-3xl font-bold text-slate-800 mb-1">{{ $attendanceSummary['izin'] }}</div>
                            <div class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-amber-50 text-amber-700 border border-amber-200">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                </svg>
                                Approved
                            </div>
                        </div>
                    </div>
                    <div class="h-2 bg-slate-100 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-amber-400 to-amber-500 rounded-full" style="width: {{ $izinPercent }}%"></div>
                    </div>
                </div>
            </div>

            <!-- CARD BARU: Alpha (Alpa) -->
            <div class="group hover:scale-[1.02] transition-transform duration-200">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 p-6 h-full">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-4">
                            <div class="bg-red-50 p-3 rounded-xl border border-red-100">
                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-slate-600 uppercase tracking-wider">Alpa</h3>
                                <p class="text-xs text-slate-500 mt-1">Tanpa Keterangan</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-3xl font-bold text-slate-800 mb-1">{{ $attendanceSummary['alpa'] }}</div>
                            <div class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-50 text-red-700 border border-red-200">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                Absent
                            </div>
                        </div>
                    </div>
                    <div class="h-2 bg-slate-100 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-red-400 to-red-500 rounded-full" style="width: {{ $alpaPercent }}%"></div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Recent Permits Section -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
            <div class="bg-gradient-to-r from-slate-50 to-slate-100 px-8 py-6 border-b border-slate-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-bold text-slate-800">Riwayat Pengajuan Izin</h3>
                        <p class="text-slate-600 text-sm mt-1">5 pengajuan terakhir Anda</p>
                    </div>
                    <div class="hidden sm:block">
                        <div class="bg-white px-3 py-2 rounded-lg border border-slate-200 shadow-sm">
                            <div class="flex items-center space-x-2 text-sm text-slate-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2m-9 4v10a2 2 0 002 2h6a2 2 0 002-2V8M7 8h10M9 12h6m-6 4h6"></path>
                                </svg>
                                <span>Terbaru</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="overflow-hidden">
                @forelse ($recentPermits as $index => $permit)
                    <div class="border-b border-slate-100 last:border-b-0 hover:bg-slate-50/50 transition-colors duration-150">
                        <div class="px-8 py-6">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0">
                                <div class="flex items-start space-x-4">
                                    <div class="flex-shrink-0">
                                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center text-white text-sm font-bold">
                                            {{ $index + 1 }}
                                        </div>
                                    </div>
                                    <div class="flex-grow min-w-0">
                                        <div class="flex items-center space-x-3 mb-2">
                                            <h4 class="text-lg font-semibold text-slate-800 capitalize">{{ $permit->permit_type }}</h4>
                                            <div class="text-sm text-slate-500 bg-slate-100 px-2 py-1 rounded-md">
                                                {{ \Carbon\Carbon::parse($permit->start_date)->format('d M Y') }}
                                            </div>
                                        </div>
                                        <p class="text-slate-600 text-sm leading-relaxed">{{ $permit->reason }}</p>
                                    </div>
                                </div>
                                <div class="flex-shrink-0">
                                    @if($permit->status == 'disetujui')
                                        <div class="inline-flex items-center px-3 py-2 rounded-lg text-sm font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                            Disetujui
                                        </div>
                                    @elseif($permit->status == 'ditolak')
                                        <div class="inline-flex items-center px-3 py-2 rounded-lg text-sm font-medium bg-red-50 text-red-700 border border-red-200">
                                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                            </svg>
                                            Ditolak
                                        </div>
                                    @else
                                        <div class="inline-flex items-center px-3 py-2 rounded-lg text-sm font-medium bg-amber-50 text-amber-700 border border-amber-200">
                                            <svg class="w-4 h-4 mr-2 animate-spin" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="m4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            Menunggu
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-8 py-12 text-center">
                        <div class="mx-auto w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-slate-600 mb-2">Belum Ada Pengajuan Izin</h3>
                        <p class="text-slate-500">Anda belum pernah mengajukan izin. Silakan ajukan izin jika diperlukan.</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</div>

<style>
/* Custom styles for enhanced visual appeal */
.group:hover .transition-transform {
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

/* Smooth scrollbar for mobile */
.overflow-x-auto::-webkit-scrollbar {
    height: 4px;
}

.overflow-x-auto::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 2px;
}

.overflow-x-auto::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 2px;
}

.overflow-x-auto::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

/* Animation for status indicators */
@keyframes pulse-gentle {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.7;
    }
}

.animate-spin {
    animation: spin 1s linear infinite;
}
</style>
@endsection
