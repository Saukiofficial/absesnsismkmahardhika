@extends('guardian.layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Back Navigation -->
        <div class="mb-6">
            <a href="{{ route('guardian.dashboard') }}" class="inline-flex items-center space-x-2 text-slate-600 hover:text-teal-700 transition-colors duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span class="font-medium">Kembali ke Dashboard</span>
            </a>
        </div>

        <!-- Student Profile Header -->
        <div class="mb-8">
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200/60 overflow-hidden">
                <div class="relative bg-gradient-to-r from-teal-600 via-teal-700 to-cyan-700 px-8 py-8">
                    <div class="absolute inset-0 bg-black/10"></div>
                    <div class="relative">
                        <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                            <div class="flex flex-col md:flex-row items-center gap-6">
                                <!-- Avatar -->
                                <div class="relative">
                                    <div class="w-24 h-24 rounded-full bg-white p-1 shadow-xl">
                                        @if($student->photo)
                                            <img src="{{ asset('storage/' . $student->photo) }}" alt="{{ $student->name }}" class="w-full h-full rounded-full object-cover">
                                        @else
                                            <div class="w-full h-full rounded-full bg-slate-100 flex items-center justify-center text-teal-700 text-3xl font-bold">
                                                {{ substr($student->name, 0, 1) }}
                                            </div>
                                        @endif
                                    </div>
                                    @if(isset($statusHariIni))
                                        <div class="absolute -bottom-2 -right-2 px-3 py-1 rounded-full text-xs font-bold shadow-md border-2 border-white
                                            {{ $statusHariIni == 'Hadir' ? 'bg-emerald-500 text-white' :
                                               ($statusHariIni == 'Terlambat' ? 'bg-yellow-500 text-white' :
                                               ($statusHariIni == 'Izin' ? 'bg-blue-500 text-white' :
                                               ($statusHariIni == 'Sakit' ? 'bg-indigo-500 text-white' :
                                               ($statusHariIni == 'Alpa' ? 'bg-rose-500 text-white' : 'bg-slate-500 text-white')))) }}">
                                            {{ $statusHariIni }} {{ $detailHariIni ? "($detailHariIni)" : '' }}
                                        </div>
                                    @endif
                                </div>

                                <!-- Info -->
                                <div class="text-center md:text-left text-white">
                                    <h1 class="text-3xl font-bold mb-2">{{ $student->name }}</h1>
                                    <div class="flex flex-wrap justify-center md:justify-start gap-3 text-teal-100 text-sm">
                                        <span class="bg-white/20 px-3 py-1 rounded-full backdrop-blur-sm">
                                            NIS: {{ $student->nis }}
                                        </span>
                                        <span class="bg-white/20 px-3 py-1 rounded-full backdrop-blur-sm">
                                            {{ $student->class }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stats Grid (Attendance) -->
                <div class="grid grid-cols-3 divide-x divide-slate-100 border-b border-slate-100 bg-white">
                    <div class="p-6 text-center group hover:bg-slate-50 transition-colors">
                        <div class="text-3xl font-bold text-emerald-600 mb-1 group-hover:scale-110 transition-transform">{{ $attendanceStats['hadir'] }}</div>
                        <div class="text-sm font-medium text-slate-500 uppercase tracking-wider">Hadir</div>
                    </div>
                    <div class="p-6 text-center group hover:bg-slate-50 transition-colors">
                        <div class="text-3xl font-bold text-blue-600 mb-1 group-hover:scale-110 transition-transform">{{ $attendanceStats['izin'] }}</div>
                        <div class="text-sm font-medium text-slate-500 uppercase tracking-wider">Izin/Sakit</div>
                    </div>
                    <div class="p-6 text-center group hover:bg-slate-50 transition-colors">
                        <div class="text-3xl font-bold text-rose-600 mb-1 group-hover:scale-110 transition-transform">{{ $attendanceStats['alpa'] }}</div>
                        <div class="text-sm font-medium text-slate-500 uppercase tracking-wider">Alpa</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Grid -->
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">

            <!-- Attendance History -->
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200/60 overflow-hidden flex flex-col h-full">
                <div class="p-6 border-b border-slate-100 bg-slate-50/50">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="p-2 bg-white rounded-lg shadow-sm border border-slate-100">
                                <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-slate-800">Riwayat Absensi</h3>
                                <p class="text-sm text-slate-500">Log kehadiran terbaru</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="p-0 flex-grow">
                    @include('guardian.students._attendance_table')
                </div>
            </div>

            <!-- Permission History -->
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200/60 overflow-hidden flex flex-col h-full">
                <div class="p-6 border-b border-slate-100 bg-slate-50/50">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-white rounded-lg shadow-sm border border-slate-100">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-slate-800">Riwayat Izin</h3>
                            <p class="text-sm text-slate-500">Daftar pengajuan izin siswa</p>
                        </div>
                    </div>
                </div>
                <div class="p-0 flex-grow">
                    @include('guardian.students._permit_table')
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
