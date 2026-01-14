@extends('layouts.admin')

@section('header', 'Rekapitulasi Absensi')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 py-6 px-4">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-gradient-to-br from-indigo-600 to-purple-700 rounded-2xl flex items-center justify-center shadow-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
            </div>
            <div>
                <h1 class="text-3xl font-bold text-slate-800">Rekapitulasi Absensi</h1>
                <p class="text-slate-500 font-medium mt-1">Pantau kehadiran siswa secara real-time</p>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-3xl p-6 shadow-xl shadow-indigo-100 border border-indigo-50 mb-6">
        <form action="{{ route('admin.attendances.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="relative group">
                <label class="block text-sm font-semibold text-slate-700 mb-2">Tanggal Mulai</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}"
                       class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all outline-none font-medium text-slate-600">
            </div>

            <div class="relative group">
                <label class="block text-sm font-semibold text-slate-700 mb-2">Tanggal Akhir</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}"
                       class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all outline-none font-medium text-slate-600">
            </div>

            <div class="relative group">
                <label class="block text-sm font-semibold text-slate-700 mb-2">Kelas</label>
                <div class="relative">
                    <select name="class" class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all outline-none appearance-none font-medium text-slate-600">
                        <option value="">Semua Kelas</option>
                        @foreach($classes as $class)
                            <option value="{{ $class }}" {{ request('class') == $class ? 'selected' : '' }}>
                                {{ $class }}
                            </option>
                        @endforeach
                    </select>
                    <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="flex items-end gap-3">
                <button type="submit" class="flex-1 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-indigo-500/30 transition-all transform hover:scale-[1.02] active:scale-95 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Filter
                </button>
                <a href="{{ route('admin.attendances.index') }}" class="px-4 py-3 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold rounded-xl transition-all border-2 border-slate-200" title="Reset Filter">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                </a>
            </div>
        </form>
    </div>

    <!-- === INFO BANNER UNTUK MODE DEFAULT === -->
    @if(!request('start_date') || !request('end_date'))
        @php
            $currentMonth = date('n');
            $semester = ($currentMonth >= 7) ? 'Ganjil' : 'Genap';
            $year = date('Y');
            $rangeStart = ($currentMonth >= 7) ? '1 Juli' : '1 Januari';
        @endphp
        <div class="mb-6 p-4 bg-blue-50 border border-blue-100 rounded-2xl flex items-start gap-4 animate-fade-in-down shadow-sm">
            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0 text-blue-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <h4 class="font-bold text-blue-800 text-sm">Tampilan Default: Semester {{ $semester }} {{ $year }}</h4>
                <p class="text-blue-600 text-xs mt-1 leading-relaxed">
                    Saat ini menampilkan seluruh data absensi mulai dari <span class="font-bold">{{ $rangeStart }}</span> sampai hari ini.
                    Gunakan kolom "Tanggal Mulai" dan "Tanggal Akhir" di atas jika ingin melihat rentang waktu yang lebih spesifik (misal: per hari atau per minggu).
                </p>
            </div>
        </div>
    @endif
    <!-- ===================================================== -->

    <!-- Table Section -->
    <div class="bg-white rounded-3xl shadow-xl shadow-indigo-100 border border-indigo-50 overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
            <h2 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                <span class="w-2 h-8 bg-indigo-500 rounded-full"></span>
                Data Absensi
            </h2>

            <!-- UPDATE: Tombol Export Aktif -->
            <form action="{{ route('admin.attendances.export') }}" method="GET">
                 <!-- Kirimkan filter saat ini agar export sesuai dengan tampilan tabel -->
                 <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                 <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                 <input type="hidden" name="class" value="{{ request('class') }}">

                 <button type="submit" class="px-4 py-2 bg-emerald-50 text-emerald-600 hover:bg-emerald-100 rounded-lg font-semibold transition-colors flex items-center gap-2 border border-emerald-100 hover:scale-105 active:scale-95 duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Export Excel
                </button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-left">
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Siswa</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Jam Masuk</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Jam Pulang</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($attendances as $attendance)
                        <tr class="hover:bg-indigo-50/30 transition-colors group">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-slate-700">{{ $attendance['date'] }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <!-- Tombol interaktif untuk membuka Sidebar -->
                                <button onclick="openStudentSidebar({{ $attendance['user_id'] }})" class="flex items-center text-left hover:bg-slate-100 p-2 -ml-2 rounded-lg transition-colors w-full group-hover:bg-white/50">
                                    <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold mr-3 border-2 border-white shadow-sm flex-shrink-0">
                                        {{ substr($attendance['user_name'], 0, 1) }}
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div class="font-bold text-indigo-600 hover:text-indigo-800 transition-colors truncate">{{ $attendance['user_name'] }}</div>
                                        <div class="text-xs text-slate-500 font-medium">{{ $attendance['class'] }}</div>
                                    </div>
                                    <svg class="w-4 h-4 text-slate-300 opacity-0 group-hover:opacity-100 transition-opacity ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </button>
                            </td>
                            <td class="px-6 py-4 text-center whitespace-nowrap">
                                @if($attendance['check_in'])
                                    <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                        {{ $attendance['check_in'] }}
                                    </span>
                                @else
                                    <span class="text-slate-400 font-medium">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center whitespace-nowrap">
                                @if($attendance['check_out'])
                                    <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-bold bg-blue-50 text-blue-700 border border-blue-100">
                                        {{ $attendance['check_out'] }}
                                    </span>
                                @else
                                    <span class="text-slate-400 font-medium">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center whitespace-nowrap">
                                @if(isset($attendance['status_in']) && $attendance['status_in'] == 'Terlambat')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-600 border border-rose-100">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                        Terlambat
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-600 border border-emerald-100">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Tepat Waktu
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                                        <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414a1 1 0 00-.707-.293H6"/>
                                        </svg>
                                    </div>
                                    <div class="text-center">
                                        <h3 class="text-lg font-semibold text-slate-600 mb-1">Tidak ada data absensi</h3>
                                        <p class="text-slate-500 text-sm">Silakan gunakan filter di atas untuk mencari data absensi</p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- STUDENT DETAIL SIDEBAR / OFF-CANVAS        -->
