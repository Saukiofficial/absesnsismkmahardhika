@extends('layouts.app')

@section('header')
    Dashboard
@endsection

@section('content')

{{-- ======================================================================= --}}
{{-- TAMPILAN UNTUK SISWA --}}
{{-- ======================================================================= --}}
@if(Auth::user()->role === 'siswa')
    <div class="space-y-6">

        <!-- Header Sambutan -->
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Selamat Datang, {{ $user->name }}!</h2>
            <p class="text-gray-600">Berikut adalah ringkasan absensi Anda hari ini.</p>
        </div>

        {{--
            BAGIAN ALERT / PEMBERITAHUAN
            Urutan Prioritas:
            1. Sistem Dimatikan Admin (Paling Penting)
            2. Hari Libur Nasional
            3. Akhir Pekan (Sabtu/Minggu)
        --}}

        @if(isset($isSystemClosed) && $isSystemClosed)
            <!-- 1. ALERT: SISTEM DITUTUP MANUAL -->
            <div class="bg-rose-50 border-l-4 border-rose-500 p-4 rounded-r-lg shadow-sm animate-pulse">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-lg font-bold text-rose-800">Sistem Absensi Nonaktif</h3>
                        <div class="mt-1 text-sm text-rose-700">
                            <p>Admin telah menonaktifkan sistem absensi untuk sementara waktu.</p>
                            <p>Absensi tidak dapat dilakukan saat ini. Silakan hubungi admin sekolah jika ini kesalahan.</p>
                        </div>
                    </div>
                </div>
            </div>

        @elseif(isset($todayHoliday) && $todayHoliday)
            <!-- 2. ALERT: HARI LIBUR NASIONAL -->
            <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg shadow-sm">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-lg font-bold text-red-800">Sekolah Libur</h3>
                        <div class="mt-1 text-sm text-red-700">
                            <p>Hari ini adalah hari libur nasional: <strong>{{ $todayHoliday->title }}</strong>.</p>
                            <p>Sistem absensi dinonaktifkan untuk hari ini.</p>
                            @if($todayHoliday->description)
                                <p class="mt-1 italic text-xs">Catatan: {{ $todayHoliday->description }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        @elseif(isset($isWeekend) && $isWeekend)
            <!-- 3. ALERT: AKHIR PEKAN -->
            <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded-r-lg shadow-sm">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-lg font-bold text-yellow-800">Akhir Pekan</h3>
                        <div class="mt-1 text-sm text-yellow-700">
                            <p>Hari ini adalah hari libur akhir pekan ({{ \Carbon\Carbon::now()->translatedFormat('l') }}). Selamat beristirahat!</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Status Absensi Hari Ini -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <h3 class="text-lg font-bold text-gray-800 border-b border-gray-100 pb-3 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Absensi Hari Ini ({{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }})
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Kotak Masuk -->
                <div class="relative overflow-hidden bg-gradient-to-br from-green-50 to-emerald-100 p-6 rounded-xl border border-green-200">
                    <div class="relative z-10">
                        <p class="text-green-800 font-semibold mb-1">Jam Masuk</p>
                        <p class="text-3xl font-bold text-green-700 tracking-tight">
                            {{ $checkIn ? \Carbon\Carbon::parse($checkIn->recorded_at)->format('H:i:s') : '--:--' }}
                        </p>
                        <p class="text-xs text-green-600 mt-2">
                            @if($checkIn)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-200 text-green-800">
                                    Tercatat
                                </span>
                            @else
                                Belum ada data masuk
                            @endif
                        </p>
                    </div>
                    <div class="absolute right-0 bottom-0 opacity-10 transform translate-x-2 translate-y-2">
                        <svg class="w-24 h-24 text-green-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    </div>
                </div>

                <!-- Kotak Pulang -->
                <div class="relative overflow-hidden bg-gradient-to-br from-blue-50 to-indigo-100 p-6 rounded-xl border border-blue-200">
                    <div class="relative z-10">
                        <p class="text-blue-800 font-semibold mb-1">Jam Pulang</p>
                        <p class="text-3xl font-bold text-blue-700 tracking-tight">
                            {{ $checkOut ? \Carbon\Carbon::parse($checkOut->recorded_at)->format('H:i:s') : '--:--' }}
                        </p>
                        <p class="text-xs text-blue-600 mt-2">
                            @if($checkOut)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-200 text-blue-800">
                                    Tercatat
                                </span>
                            @else
                                Belum ada data pulang
                            @endif
                        </p>
                    </div>
                     <div class="absolute right-0 bottom-0 opacity-10 transform translate-x-2 translate-y-2">
                        <svg class="w-24 h-24 text-blue-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd"/></svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Riwayat Absensi Bulan Ini -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-800">Riwayat Bulan Ini</h3>
                <span class="text-xs font-medium text-gray-500 bg-white px-2 py-1 rounded border">{{ \Carbon\Carbon::now()->translatedFormat('F Y') }}</span>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jam Masuk</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jam Pulang</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($monthlyAttendances as $date => $records)
                            @php
                                $in = $records->firstWhere('status', 'in');
                                $out = $records->where('status', 'out')->last();
                                $carbonDate = \Carbon\Carbon::parse($date);
                                $isWeekendRow = $carbonDate->isWeekend();
                            @endphp
                            <tr class="{{ $isWeekendRow ? 'bg-slate-50' : 'hover:bg-gray-50' }} transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $carbonDate->translatedFormat('d F Y') }}
                                    @if($isWeekendRow)
                                        <span class="ml-2 text-[10px] text-red-500 font-bold bg-red-50 px-1.5 py-0.5 rounded">LIBUR</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $in ? \Carbon\Carbon::parse($in->recorded_at)->format('H:i:s') : '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $out ? \Carbon\Carbon::parse($out->recorded_at)->format('H:i:s') : '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @if($in && $out)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Lengkap</span>
                                    @elseif($in)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Belum Pulang</span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Tidak Hadir</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-gray-400 text-sm">
                                    Belum ada data absensi bulan ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>


{{-- ======================================================================= --}}
{{-- TAMPILAN UNTUK WALI MURID --}}
{{-- ======================================================================= --}}
@elseif(Auth::user()->role === 'wali')
    <div class="space-y-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Halo, {{ $user->name }}</h2>
            <p class="text-gray-600">Memantau aktivitas absensi putra/putri Anda.</p>
        </div>

        {{-- ALERT WALI MURID --}}
        @if(isset($isSystemClosed) && $isSystemClosed)
            <div class="bg-rose-50 border-l-4 border-rose-500 p-4 rounded-r-lg shadow-sm">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-rose-500" viewBox="0 0 20 20" fill="currentColor">
                             <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-rose-700 font-bold">
                            Info: Sistem absensi sekolah sedang dimatikan manual oleh Admin.
                        </p>
                    </div>
                </div>
            </div>
        @elseif(isset($todayHoliday) && $todayHoliday)
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r-lg shadow-sm">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-blue-700">
                            Info: Hari ini sekolah libur (<strong>{{ $todayHoliday->title }}</strong>). Tidak ada data absensi hari ini.
                        </p>
                    </div>
                </div>
            </div>
        @endif

        @if($students->isEmpty())
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-md">
                <div class="flex">
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700">
                            Anda belum terhubung dengan data siswa manapun. Silakan hubungi admin sekolah.
                        </p>
                    </div>
                </div>
            </div>
        @else
            <div class="grid grid-cols-1 gap-8">
                @foreach($students as $student)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="bg-indigo-50 px-6 py-4 border-b border-indigo-100 flex justify-between items-center">
                            <div>
                                <h3 class="text-lg font-bold text-indigo-900">{{ $student->name }}</h3>
                                <p class="text-sm text-indigo-600">Kelas: {{ $student->class ?? '-' }} | NIS: {{ $student->nis ?? '-' }}</p>
                            </div>
                            <div class="h-10 w-10 bg-indigo-200 rounded-full flex items-center justify-center text-indigo-700 font-bold">
                                {{ substr($student->name, 0, 1) }}
                            </div>
                        </div>

                        <div class="p-0">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Masuk</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pulang</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @php
                                            $studentAttendances = $monthlyAttendances->get($student->id);
                                        @endphp

                                        @if($studentAttendances)
                                            @foreach($studentAttendances->groupBy(function($d) { return \Carbon\Carbon::parse($d->recorded_at)->format('Y-m-d'); }) as $date => $records)
                                                @php
                                                    $in = $records->firstWhere('status', 'in');
                                                    $out = $records->where('status', 'out')->last();
                                                @endphp
                                                <tr class="hover:bg-gray-50">
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                        {{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        {{ $in ? \Carbon\Carbon::parse($in->recorded_at)->format('H:i:s') : '-' }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        {{ $out ? \Carbon\Carbon::parse($out->recorded_at)->format('H:i:s') : '-' }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                        @if($in)
                                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                                Hadir
                                                            </span>
                                                        @else
                                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                                Alpha
                                                            </span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="4" class="px-6 py-8 text-center text-gray-400 text-sm">Belum ada data absensi bulan ini.</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endif

@endsection
