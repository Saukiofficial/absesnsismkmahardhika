<div class="space-y-6">
    <!-- Table Container -->
    <div class="overflow-hidden rounded-xl border border-slate-200/60">
        @forelse ($groupedAttendances as $date => $attendancesOnDate)
            @php
                $checkIn = $attendancesOnDate->where('status', 'in')->first();
                $checkOut = $attendancesOnDate->where('status', 'out')->last();

                // Cek apakah terlambat (menggunakan batas waktu 06:30)
                $isLate = false;
                if ($checkIn && $checkIn->recorded_at->format('H:i:s') > '06:30:00') {
                    $isLate = true;
                }
            @endphp

            <!-- Attendance Card -->
            <div class="bg-white border-b border-slate-200/60 last:border-b-0 hover:bg-slate-50/50 transition-colors duration-200">
                <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 items-center">
                        <!-- Date Section -->
                        <div class="lg:col-span-1">
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-12 bg-slate-100 rounded-xl flex items-center justify-center">
                                    <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-slate-800">{{ \Carbon\Carbon::parse($date)->isoFormat('dddd') }}</p>
                                    <p class="text-sm text-slate-600">{{ \Carbon\Carbon::parse($date)->isoFormat('D MMMM YYYY') }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Check In Section -->
                        <div class="lg:col-span-1">
                            <div class="text-center lg:text-left">
                                <p class="text-xs font-medium text-slate-500 uppercase tracking-wider mb-1">Jam Masuk</p>
                                @if($checkIn)
                                    <div class="flex items-center space-x-2 justify-center lg:justify-start">
                                        <div class="w-2 h-2 {{ $isLate ? 'bg-yellow-500' : 'bg-green-500' }} rounded-full"></div>
                                        <span class="text-lg font-bold text-slate-800">{{ $checkIn->recorded_at->format('H:i') }}</span>
                                        <span class="text-sm text-slate-500">{{ $checkIn->recorded_at->format('s') }}s</span>

                                        <!-- Badge Terlambat -->
                                        @if($isLate)
                                            <span class="text-xs font-bold text-yellow-800 bg-yellow-100 px-2 py-0.5 rounded-md">Terlambat</span>
                                        @endif
                                    </div>
                                @else
                                    <div class="flex items-center space-x-2 justify-center lg:justify-start">
                                        <div class="w-2 h-2 bg-slate-300 rounded-full"></div>
                                        <span class="text-lg font-medium text-slate-400">--:--</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Check Out Section -->
                        <div class="lg:col-span-1">
                            <div class="text-center lg:text-left">
                                <p class="text-xs font-medium text-slate-500 uppercase tracking-wider mb-1">Jam Pulang</p>
                                @if($checkOut)
                                    <div class="flex items-center space-x-2 justify-center lg:justify-start">
                                        <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                                        <span class="text-lg font-bold text-slate-800">{{ $checkOut->recorded_at->format('H:i') }}</span>
                                        <span class="text-sm text-slate-500">{{ $checkOut->recorded_at->format('s') }}s</span>
                                    </div>
                                @else
                                    <div class="flex items-center space-x-2 justify-center lg:justify-start">
                                        <div class="w-2 h-2 bg-slate-300 rounded-full"></div>
                                        <span class="text-lg font-medium text-slate-400">--:--</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Status Section -->
                        <div class="lg:col-span-1">
                            <div class="flex justify-center lg:justify-end">
                                @if($checkIn && $checkOut)
                                    <div class="inline-flex items-center px-4 py-2 rounded-xl bg-green-100 border border-green-200">
                                        <svg class="w-4 h-4 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span class="text-sm font-semibold text-green-700">Lengkap</span>
                                    </div>
                                @elseif($checkIn)
                                    <div class="inline-flex items-center px-4 py-2 rounded-xl bg-blue-100 border border-blue-200">
                                        <svg class="w-4 h-4 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                                        </svg>
                                        <span class="text-sm font-semibold text-blue-700">Hanya Masuk</span>
                                    </div>
                                @else
                                    <div class="inline-flex items-center px-4 py-2 rounded-xl bg-slate-100 border border-slate-200">
                                        <svg class="w-4 h-4 text-slate-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span class="text-sm font-semibold text-slate-600">Tidak Diketahui</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <!-- Empty State -->
            <div class="bg-white rounded-xl p-12">
                <div class="text-center max-w-md mx-auto">
                    <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-800 mb-2">Belum Ada Data Absensi</h3>
                    <p class="text-slate-600">Data absensi siswa akan muncul di sini setelah melakukan check-in di sekolah.</p>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($attendances->hasPages())
    <div class="flex justify-center mt-6">
        <div class="bg-white rounded-xl shadow-lg border border-slate-200/60 p-2">
            {{ $attendances->links('pagination::tailwind') }}
        </div>
    </div>
    @endif
</div>