<!-- ========================================== -->
<div id="studentSidebarOverlay" onclick="closeStudentSidebar()" class="fixed inset-0 bg-black/30 backdrop-blur-sm z-40 hidden transition-opacity duration-300 opacity-0"></div>

<div id="studentSidebar" class="fixed inset-y-0 right-0 w-full max-w-md bg-white shadow-2xl z-50 transform translate-x-full transition-transform duration-300 ease-in-out flex flex-col font-sans">
    <!-- Header Sidebar -->
    <div class="p-6 bg-gradient-to-r from-indigo-600 to-purple-600 text-white relative flex-shrink-0">
        <button onclick="closeStudentSidebar()" class="absolute top-4 right-4 text-white/80 hover:text-white bg-white/10 hover:bg-white/20 rounded-lg p-2 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>

        <div class="flex items-center gap-4 mt-2">
            <div id="sidebarPhotoContainer" class="w-16 h-16 rounded-2xl bg-white p-1 shadow-lg overflow-hidden">
                <!-- Foto akan diinject via JS -->
                <div class="w-full h-full rounded-xl bg-slate-100 flex items-center justify-center text-slate-400 font-bold text-2xl" id="sidebarInitial">A</div>
                <img id="sidebarPhoto" src="" class="w-full h-full rounded-xl object-cover hidden">
            </div>
            <div>
                <h3 id="sidebarName" class="text-xl font-bold leading-tight">Memuat...</h3>
                <p id="sidebarNis" class="text-indigo-100 text-sm opacity-90 mt-1">NIS: ...</p>
                <div id="sidebarClass" class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-white/20 text-white mt-1">
                    ...
                </div>
            </div>
        </div>
    </div>

    <!-- Content Sidebar -->
    <div class="flex-1 overflow-y-auto p-6 bg-slate-50">
        <!-- Statistik Bulan Ini -->
        <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4">Statistik Bulan Ini</h4>
        <div class="grid grid-cols-2 gap-3 mb-6">
            <div class="bg-white p-4 rounded-xl border border-slate-100 shadow-sm">
                <div class="text-slate-500 text-xs font-medium mb-1">Hadir</div>
                <div class="text-2xl font-bold text-emerald-600" id="statHadir">-</div>
            </div>
            <div class="bg-white p-4 rounded-xl border border-slate-100 shadow-sm">
                <div class="text-slate-500 text-xs font-medium mb-1">Izin/Sakit</div>
                <div class="text-2xl font-bold text-blue-600">
                    <span id="statIzin">-</span> / <span id="statSakit">-</span>
                </div>
            </div>
            <div class="bg-white p-4 rounded-xl border border-slate-100 shadow-sm col-span-2 flex items-center justify-between">
                <div>
                    <div class="text-slate-500 text-xs font-medium mb-1">Alpa (Tanpa Keterangan)</div>
                    <div class="text-2xl font-bold text-rose-600" id="statAlpa">-</div>
                </div>
                <div class="w-10 h-10 rounded-full bg-rose-50 flex items-center justify-center text-rose-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Riwayat Terakhir -->
        <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4">5 Absensi Terakhir</h4>
        <div class="space-y-3" id="sidebarHistory">
            <!-- Loading Skeleton -->
            <div class="animate-pulse flex items-center gap-3 p-3 bg-white rounded-xl border border-slate-100">
                <div class="w-10 h-10 bg-slate-100 rounded-lg"></div>
                <div class="flex-1 space-y-2">
                    <div class="h-3 bg-slate-100 rounded w-1/2"></div>
                    <div class="h-2 bg-slate-100 rounded w-1/4"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="p-4 bg-white border-t border-slate-100 text-center">
        <p class="text-xs text-slate-400">Data ditampilkan berdasarkan perhitungan real-time sistem.</p>
    </div>
