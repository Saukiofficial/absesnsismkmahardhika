<div class="flex flex-col h-full">
    <div class="flex-grow overflow-y-auto max-h-[500px] scrollbar-thin scrollbar-thumb-slate-200 scrollbar-track-transparent">
        @forelse ($groupedAttendances as $date => $attendancesOnDate)
            @php
                $checkIn = $attendancesOnDate->where('status', 'in')->first();
                $checkOut = $attendancesOnDate->where('status', 'out')->last();

                // Format Tanggal
                $carbonDate = \Carbon\Carbon::parse($date);
                $isWeekend = $carbonDate->isWeekend();

                // Status Terlambat
                $isLate = false;
                if ($checkIn && $checkIn->recorded_at->format('H:i:s') > '07:00:00') {
                    $isLate = true;
                }
            @endphp

            <div class="group border-b border-slate-100 last:border-0 hover:bg-slate-50 transition-colors duration-150">
                <div class="p-5">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center space-x-3">
                            <div class="flex flex-col items-center justify-center w-12 h-12 rounded-xl {{ $isWeekend ? 'bg-red-50 text-red-600' : 'bg-slate-100 text-slate-600' }} border border-slate-200">
                                <span class="text-xs font-bold uppercase">{{ $carbonDate->isoFormat('MMM') }}</span>
                                <span class="text-lg font-bold leading-none">{{ $carbonDate->format('d') }}</span>
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-800">{{ $carbonDate->isoFormat('dddd') }}</h4>
                                <p class="text-xs text-slate-500">{{ $carbonDate->isoFormat('D MMMM Y') }}</p>
                            </div>
                        </div>
                        @if($isLate)
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-700 border border-yellow-200">
                                Terlambat
                            </span>
                        @endif
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <!-- Check In -->
                        <div class="relative p-3 rounded-xl bg-emerald-50/50 border border-emerald-100">
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-[10px] uppercase tracking-wider font-bold text-emerald-600/70">Masuk</span>
                                @if($checkIn)
                                    <svg class="w-3 h-3 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                @endif
                            </div>
                            @if($checkIn)
                                <div class="text-lg font-bold text-emerald-700 font-mono">
                                    {{ $checkIn->recorded_at->format('H:i') }}
                                </div>
                                <div class="text-[10px] text-emerald-600 truncate" title="{{ $checkIn->method ?? 'RFID' }}">
                                    Via {{ $checkIn->method ?? 'RFID' }}
                                </div>
                            @else
                                <div class="text-sm font-medium text-slate-400 italic">-- : --</div>
                            @endif
                        </div>

                        <!-- Check Out -->
                        <div class="relative p-3 rounded-xl bg-blue-50/50 border border-blue-100">
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-[10px] uppercase tracking-wider font-bold text-blue-600/70">Pulang</span>
                                @if($checkOut)
                                    <svg class="w-3 h-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                @endif
                            </div>
                            @if($checkOut)
                                <div class="text-lg font-bold text-blue-700 font-mono">
                                    {{ $checkOut->recorded_at->format('H:i') }}
                                </div>
                                <div class="text-[10px] text-blue-600 truncate" title="{{ $checkOut->method ?? 'RFID' }}">
                                    Via {{ $checkOut->method ?? 'RFID' }}
                                </div>
                            @else
                                <div class="text-sm font-medium text-slate-400 italic">-- : --</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="flex flex-col items-center justify-center py-12 px-6 text-center">
                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-slate-800 font-bold mb-1">Belum Ada Data</h3>
                <p class="text-slate-500 text-sm">Belum ada catatan absensi untuk periode ini.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($attendances->hasPages())
        <div class="p-4 border-t border-slate-100 bg-slate-50/50">
            {{ $attendances->appends(request()->except('attendance_page'))->links() }}
        </div>
    @endif
</div>
