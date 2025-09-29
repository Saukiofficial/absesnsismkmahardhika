@extends('student.layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Page Header -->
        <div class="mb-8">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
                <div class="bg-gradient-to-r from-amber-600 to-orange-600 px-8 py-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                        <div class="mb-4 sm:mb-0">
                            <h1 class="text-2xl font-bold text-white mb-1">Riwayat Pengajuan Izin</h1>
                            <p class="text-amber-100 opacity-90">Kelola dan pantau status pengajuan izin Anda</p>
                        </div>
                        <div class="flex items-center space-x-4">
                            <!-- Statistics -->
                            <div class="hidden md:block bg-white/10 backdrop-blur-sm rounded-xl px-4 py-3">
                                <div class="text-white text-sm font-medium text-center">
                                    <div>Total Pengajuan</div>
                                    <div class="text-amber-200 text-lg font-bold">{{ $permits->total() ?? 0 }}</div>
                                </div>
                            </div>
                            <!-- Create New Button -->
                            <a href="{{ route('student.permits.create') }}" class="inline-flex items-center px-4 py-3 bg-white hover:bg-amber-50 text-amber-700 font-semibold rounded-xl shadow-sm transition-all duration-200 group">
                                <svg class="w-5 h-5 mr-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                <span>Buat Pengajuan</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Permits Table/Cards -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">

            <!-- Table Header -->
            <div class="bg-gradient-to-r from-slate-50 to-slate-100 px-8 py-6 border-b border-slate-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h3 class="text-xl font-bold text-slate-800">Daftar Pengajuan Izin</h3>
                        <p class="text-slate-600 text-sm mt-1">Riwayat lengkap pengajuan dan status persetujuan</p>
                    </div>

                    {{--  <!-- Filter Actions -->
                    <div class="mt-4 sm:mt-0 flex items-center space-x-3">
                        <div class="bg-white px-3 py-2 rounded-lg border border-slate-200 shadow-sm">
                            <div class="flex items-center space-x-2 text-sm text-slate-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.414A1 1 0 013 6.707V4z"></path>
                                </svg>
                                <span>Filter Status</span>
                            </div>
                        </div>
                        <div class="bg-white px-3 py-2 rounded-lg border border-slate-200 shadow-sm">
                            <div class="flex items-center space-x-2 text-sm text-slate-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2m-9 4v10a2 2 0 002 2h6a2 2 0 002-2V8M7 8h10M9 12h6m-6 4h6"></path>
                                </svg>
                                <span>Export</span>
                            </div>
                        </div>
                    </div>  --}}
                </div>
            </div>

            <!-- Desktop Table View -->
            <div class="hidden lg:block overflow-x-auto">
                @forelse ($permits as $permit)
                    @if($loop->first)
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th scope="col" class="px-8 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <span>Periode Izin</span>
                                    </div>
                                </th>
                                <th scope="col" class="px-8 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                        </svg>
                                        <span>Tipe Izin</span>
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
                                <th scope="col" class="px-8 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                        </svg>
                                        <span>Aksi</span>
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
                                    <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl flex items-center justify-center text-white">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div>
                                    <div class="text-sm font-semibold text-slate-900">
                                        {{ \Carbon\Carbon::parse($permit->start_date)->isoFormat('D MMM YYYY') }}
                                    </div>
                                    <div class="text-xs text-slate-500 mt-1">
                                        sampai {{ \Carbon\Carbon::parse($permit->end_date)->isoFormat('D MMM YYYY') }}
                                    </div>
                                    <div class="text-xs text-slate-400 mt-1">
                                        {{ \Carbon\Carbon::parse($permit->start_date)->diffInDays(\Carbon\Carbon::parse($permit->end_date)) + 1 }} hari
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6 whitespace-nowrap">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-blue-50 rounded-lg flex items-center justify-center">
                                    @if($permit->permit_type == 'sakit')
                                        <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                        </svg>
                                    @elseif($permit->permit_type == 'izin')
                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    @endif
                                </div>
                                <div>
                                    <div class="text-sm font-semibold text-slate-900 capitalize">
                                        {{ $permit->permit_type }}
                                    </div>
                                    <div class="text-xs text-slate-500 mt-1">
                                        Pengajuan
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6 whitespace-nowrap">
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
                        </td>
                        <td class="px-8 py-6 whitespace-nowrap">
                            <a href="{{ route('student.permits.show', $permit) }}" class="inline-flex items-center px-3 py-2 text-sm font-medium text-slate-700 bg-slate-50 hover:bg-slate-100 rounded-lg transition-colors group">
                                <svg class="w-4 h-4 mr-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                Detail
                            </a>
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-slate-600 mb-3">Belum Ada Pengajuan Izin</h3>
                        <p class="text-slate-500 max-w-md mx-auto mb-6">
                            Anda belum memiliki riwayat pengajuan izin. Buat pengajuan pertama Anda untuk memulai.
                        </p>
                        <a href="{{ route('student.permits.create') }}" class="inline-flex items-center px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Buat Pengajuan Izin
                        </a>
                    </div>
                @endforelse
            </div>

            <!-- Mobile Card View -->
            <div class="lg:hidden">
                @forelse ($permits as $permit)
                    <div class="border-b border-slate-100 last:border-b-0 hover:bg-slate-50/50 transition-colors duration-150">
                        <div class="px-6 py-6">
                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl flex items-center justify-center text-white">
                                        @if($permit->permit_type == 'sakit')
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                            </svg>
                                        @elseif($permit->permit_type == 'izin')
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        @else
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <h4 class="text-lg font-semibold text-slate-800 capitalize">{{ $permit->permit_type }}</h4>
                                            <p class="text-sm text-slate-600 mt-1">
                                                {{ \Carbon\Carbon::parse($permit->start_date)->isoFormat('D MMM YYYY') }} - {{ \Carbon\Carbon::parse($permit->end_date)->isoFormat('D MMM YYYY') }}
                                            </p>
                                            <p class="text-xs text-slate-500 mt-1">
                                                {{ \Carbon\Carbon::parse($permit->start_date)->diffInDays(\Carbon\Carbon::parse($permit->end_date)) + 1 }} hari
                                            </p>
                                        </div>
                                        <div class="flex flex-col items-end space-y-2">
                                            @if($permit->status == 'disetujui')
                                                <div class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    Disetujui
                                                </div>
                                            @elseif($permit->status == 'ditolak')
                                                <div class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-red-50 text-red-700 border border-red-200">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    Ditolak
                                                </div>
                                            @else
                                                <div class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-amber-50 text-amber-700 border border-amber-200">
                                                    <svg class="w-3 h-3 mr-1 animate-spin" fill="none" viewBox="0 0 24 24">
                                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                        <path class="opacity-75" fill="currentColor" d="m4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                    </svg>
                                                    Menunggu
                                                </div>
                                            @endif
                                            <a href="{{ route('student.permits.show', $permit) }}" class="text-xs font-medium text-slate-600 hover:text-slate-800 flex items-center">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                                Detail
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <!-- Mobile Empty State -->
                    <div class="px-6 py-16 text-center">
                        <div class="mx-auto w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-slate-600 mb-2">Belum Ada Pengajuan</h3>
                        <p class="text-slate-500 text-sm mb-4">Buat pengajuan izin pertama Anda</p>
                        <a href="{{ route('student.permits.create') }}" class="inline-flex items-center px-3 py-2 bg-amber-600 hover:bg-amber-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Buat Pengajuan
                        </a>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($permits->hasPages())
            <div class="bg-slate-50 px-8 py-6 border-t border-slate-200">
                <div class="flex items-center justify-between">
                    <div class="flex-1 flex justify-between sm:hidden">
                        @if ($permits->onFirstPage())
                            <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-slate-500 bg-white border border-slate-300 cursor-default leading-5 rounded-md">
                                Previous
                            </span>
                        @else
                            <a href="{{ $permits->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 leading-5 rounded-md hover:text-slate-500 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 active:bg-slate-100 active:text-slate-700 transition ease-in-out duration-150">
                                Previous
                            </a>
                        @endif

                        @if ($permits->hasMorePages())
                            <a href="{{ $permits->nextPageUrl() }}" class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-slate-700 bg-white border border-slate-300 leading-5 rounded-md hover:text-slate-500 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 active:bg-slate-100 active:text-slate-700 transition ease-in-out duration-150">
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
                                Menampilkan
                                <span class="font-medium">{{ $permits->firstItem() }}</span>
                                sampai
                                <span class="font-medium">{{ $permits->lastItem() }}</span>
                                dari
                                <span class="font-medium">{{ $permits->total() }}</span>
                                pengajuan
                            </p>
                        </div>
                        <div>
                            <nav class="relative z-0 inline-flex shadow-sm rounded-md">
                                {{ $permits->links() }}
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
/* Card hover effects */
.permit-card-hover:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
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

/* Button hover effects */
.btn-hover:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
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

/* Loading animation for pending status */
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Responsive table enhancements */
@media (max-width: 1023px) {
    .mobile-card-shadow {
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }
}

/* Gradient text effects */
.gradient-text {
    background: linear-gradient(135deg, #f59e0b, #ea580c);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

/* Icon animations */
.icon-bounce:hover {
    animation: bounce 0.6s infinite;
}

@keyframes bounce {
    0%, 100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-4px);
    }
}
</style>
@endsection