</div>

<style>
/* Custom scrollbar untuk tabel & sidebar */
.overflow-x-auto::-webkit-scrollbar,
.overflow-y-auto::-webkit-scrollbar {
    width: 6px;
    height: 6px;
}

.overflow-x-auto::-webkit-scrollbar-track,
.overflow-y-auto::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 3px;
}

.overflow-x-auto::-webkit-scrollbar-thumb,
.overflow-y-auto::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 3px;
}
</style>

<script>
    function openStudentSidebar(userId) {
        const sidebar = document.getElementById('studentSidebar');
        const overlay = document.getElementById('studentSidebarOverlay');
        const historyContainer = document.getElementById('sidebarHistory');

        // 1. Reset Content to Loading State (UI Bersih dulu)
        document.getElementById('sidebarName').innerText = 'Memuat Data...';
        document.getElementById('sidebarNis').innerText = 'NIS: ...';
        document.getElementById('sidebarClass').innerText = '...';
        document.getElementById('statHadir').innerText = '-';
        document.getElementById('statIzin').innerText = '-';
        document.getElementById('statSakit').innerText = '-';
        document.getElementById('statAlpa').innerText = '-';

        // Reset Foto
        document.getElementById('sidebarPhoto').classList.add('hidden');
        document.getElementById('sidebarInitial').classList.remove('hidden');
        document.getElementById('sidebarInitial').innerText = '?';

        // Skeleton Loading untuk history
        historyContainer.innerHTML = `
            <div class="flex items-center gap-3 p-4 bg-white rounded-xl border border-slate-100 text-slate-400 justify-center">
                <svg class="animate-spin h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Mengambil data...
            </div>
        `;

        // 2. Tampilkan Sidebar (Animasi masuk)
        overlay.classList.remove('hidden');
        // Force reflow sedikit agar transisi opacity jalan
        void overlay.offsetWidth;
        overlay.classList.remove('opacity-0');
        sidebar.classList.remove('translate-x-full');

        // 3. Ambil Data dari Server (AJAX)
        // Pastikan route ini sesuai dengan web.php: attendances/{student}/detail
        fetch(`/admin/attendances/${userId}/detail`)
            .then(response => {
                if (!response.ok) throw new Error('Gagal mengambil data');
                return response.json();
            })
            .then(data => {
                // Populate Header
                document.getElementById('sidebarName').innerText = data.name;
                document.getElementById('sidebarNis').innerText = 'NIS: ' + data.nis;
                document.getElementById('sidebarClass').innerText = data.class;

                // Handle Foto
                if (data.photo) {
                    const img = document.getElementById('sidebarPhoto');
                    img.src = data.photo;
                    img.classList.remove('hidden');
                    document.getElementById('sidebarInitial').classList.add('hidden');
                } else {
                    document.getElementById('sidebarInitial').innerText = data.name.charAt(0).toUpperCase();
                    document.getElementById('sidebarPhoto').classList.add('hidden');
                    document.getElementById('sidebarInitial').classList.remove('hidden');
                }

                // Populate Stats
                document.getElementById('statHadir').innerText = data.stats.hadir;
                document.getElementById('statIzin').innerText = data.stats.izin;
                document.getElementById('statSakit').innerText = data.stats.sakit;
                document.getElementById('statAlpa').innerText = data.stats.alpa;

                // Populate History
                historyContainer.innerHTML = '';
                if (data.history.length === 0) {
                    historyContainer.innerHTML = '<div class="text-center py-8 text-slate-400 text-sm bg-white rounded-xl border border-dashed border-slate-200">Belum ada riwayat absensi.</div>';
                } else {
                    data.history.forEach(item => {
                        const html = `
                            <div class="flex items-center justify-between p-3 bg-white rounded-xl border border-slate-100 hover:shadow-sm transition-shadow">
                                <div class="flex items-center gap-3">
                                    <div class="flex flex-col items-center justify-center w-12 h-12 rounded-lg bg-slate-50 border border-slate-100">
                                        <span class="text-xs font-bold text-slate-500">${item.date.split(' ')[0]}</span>
                                        <span class="text-[10px] uppercase text-slate-400 font-bold">${item.date.split(' ')[1].substring(0,3)}</span>
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-slate-700">${item.status === 'Masuk' ? 'Absen Masuk' : 'Absen Pulang'}</div>
                                        <div class="text-xs text-slate-400 font-medium">${item.time} WIB</div>
                                    </div>
                                </div>
                                <span class="px-2 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider ${item.color.replace('text-', 'text-').replace('bg-', 'bg-')}">
                                    ${item.status === 'Masuk' ? 'IN' : 'OUT'}
                                </span>
                            </div>
                        `;
                        historyContainer.insertAdjacentHTML('beforeend', html);
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                historyContainer.innerHTML = `
                    <div class="text-center py-4 text-rose-500 text-sm bg-rose-50 rounded-xl border border-rose-100">
                        <p class="font-bold">Gagal memuat data.</p>
                        <p class="text-xs mt-1">Periksa koneksi internet Anda.</p>
                    </div>
                `;
            });
    }

    function closeStudentSidebar() {
        const sidebar = document.getElementById('studentSidebar');
        const overlay = document.getElementById('studentSidebarOverlay');

        // Animasi keluar
        sidebar.classList.add('translate-x-full');
        overlay.classList.add('opacity-0');

        // Sembunyikan elemen setelah animasi selesai (300ms)
        setTimeout(() => {
            overlay.classList.add('hidden');
        }, 300);
    }
</script>
@endsection
