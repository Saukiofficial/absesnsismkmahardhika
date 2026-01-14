@extends('layouts.admin')

@section('header', 'Dashboard')

@section('content')
    <!-- Welcome Section / Header -->
    <div class="mb-6">
        <div class="bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-700 rounded-2xl p-6 md:p-8 text-white shadow-xl relative overflow-hidden">
            <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold mb-2">Selamat Datang di Dashboard</h1>
                    <p class="text-blue-100 text-sm md:text-lg">Pantau kehadiran siswa secara real-time</p>
                    <div class="mt-4 flex flex-wrap items-center gap-2">
                        <div class="flex items-center text-xs md:text-sm bg-white/20 px-3 py-1.5 rounded-full backdrop-blur-sm">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                            </svg>
                            {{ date('d F Y') }}
                        </div>
                        <div class="flex items-center text-xs md:text-sm bg-white/20 px-3 py-1.5 rounded-full backdrop-blur-sm">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                            </svg>
                            <span id="current-time"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="absolute right-0 top-0 h-full w-1/3 bg-white/10 skew-x-12 transform origin-bottom-left pointer-events-none"></div>
        </div>
    </div>

    <!-- GRID STATISTIK CARDS (Responsif) -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3 md:gap-6 mb-6">

        <!-- CARD 1: Total Siswa -->
        <div class="bg-white p-4 md:p-6 rounded-xl md:rounded-2xl shadow-sm hover:shadow-md transition-all border border-gray-100 group">
            <div class="flex justify-between items-start">
                <div class="flex-1 min-w-0">
                    <p class="text-xs md:text-sm font-medium text-gray-500 truncate">Total Siswa</p>
                    <h3 class="text-2xl md:text-3xl font-bold text-gray-800 mt-1 md:mt-2">{{ $totalStudents }}</h3>
                </div>
                <div class="p-2 md:p-3 bg-blue-50 rounded-lg md:rounded-xl text-blue-600 group-hover:bg-blue-100 transition-colors ml-2">
                    <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-3 md:mt-4 w-full bg-gray-100 rounded-full h-1 md:h-1.5">
                <div class="bg-blue-500 h-1 md:h-1.5 rounded-full" style="width: 100%"></div>
            </div>
        </div>

        <!-- CARD 2: Hadir Hari Ini -->
        <div class="bg-white p-4 md:p-6 rounded-xl md:rounded-2xl shadow-sm hover:shadow-md transition-all border border-gray-100 group">
            <div class="flex justify-between items-start">
                <div class="flex-1 min-w-0">
                    <p class="text-xs md:text-sm font-medium text-gray-500 truncate">Hadir</p>
                    <h3 class="text-2xl md:text-3xl font-bold text-gray-800 mt-1 md:mt-2">{{ $presentCount }}</h3>
                </div>
                <div class="p-2 md:p-3 bg-green-50 rounded-lg md:rounded-xl text-green-600 group-hover:bg-green-100 transition-colors ml-2">
                    <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-3 md:mt-4 flex items-center text-xs text-green-600 font-medium">
                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span class="truncate">Tapin</span>
            </div>
        </div>

        <!-- CARD 3: Izin Hari Ini -->
        <div class="bg-white p-4 md:p-6 rounded-xl md:rounded-2xl shadow-sm hover:shadow-md transition-all border border-gray-100 group">
            <div class="flex justify-between items-start">
                <div class="flex-1 min-w-0">
                    <p class="text-xs md:text-sm font-medium text-gray-500 truncate">Izin</p>
                    <h3 class="text-2xl md:text-3xl font-bold text-gray-800 mt-1 md:mt-2">{{ $izinHariIni }}</h3>
                </div>
                <div class="p-2 md:p-3 bg-orange-50 rounded-lg md:rounded-xl text-orange-600 group-hover:bg-orange-100 transition-colors ml-2">
                    <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-3 md:mt-4 flex items-center text-xs text-orange-600 font-medium">
                <svg class="w-3 h-3 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="truncate">Sakit & Izin</span>
            </div>
        </div>

        <!-- CARD 4: Terlambat -->
        <div class="bg-white p-4 md:p-6 rounded-xl md:rounded-2xl shadow-sm hover:shadow-md transition-all border border-gray-100 group">
            <div class="flex justify-between items-start">
                <div class="flex-1 min-w-0">
                    <p class="text-xs md:text-sm font-medium text-gray-500 truncate">Terlambat</p>
                    <h3 class="text-2xl md:text-3xl font-bold text-gray-800 mt-1 md:mt-2">{{ $lateCount }}</h3>
                </div>
                <div class="p-2 md:p-3 bg-yellow-50 rounded-lg md:rounded-xl text-yellow-600 group-hover:bg-yellow-100 transition-colors ml-2">
                    <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-3 md:mt-4 flex items-center text-xs text-yellow-600 font-medium">
                <svg class="w-3 h-3 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.664-.833-2.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"></path></svg>
                <span class="truncate">Dipantau</span>
            </div>
        </div>

        <!-- CARD 5: Siswa Alpa -->
        <div class="bg-white p-4 md:p-6 rounded-xl md:rounded-2xl shadow-sm hover:shadow-md transition-all border border-gray-100 group col-span-2 md:col-span-1">
            <div class="flex justify-between items-start">
                <div class="flex-1 min-w-0">
                    <p class="text-xs md:text-sm font-medium text-gray-500 truncate">Alpa</p>
                    <h3 class="text-2xl md:text-3xl font-bold text-red-600 mt-1 md:mt-2">{{ $absentCount }}</h3>
                </div>
                <div class="p-2 md:p-3 bg-red-50 rounded-lg md:rounded-xl text-red-600 group-hover:bg-red-100 transition-colors ml-2">
                    <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-3 md:mt-4 flex items-center text-xs text-red-600 font-medium">
                <svg class="w-3 h-3 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="truncate">Tanpa Ket</span>
            </div>
        </div>
    </div>

    <!-- LAYOUT SPLIT: Tabel Aktivitas & Tabel Permintaan Izin -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6 mb-6">

        <!-- KOLOM KIRI: Aktivitas Absensi (Max 5 data) -->
        <div class="bg-white rounded-xl md:rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-4 md:px-6 py-3 md:py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-base md:text-lg font-bold text-gray-800">Aktivitas Tapin</h3>
                <span class="inline-flex items-center px-2 md:px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 animate-pulse">
                    Live
                </span>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 md:px-6 py-2 md:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Siswa</th>
                            <th class="px-4 md:px-6 py-2 md:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-4 md:px-6 py-2 md:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($latestAttendances->take(5) as $attendance)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 md:px-6 py-3 md:py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8 bg-gradient-to-br from-indigo-400 to-indigo-600 rounded-full flex items-center justify-center text-white font-bold text-xs shadow-sm">
                                            {{ substr($attendance->user->name ?? 'U', 0, 1) }}
                                        </div>
                                        <div class="ml-3 min-w-0">
                                            <div class="text-sm font-medium text-gray-900 truncate">{{ Str::limit($attendance->user->name ?? 'Unknown', 12) }}</div>
                                            <div class="text-xs text-gray-500 truncate">{{ $attendance->user->class ?? '-' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 md:px-6 py-3 md:py-4 whitespace-nowrap">
                                    @if($attendance->status == 'in')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Masuk</span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Pulang</span>
                                    @endif
                                </td>
                                <td class="px-4 md:px-6 py-3 md:py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $attendance->recorded_at->format('H:i') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 md:px-6 py-8 text-center">
                                    <div class="flex flex-col items-center text-gray-400">
                                        <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        <p class="text-sm">Belum ada aktivitas hari ini</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($latestAttendances->count() > 5)
                <div class="bg-gray-50 px-4 py-3 text-center border-t border-gray-100">
                    <a href="{{ route('admin.attendances.index') }}" class="text-xs md:text-sm font-medium text-indigo-600 hover:text-indigo-800 inline-flex items-center transition-colors">
                        Lihat Semua ({{ $latestAttendances->count() }})
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>
            @endif
        </div>

        <!-- KOLOM KANAN: Permintaan Izin Baru (Max 5 data) -->
        <div class="bg-white rounded-xl md:rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-orange-50 to-amber-50 px-4 md:px-6 py-3 md:py-4 border-b border-orange-200 flex justify-between items-center">
                <h3 class="text-base md:text-lg font-bold text-orange-800">Permintaan Izin</h3>
                <span class="flex items-center justify-center w-6 h-6 bg-orange-500 text-white rounded-full text-xs font-bold shadow-sm">
                    {{ $pendingPermissions->count() }}
                </span>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 md:px-6 py-2 md:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Siswa</th>
                            <th class="px-4 md:px-6 py-2 md:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ket</th>
                            <th class="px-4 md:px-6 py-2 md:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($pendingPermissions->take(5) as $permit)
                            <tr class="hover:bg-orange-50/30 transition-colors">
                                <td class="px-4 md:px-6 py-3 md:py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8 bg-gradient-to-br from-orange-400 to-orange-600 rounded-full flex items-center justify-center text-white font-bold text-xs shadow-sm">
                                            {{ substr($permit->student->name ?? 'S', 0, 1) }}
                                        </div>
                                        <div class="ml-3 min-w-0">
                                            <div class="text-sm font-medium text-gray-900 truncate">{{ Str::limit($permit->student->name ?? 'Unknown', 12) }}</div>
                                            <div class="text-xs text-gray-500 truncate">{{ $permit->student->class ?? '-' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 md:px-6 py-3 md:py-4">
                                    <div class="text-xs font-bold uppercase {{ $permit->type == 'sakit' ? 'text-red-600' : 'text-blue-600' }}">
                                        {{ $permit->type }}
                                    </div>
                                    <div class="text-xs text-gray-500 truncate max-w-[80px]" title="{{ $permit->reason }}">
                                        {{ Str::limit($permit->reason, 15) }}
                                    </div>
                                </td>
                                <td class="px-4 md:px-6 py-3 md:py-4 whitespace-nowrap text-xs md:text-sm text-gray-500">
                                    {{ $permit->start_date->format('d M') }}
                                    @if($permit->start_date != $permit->end_date)
                                        <span class="text-xs text-gray-400 block">→ {{ $permit->end_date->format('d M') }}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 md:px-6 py-8 text-center">
                                    <div class="flex flex-col items-center text-gray-400">
                                        <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        <p class="text-sm">Tidak ada permintaan pending</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($pendingPermissions->count() > 5)
                <div class="bg-gray-50 px-4 py-3 text-center border-t border-gray-100">
                    <a href="{{ route('admin.permits.index') }}" class="text-xs md:text-sm font-medium text-indigo-600 hover:text-indigo-800 inline-flex items-center transition-colors">
                        Lihat Semua ({{ $pendingPermissions->count() }})
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>
            @endif
        </div>

    </div>

    <!-- Grid Aktivitas Izin & Alpa (Max 5 data each) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6">

        <!-- KOLOM KIRI: Aktivitas Izin Hari Ini -->
        <div class="bg-white rounded-xl md:rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-50 to-cyan-50 px-4 md:px-6 py-3 md:py-4 border-b border-blue-200 flex justify-between items-center">
                <h3 class="text-base md:text-lg font-bold text-blue-800">Izin Hari Ini</h3>
                <span class="flex items-center justify-center w-6 h-6 bg-blue-500 text-white rounded-full text-xs font-bold shadow-sm">
                    {{ $approvedPermitsToday->count() }}
                </span>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 md:px-6 py-2 md:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Siswa</th>
                            <th class="px-4 md:px-6 py-2 md:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keterangan</th>
                            <th class="px-4 md:px-6 py-2 md:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($approvedPermitsToday->take(5) as $permit)
                            <tr class="hover:bg-blue-50/30 transition-colors">
                                <td class="px-4 md:px-6 py-3 md:py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white font-bold text-xs shadow-sm">
                                            {{ substr($permit->student->name ?? 'S', 0, 1) }}
                                        </div>
                                        <div class="ml-3 min-w-0">
                                            <div class="text-sm font-medium text-gray-900 truncate">{{ Str::limit($permit->student->name ?? 'Unknown', 12) }}</div>
                                            <div class="text-xs text-gray-500 truncate">{{ $permit->student->class ?? '-' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 md:px-6 py-3 md:py-4">
                                    <div class="text-xs font-bold uppercase {{ $permit->type == 'sakit' ? 'text-red-600' : 'text-blue-600' }}">
                                        {{ $permit->type }}
                                    </div>
                                    <div class="text-xs text-gray-500 truncate max-w-[80px]" title="{{ $permit->reason }}">
                                        {{ Str::limit($permit->reason, 15) }}
                                    </div>
                                </td>
                                <td class="px-4 md:px-6 py-3 md:py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Disetujui
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 md:px-6 py-8 text-center">
                                    <div class="flex flex-col items-center text-gray-400">
                                        <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        <p class="text-sm">Tidak ada siswa yang izin hari ini</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($approvedPermitsToday->count() > 5)
                <div class="bg-gray-50 px-4 py-3 text-center border-t border-gray-100">
                    <a href="{{ route('admin.permits.index') }}" class="text-xs md:text-sm font-medium text-indigo-600 hover:text-indigo-800 inline-flex items-center transition-colors">
                        Lihat Semua ({{ $approvedPermitsToday->count() }})
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>
            @endif
        </div>

        <!-- KOLOM KANAN: Siswa Alpa Hari Ini -->
        <div class="bg-white rounded-xl md:rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-red-50 to-rose-50 px-4 md:px-6 py-3 md:py-4 border-b border-red-200 flex justify-between items-center">
                <h3 class="text-base md:text-lg font-bold text-red-800">Siswa Alpa Hari Ini</h3>
                <span class="flex items-center justify-center w-6 h-6 bg-red-500 text-white rounded-full text-xs font-bold shadow-sm">
                    {{ $alpaStudentsToday->count() }}
                </span>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 md:px-6 py-2 md:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Siswa</th>
                            <th class="px-4 md:px-6 py-2 md:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                            <th class="px-4 md:px-6 py-2 md:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($alpaStudentsToday->take(5) as $student)
                            <tr class="hover:bg-red-50/30 transition-colors">
                                <td class="px-4 md:px-6 py-3 md:py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8 bg-gradient-to-br from-red-400 to-red-600 rounded-full flex items-center justify-center text-white font-bold text-xs shadow-sm">
                                            {{ substr($student->name ?? 'S', 0, 1) }}
                                        </div>
                                        <div class="ml-3 min-w-0">
                                            <div class="text-sm font-medium text-gray-900 truncate">{{ Str::limit($student->name ?? 'Unknown', 12) }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 md:px-6 py-3 md:py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $student->class ?? '-' }}
                                </td>
                                <td class="px-4 md:px-6 py-3 md:py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        Tanpa Keterangan
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 md:px-6 py-8 text-center">
                                    <div class="flex flex-col items-center text-gray-400">
                                        <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        <p class="text-sm">Tidak ada siswa alpa hari ini</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($alpaStudentsToday->count() > 5)
                <div class="bg-gray-50 px-4 py-3 text-center border-t border-gray-100">
                    <a href="{{ route('admin.students.index') }}" class="text-xs md:text-sm font-medium text-indigo-600 hover:text-indigo-800 inline-flex items-center transition-colors">
                        Lihat Semua ({{ $alpaStudentsToday->count() }})
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>
            @endif
        </div>

    </div>

    <!-- Script Jam Realtime -->
    <script>
        function updateClock() {
            const now = new Date();
            document.getElementById('current-time').textContent = now.toLocaleTimeString('id-ID', {
                hour: '2-digit', minute: '2-digit', second: '2-digit'
            });
        }
        setInterval(updateClock, 1000);
        updateClock();
    </script>
@endsection
