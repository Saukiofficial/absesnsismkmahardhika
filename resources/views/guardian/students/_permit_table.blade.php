<div class="flex flex-col h-full">
    <div class="flex-grow overflow-y-auto max-h-[500px] scrollbar-thin scrollbar-thumb-slate-200 scrollbar-track-transparent">
        @forelse ($permits as $permit)
            @php
                $statusColor = match($permit->status) {
                    'disetujui' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                    'ditolak' => 'bg-rose-50 text-rose-700 border-rose-100',
                    default => 'bg-amber-50 text-amber-700 border-amber-100',
                };

                $statusIcon = match($permit->status) {
                    'disetujui' => '<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>',
                    'ditolak' => '<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>',
                    default => '<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
                };
            @endphp

            <div class="p-5 border-b border-slate-100 last:border-0 hover:bg-slate-50 transition-colors duration-150">
                <div class="flex justify-between items-start mb-3">
                    <div class="flex items-center space-x-2">
                        <span class="px-2.5 py-1 rounded-md text-xs font-bold bg-slate-100 text-slate-600 border border-slate-200 uppercase tracking-wider">
                            {{ $permit->permit_type }}
                        </span>
                        <span class="text-xs text-slate-400">•</span>
                        <span class="text-xs font-medium text-slate-500">
                            {{ $permit->created_at->diffForHumans() }}
                        </span>
                    </div>
                    <div class="flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold border {{ $statusColor }}">
                        {!! $statusIcon !!}
                        <span class="uppercase tracking-wide">{{ $permit->status }}</span>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="flex items-center text-sm font-bold text-slate-800">
                        <svg class="w-4 h-4 mr-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a4 4 0 118 0v4m-4 0H9m6 0h3m2 0a2 2 0 012 2v9a2 2 0 01-2 2H6a2 2 0 01-2-2V9a2 2 0 012-2h2m8 0V3a4 4 0 00-8 0v4"></path>
                        </svg>
                        {{ \Carbon\Carbon::parse($permit->start_date)->isoFormat('D MMM Y') }}
                        <span class="mx-2 text-slate-300">➜</span>
                        {{ \Carbon\Carbon::parse($permit->end_date)->isoFormat('D MMM Y') }}
                    </div>
                </div>

                <div class="bg-slate-50 p-3 rounded-lg border border-slate-100">
                    <p class="text-sm text-slate-600 line-clamp-2 italic">
                        "{{ $permit->reason }}"
                    </p>
                </div>
            </div>
        @empty
            <div class="flex flex-col items-center justify-center py-12 px-6 text-center">
                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h3 class="text-slate-800 font-bold mb-1">Tidak Ada Data</h3>
                <p class="text-slate-500 text-sm">Belum ada riwayat pengajuan izin.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($permits->hasPages())
        <div class="p-4 border-t border-slate-100 bg-slate-50/50">
            {{ $permits->appends(request()->except('permit_page'))->links() }}
        </div>
    @endif
</div>
