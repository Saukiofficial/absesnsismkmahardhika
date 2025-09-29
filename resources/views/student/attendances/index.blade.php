@extends('student.layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Page Header -->
        <div class="mb-8">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
                <div class="bg-gradient-to-r from-emerald-600 to-teal-700 px-8 py-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-white mb-1">Riwayat Absensi</h1>
                            <p class="text-emerald-100 opacity-90">Pantau catatan kehadiran Anda secara detail</p>
                        </div>
                        <div class="hidden md:block">
                            <div class="bg-white/10 backdrop-blur-sm rounded-xl px-4 py-3 flex items-center space-x-3">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                <div class="text-white text-sm font-medium">
                                    <div>Total Records</div>
                                    <div class="text-emerald-200">{{ $groupedAttendances ? count($groupedAttendances) : 0 }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Attendance Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">

            <!-- Table Header -->
            <div class="bg-gradient-to-r from-slate-50 to-slate-100 px-8 py-6 border-b border-slate-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h3 class="text-xl font-bold text-slate-800">Data Kehadiran</h3>
                        <p class="text-slate-600 text-sm mt-1">Riwayat lengkap jam masuk dan pulang Anda</p>
                    </div>

                    <!-- Filter/Export Actions -->
                    <div class="mt-4 sm:mt-0 flex items-center space-x-3">
                        <div class="bg-white px-3 py-2 rounded-lg border border-slate-200 shadow-sm">
                            <div class="flex items-center space-x-2 text-sm text-slate-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.414A1 1 0 013 6.707V4z"></path>
                                </svg>
                                <span>Filter</span>
                            </div>
                        </div>
                        <div class="bg-white px-3 py-2 rounded-lg border border-slate-200 shadow-sm">
                            <div class="flex items-center space-x-2 text-sm text-slate-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <span>Export</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table Content -->
            <div class="overflow-x-auto">
                @forelse ($groupedAttendances as $date => $attendancesOnDate)
                    @php
                        // Cari jam masuk (record 'in' pertama) dan jam pulang (record 'out' terakhir)
                        $checkIn = $attendancesOnDate->where('status', 'in')->first();
                        $checkOut = $attendancesOnDate->where('status', 'out')->last();
                    @endphp

                    @if($loop->first)
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th scope="col" class="px-8 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <span>Tanggal</span>
                                    </div>
                                </th>
                                <th scope="col" class="px-8 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span>Jam Masuk</span>
                                    </div>
                                </th>
                                <th scope="col" class="px-8 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                        </svg>
                                        <span>Jam Pulang</span>
                                    </div>
                                </th>
                                <th scope="col" class="px-8 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span>Status</span>
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-200">
                    @endif

                    <tr class="hover:bg-slate-50/50 transition-colors duration-150 {{ $loop->index % 2 == 0 ? 'bg-white' : 'bg-slate-25' }}">
                        <td class="px-8 py-6 whitespace-nowrap">
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl flex items-center justify-center text-white text-sm font-bold">
                                        {{ \Carbon\Carbon::parse($date)->format('d') }}
                                    </div>
                                </div>
                                <div>
                                    <div class="text-sm font-semibold text-slate-900">
                                        {{ \Carbon\Carbon::parse($date)->isoFormat('dddd') }}
                                    </div>
                                    <div class="text-sm text-slate-500">
                                        {{ \Carbon\Carbon::parse($date)->isoFormat('D MMMM YYYY') }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6 whitespace-nowrap">
                            @if($checkIn)
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-blue-50 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-sm font-semibold text-slate-900">
                                            {{ $checkIn->recorded_at->format('H:i') }}
                                        </div>
                                        <div class="text-xs text-slate-500">
                                            {{ $checkIn->recorded_at->format('s') }}s
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-slate-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                        </svg>
                                    </div>
                                    <span class="text-sm text-slate-500 font-medium">Tidak ada data</span>
                                </div>
                            @endif
                        </td>
                        <td class="px-8 py-6 whitespace-nowrap">
                            @if($checkOut)
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-orange-50 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-sm font-semibold text-slate-900">
                                            {{ $checkOut->recorded_at->format('H:i') }}
                                        </div>
                                        <div class="text-xs text-slate-500">
                                            {{ $checkOut->recorded_at->format('s') }}s
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-slate-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                        </svg>
                                    </div>
                                    <span class="text-sm text-slate-500 font-medium">Belum pulang</span>
                                </div>
                            @endif
                        </td>
                        <td class="px-8 py-6 whitespace-nowrap">
                            @if($checkIn && $checkOut)
                                <div class="inline-flex items-center px-3 py-2 rounded-lg text-sm font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    Lengkap
                                </div>
                            @elseif($checkIn)
                                <div class="inline-flex items-center px-3 py-2 rounded-lg text-sm font-medium bg-blue-50 text-blue-700 border border-blue-200">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                    </svg>
                                    Hanya Masuk
                                </div>
                            @else
                                <div class="inline-flex items-center px-3 py-2 rounded-lg text-sm font-medium bg-slate-50 text-slate-700 border border-slate-200">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                    </svg>
                                    Tidak Diketahui
                                </div>
                            @endif
                        </td>
                    </tr>

                    @if($loop->last)
                        </tbody>
                    </table>
                    @endif

                @empty
                    <!-- Empty State -->
                    <div class="px-8 py-16 text-center">
                        <div class="mx-auto w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mb-6">
                            <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-slate-600 mb-3">Belum Ada Data Absensi</h3>
                        <p class="text-slate-500 max-w-md mx-auto">
                            Anda belum memiliki catatan absensi. Data akan muncul setelah Anda melakukan absensi pertama kali.
                        </p>
                        <div class="mt-6">
                            <button class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Mulai Absensi
                            </button>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if(isset($attendances) && $attendances->hasPages())
            <div class="bg-slate-50 px-8 py-6 border-t border-slate-200">
                <div class="flex items-center justify-between">
                    <div class="flex-1 flex justify-between sm:hidden">
                        @if ($attendances->onFirstPage())
                            <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-slate-500 bg-white border border-slate-300 cursor-default leading-5 rounded-md">
                                Previous
                            </span>
                        @else
                            <a href="{{ $attendances->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 leading-5 rounded-md hover:text-slate-500 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 active:bg-slate-100 active:text-slate-700 transition ease-in-out duration-150">
                                Previous
                            </a>
                        @endif

                        @if ($attendances->hasMorePages())
                            <a href="{{ $attendances->nextPageUrl() }}" class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-slate-700 bg-white border border-slate-300 leading-5 rounded-md hover:text-slate-500 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 active:bg-slate-100 active:text-slate-700 transition ease-in-out duration-150">
                                Next
                            </a>
                        @else
                            <span class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-slate-500 bg-white border border-slate-300 cursor-default leading-5 rounded-md">
                                Next
                            </span>
                        @endif
                    </div>

                    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm text-slate-700 leading-5">
                                Showing
                                <span class="font-medium">{{ $attendances->firstItem() ?? 1 }}</span>
                                to
                                <span class="font-medium">{{ $attendances->lastItem() ?? count($groupedAttendances) }}</span>
                                of
                                <span class="font-medium">{{ $attendances->total() ?? count($groupedAttendances) }}</span>
                                results
                            </p>
                        </div>
                        <div>
                            <nav class="relative z-0 inline-flex shadow-sm rounded-md">
                                {{ $attendances->links() }}
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>

    </div>
</div>

<style>
/* Table row animations */
.table-row-enter {
    animation: slideInFromLeft 0.3s ease-out;
}

@keyframes slideInFromLeft {
    0% {
        opacity: 0;
        transform: translateX(-20px);
    }
    100% {
        opacity: 1;
        transform: translateX(0);
    }
}

/* Status badge animations */
.status-badge {
    animation: fadeInScale 0.2s ease-out;
}

@keyframes fadeInScale {
    0% {
        opacity: 0;
        transform: scale(0.8);
    }
    100% {
        opacity: 1;
        transform: scale(1);
    }
}

/* Hover effects for table rows */
tbody tr:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}

/* Custom scrollbar for table */
.overflow-x-auto::-webkit-scrollbar {
    height: 8px;
}

.overflow-x-auto::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 4px;
}

.overflow-x-auto::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
}

.overflow-x-auto::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}
</style>
@endsection
