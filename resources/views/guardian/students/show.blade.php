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
                        <div class="flex items-center space-x-6">
                            <!-- Student Photo -->
                            <div class="relative">
                                <div class="w-24 h-24 rounded-2xl overflow-hidden shadow-xl border-4 border-white/20 backdrop-blur-sm">
                                    <img class="w-full h-full object-cover"
                                         src="{{ $student->photo ? Storage::url($student->photo) : 'https://ui-avatars.com/api/?name=' . urlencode($student->name) . '&background=0ea5e9&color=fff&size=200' }}"
                                         alt="Foto {{ $student->name }}">
                                </div>
                                <div class="absolute -bottom-2 -right-2 w-6 h-6 bg-green-500 rounded-full border-3 border-white shadow-lg"></div>
                            </div>

                            <!-- Student Info -->
                            <div class="flex-1">
                                <h1 class="text-3xl font-bold text-white mb-2">{{ $student->name }}</h1>
                                <div class="flex flex-wrap items-center gap-4 text-teal-100">
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                        <span class="font-medium">{{ $student->class }}</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V4a2 2 0 114 0v2m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                                        </svg>
                                        <span class="font-medium">NIS: {{ $student->nis }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Decorative elements -->
                    <div class="absolute top-4 right-8 w-20 h-20 bg-white/10 rounded-full"></div>
                    <div class="absolute bottom-4 right-16 w-12 h-12 bg-white/5 rounded-full"></div>
                </div>
            </div>
        </div>

        <!-- Full Width Content -->
        <div class="space-y-8">
            <!-- Attendance History - Full Width -->
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200/60 overflow-hidden">
                <div class="bg-gradient-to-r from-slate-50 to-slate-100 px-8 py-6 border-b border-slate-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-slate-800">Riwayat Absensi</h3>
                                <p class="text-slate-600">Data kehadiran siswa di sekolah</p>
                            </div>
                        </div>

                        <!-- Summary Stats -->
                        <div class="hidden lg:flex items-center space-x-6">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-green-600">{{ isset($attendanceStats['present']) ? $attendanceStats['present'] : '--' }}</div>
                                <div class="text-sm text-slate-600">Hadir</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-yellow-600">{{ isset($attendanceStats['late']) ? $attendanceStats['late'] : '--' }}</div>
                                <div class="text-sm text-slate-600">Terlambat</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-red-600">{{ isset($attendanceStats['absent']) ? $attendanceStats['absent'] : '--' }}</div>
                                <div class="text-sm text-slate-600">Tidak Hadir</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="p-8">
                    @include('guardian.students._attendance_table')
                </div>
            </div>

            <!-- Permit History - Full Width -->
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200/60 overflow-hidden">
                <div class="bg-gradient-to-r from-slate-50 to-slate-100 px-8 py-6 border-b border-slate-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-slate-800">Riwayat Pengajuan Izin</h3>
                                <p class="text-slate-600">Data pengajuan izin dan permohonan</p>
                            </div>
                        </div>

                        <!-- Permit Stats -->
                        <div class="hidden lg:flex items-center space-x-6">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-green-600">{{ isset($permitStats['approved']) ? $permitStats['approved'] : '--' }}</div>
                                <div class="text-sm text-slate-600">Disetujui</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-yellow-600">{{ isset($permitStats['pending']) ? $permitStats['pending'] : '--' }}</div>
                                <div class="text-sm text-slate-600">Menunggu</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-red-600">{{ isset($permitStats['rejected']) ? $permitStats['rejected'] : '--' }}</div>
                                <div class="text-sm text-slate-600">Ditolak</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="p-8">
                    @include('guardian.students._permit_table')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
