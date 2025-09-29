<div class="space-y-6">
    <!-- Table Container -->
    <div class="overflow-hidden rounded-xl border border-slate-200/60">
        @forelse ($permits as $permit)
            <!-- Permit Card -->
            <div class="bg-white border-b border-slate-200/60 last:border-b-0 hover:bg-slate-50/50 transition-colors duration-200">
                <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 items-center">
                        <!-- Date Section -->
                        <div class="lg:col-span-1">
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-12 bg-slate-100 rounded-xl flex items-center justify-center">
                                    <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a4 4 0 118 0v4m-4 0H9m6 0h3m2 0a2 2 0 012 2v9a2 2 0 01-2 2H6a2 2 0 01-2-2V9a2 2 0 012-2h2m8 0V3a4 4 0 00-8 0v4m0 0h8"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-slate-800">{{ \Carbon\Carbon::parse($permit->start_date)->isoFormat('dddd') }}</p>
                                    <p class="text-sm text-slate-600">{{ \Carbon\Carbon::parse($permit->start_date)->isoFormat('D MMMM YYYY') }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Permit Type Section -->
                        <div class="lg:col-span-1">
                            <div class="text-center lg:text-left">
                                <p class="text-xs font-medium text-slate-500 uppercase tracking-wider mb-2">Jenis Izin</p>
                                <div class="inline-flex items-center space-x-2">
                                    @php
                                        $typeIcon = '';
                                        $typeColor = '';
                                        $borderColor = '';
                                        switch(strtolower($permit->permit_type)) {
                                            case 'sakit':
                                                $typeIcon = 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z';
                                                $typeColor = 'text-red-700 bg-red-50';
                                                $borderColor = 'border-red-200';
                                                break;
                                            case 'izin':
                                                $typeIcon = 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z';
                                                $typeColor = 'text-blue-700 bg-blue-50';
                                                $borderColor = 'border-blue-200';
                                                break;
                                            case 'cuti':
                                                $typeIcon = 'M8 7V3a4 4 0 118 0v4m-4 0H9m6 0h3m2 0a2 2 0 012 2v9a2 2 0 01-2 2H6a2 2 0 01-2-2V9a2 2 0 012-2h2m8 0V3a4 4 0 00-8 0v4m0 0h8';
                                                $typeColor = 'text-purple-700 bg-purple-50';
                                                $borderColor = 'border-purple-200';
                                                break;
                                            default:
                                                $typeIcon = 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z';
                                                $typeColor = 'text-slate-700 bg-slate-50';
                                                $borderColor = 'border-slate-200';
                                                break;
                                        }
                                    @endphp

                                    <div class="inline-flex items-center px-3 py-1 rounded-lg {{ $typeColor }} {{ $borderColor }} border">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $typeIcon }}"></path>
                                        </svg>
                                        <span class="text-sm font-semibold">{{ ucfirst($permit->permit_type) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Duration Section -->
                        <div class="lg:col-span-1">
                            <div class="text-center lg:text-left">
                                <p class="text-xs font-medium text-slate-500 uppercase tracking-wider mb-2">Durasi</p>
                                @if(isset($permit->end_date) && $permit->end_date)
                                    @php
                                        $startDate = \Carbon\Carbon::parse($permit->start_date);
                                        $endDate = \Carbon\Carbon::parse($permit->end_date);
                                        $duration = $startDate->diffInDays($endDate) + 1;
                                    @endphp
                                    <div class="flex items-center space-x-2 justify-center lg:justify-start">
                                        <div class="w-2 h-2 bg-orange-500 rounded-full"></div>
                                        <span class="text-lg font-bold text-slate-800">{{ $duration }}</span>
                                        <span class="text-sm text-slate-500">{{ $duration > 1 ? 'hari' : 'hari' }}</span>
                                    </div>
                                @else
                                    <div class="flex items-center space-x-2 justify-center lg:justify-start">
                                        <div class="w-2 h-2 bg-orange-500 rounded-full"></div>
                                        <span class="text-lg font-bold text-slate-800">1</span>
                                        <span class="text-sm text-slate-500">hari</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Status Section -->
                        <div class="lg:col-span-1">
                            <div class="flex justify-center lg:justify-end">
                                @if($permit->status == 'disetujui')
                                    <div class="inline-flex items-center px-4 py-2 rounded-xl bg-green-100 border border-green-200">
                                        <svg class="w-4 h-4 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span class="text-sm font-semibold text-green-700">Disetujui</span>
                                    </div>
                                @elseif($permit->status == 'ditolak')
                                    <div class="inline-flex items-center px-4 py-2 rounded-xl bg-red-100 border border-red-200">
                                        <svg class="w-4 h-4 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span class="text-sm font-semibold text-red-700">Ditolak</span>
                                    </div>
                                @else
                                    <div class="inline-flex items-center px-4 py-2 rounded-xl bg-yellow-100 border border-yellow-200">
                                        <svg class="w-4 h-4 text-yellow-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span class="text-sm font-semibold text-yellow-700">Menunggu</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Additional Info (if available) -->
                    @if(isset($permit->reason) && $permit->reason)
                        <div class="mt-4 pt-4 border-t border-slate-200/60">
                            <div class="bg-slate-50 rounded-lg p-3">
                                <p class="text-xs font-medium text-slate-500 uppercase tracking-wider mb-1">Keterangan</p>
                                <p class="text-sm text-slate-700 leading-relaxed">{{ $permit->reason }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <!-- Empty State -->
            <div class="bg-white rounded-xl p-12">
                <div class="text-center max-w-md mx-auto">
                    <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-800 mb-2">Belum Ada Riwayat Izin</h3>
                    <p class="text-slate-600">Riwayat pengajuan izin siswa akan muncul di sini setelah mengajukan permohonan izin.</p>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if(isset($permits) && method_exists($permits, 'links'))
    <div class="flex justify-center">
        <div class="bg-white rounded-xl shadow-lg border border-slate-200/60 p-2">
            {{ $permits->links('pagination::tailwind') }}
        </div>
    </div>
    @endif
</div>
