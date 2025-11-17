@extends('guardian.layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200/60 overflow-hidden">
                <div class="relative bg-gradient-to-r from-teal-600 via-teal-700 to-cyan-700 px-8 py-12">
                    <div class="absolute inset-0 bg-black/10"></div>
                    <div class="relative">
                        <div class="flex items-center space-x-4">
                            <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-3xl font-bold text-white mb-2">Selamat Datang, {{ Auth::user()->name }}!</h1>
                                <p class="text-teal-100 text-lg leading-relaxed max-w-3xl">
                                    Portal orang tua untuk memantau kehadiran dan aktivitas akademik putra/putri Anda.
                                    Klik pada kartu siswa untuk melihat detail lengkap absensi dan riwayat izin.
                                </p>
                            </div>
                        </div>
                    </div>
                    <!-- Decorative elements -->
                    <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-white/10 rounded-full"></div>
                    <div class="absolute bottom-0 right-12 -mb-6 w-16 h-16 bg-white/5 rounded-full"></div>
                </div>
            </div>
        </div>

        <!-- Students Grid Section -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-slate-800 mb-1">Putra/Putri Anda</h2>
                    <p class="text-slate-600">Informasi siswa yang terdaftar di sekolah</p>
                </div>
                <div class="hidden sm:flex items-center space-x-2 text-sm text-slate-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>Klik kartu untuk detail lengkap</span>
                </div>
            </div>

            @forelse ($students as $student)
                <div class="mb-4">
                    <a href="{{ route('guardian.students.show', $student) }}"
                       class="group block bg-white rounded-2xl shadow-lg border border-slate-200/60 hover:shadow-xl hover:border-teal-300/40 transition-all duration-300 overflow-hidden">
                        <div class="p-6">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-6">
                                    <!-- Student Avatar -->
                                    <div class="relative">
                                        <div class="w-16 h-16 bg-gradient-to-br from-teal-500 to-cyan-600 rounded-2xl flex items-center justify-center shadow-lg">
                                            <span class="text-xl font-bold text-white">
                                                {{ strtoupper(substr($student->name, 0, 1)) }}
                                            </span>
                                        </div>
                                        <!-- PERBAIKAN: Ganti ikon 'aktif' statis dengan status dinamis -->
                                        @php
                                            $statusIndicatorColor = 'bg-slate-400'; // Default
                                            switch ($student->statusHariIni) {
                                                case 'Hadir': $statusIndicatorColor = 'bg-green-500'; break;
                                                case 'Terlambat': $statusIndicatorColor = 'bg-yellow-500'; break;
                                                case 'Izin': $statusIndicatorColor = 'bg-blue-500'; break;
                                                case 'Alpa': case 'Belum Absen': $statusIndicatorColor = 'bg-red-500'; break;
                                            }
                                        @endphp
                                        <div class="absolute -bottom-1 -right-1 w-5 h-5 {{ $statusIndicatorColor }} rounded-full border-2 border-white shadow-sm"></div>
                                    </div>

                                    <!-- Student Info -->
                                    <div class="space-y-1">
                                        <h3 class="text-xl font-semibold text-slate-800 group-hover:text-teal-700 transition-colors duration-200">
                                            {{ $student->name }}
                                        </h3>
                                        <div class="flex items-center space-x-4">
                                            <div class="flex items-center space-x-2">
                                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                                </svg>
                                                <span class="text-sm font-medium text-slate-600">{{ $student->class }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Indicators -->
                                <div class="flex items-center space-x-4">

                                    <!-- PERBAIKAN: Badge Status Real-time (Menggantikan 'Aktif') -->
                                    @php
                                        $statusColor = '';
                                        $statusIcon = '';
                                        $statusText = $student->statusHariIni;

                                        switch ($statusText) {
                                            case 'Hadir':
                                                $statusColor = 'bg-green-100 text-green-800';
                                                $statusIcon = '<div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>';
                                                break;
                                            case 'Terlambat':
                                                $statusColor = 'bg-yellow-100 text-yellow-800';
                                                $statusIcon = '<div class="w-2 h-2 bg-yellow-500 rounded-full"></div>';
                                                break;
                                            case 'Izin':
                                                $statusColor = 'bg-blue-100 text-blue-800';
                                                $statusIcon = '<div class="w-2 h-2 bg-blue-500 rounded-full"></div>';
                                                break;
                                            case 'Alpa':
                                            case 'Belum Absen':
                                                $statusColor = 'bg-red-100 text-red-800';
                                                $statusIcon = '<div class="w-2 h-2 bg-red-500 rounded-full"></div>';
                                                break;
                                            case 'Libur':
                                            default:
                                                $statusColor = 'bg-slate-100 text-slate-800';
                                                $statusIcon = '<div class="w-2 h-2 bg-slate-500 rounded-full"></div>';
                                                break;
                                        }
                                    @endphp

                                    <div class="hidden sm:flex items-center space-x-2 {{ $statusColor }} px-3 py-1 rounded-full">
                                        {!! $statusIcon !!}
                                        <span class="text-xs font-medium">{{ $statusText }}</span>
                                    </div>
                                    <!-- AKHIR PERUBAHAN -->

                                    <!-- Arrow -->
                                    <div class="w-10 h-10 bg-slate-50 rounded-full flex items-center justify-center group-hover:bg-teal-50 transition-colors duration-200">
                                        <svg class="w-5 h-5 text-slate-400 group-hover:text-teal-600 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Hover Effect Bar -->
                        <div class="h-1 bg-gradient-to-r from-teal-500 to-cyan-500 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"></div>
                    </a>
                </div>
            @empty
                <!-- Empty State -->
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200/60 overflow-hidden">
                    <div class="px-8 py-16 text-center">
                        <div class="max-w-md mx-auto">
                            <!-- Icon -->
                            <div class="w-20 h-20 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
                                <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-5A3.5 3.5 0 0011 2.5v0M11 2.5V9"></path>
                                </svg>
                            </div>

                            <!-- Title -->
                            <h3 class="text-xl font-semibold text-slate-800 mb-3">Belum Ada Data Siswa</h3>

                            <!-- Description -->
                            <p class="text-slate-600 mb-6 leading-relaxed">
                                Tidak ada data siswa yang terhubung dengan akun Anda saat ini.
                                Silakan hubungi administrasi sekolah untuk bantuan lebih lanjut.
                            </p>

                            <!-- Action Button -->
                            <div class="bg-slate-50 rounded-xl p-4 border border-slate-200/60">
                                <div class="flex items-center justify-center space-x-2 text-sm text-slate-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                    <span>Hubungi administrasi sekolah jika ada pertanyaan</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Quick Stats (Optional Enhancement) -->
        @if($students->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200/60 p-6">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-5A3.5 3.5 0 0011 2.5v0M11 2.5V9"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-slate-600">Total Siswa</p>
                        <p class="text-2xl font-bold text-slate-800">{{ $students->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg border border-slate-200/60 p-6">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-slate-600">Status Aktif</p>
                        <p class="text-2xl font-bold text-slate-800">{{ $students->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg border border-slate-200/60 p-6">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a4 4 0 118 0v4m-4 0H9m6 0h3m2 0a2 2 0 012 2v9a2 2 0 01-2 2H6a2 2 0 01-2-2V9a2 2 0 012-2h2m8 0V3a4 4 0 00-8 0v4m0 0h8"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-slate-600">Akses Penuh</p>
                        <p class="text-2xl font-bold text-slate-800">24/7</p>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
