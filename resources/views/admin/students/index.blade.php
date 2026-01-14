@extends('layouts.admin')

@section('header', 'Data Siswa')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50/30">
    <!-- Header Section with Glass Effect -->
    <div class="relative mb-8">
        <div class="absolute inset-0 bg-gradient-to-r from-indigo-600/10 to-purple-600/10 rounded-3xl"></div>
        <div class="relative bg-white/60 backdrop-blur-sm border border-white/20 rounded-3xl p-8 shadow-xl shadow-indigo-500/5">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
                <div>
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent mb-2">
                        Manajemen Data Siswa
                    </h1>
                    <p class="text-slate-600 font-medium">Kelola dan pantau data siswa dengan mudah</p>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.students.import.show') }}"
                       class="group relative inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-emerald-600 to-emerald-700 text-white rounded-2xl font-semibold shadow-lg shadow-emerald-500/25 hover:shadow-emerald-500/40 hover:scale-[1.02] transition-all duration-300 focus:ring-4 focus:ring-emerald-500/30">
                        <span class="absolute inset-0 bg-white/20 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                        <svg class="w-5 h-5 transition-transform duration-300 group-hover:-translate-y-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                        </svg>
                        <span class="relative">Import</span>
                    </a>

                    <a href="{{ route('admin.students.create') }}"
                       class="group relative inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-2xl font-semibold shadow-lg shadow-indigo-500/25 hover:shadow-indigo-500/40 hover:scale-[1.02] transition-all duration-300 focus:ring-4 focus:ring-indigo-500/30">
                        <span class="absolute inset-0 bg-white/20 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                        <svg class="w-5 h-5 transition-transform duration-300 group-hover:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span class="relative">Tambah Siswa</span>
                    </a>

                    <!-- Tombol Reset Data -->
                    <form action="{{ route('admin.students.destroyAll') }}" method="POST" class="inline-block" onsubmit="return confirmDeleteAll()">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="group relative inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-red-600 to-rose-600 text-white rounded-2xl font-semibold shadow-lg shadow-red-500/25 hover:shadow-red-500/40 hover:scale-[1.02] transition-all duration-300 focus:ring-4 focus:ring-red-500/30">
                            <span class="absolute inset-0 bg-white/20 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                            <svg class="w-5 h-5 transition-transform duration-300 group-hover:rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            <span class="relative">Reset Data</span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Alert Section -->
            @if(session('success'))
                <div class="mt-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl flex items-center gap-3 animate-fade-in-down">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="mt-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-2xl flex items-center gap-3 animate-fade-in-down">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="font-medium">{{ session('error') }}</span>
                </div>
            @endif

            <!-- Search and Filter Section -->
            <div class="mt-8 bg-white/50 rounded-2xl p-6 border border-white/40">
                <form action="{{ route('admin.students.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-4">
                    <!-- Search Input -->
                    <div class="md:col-span-6 lg:col-span-5 relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-slate-400 group-focus-within:text-indigo-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input type="text" name="search_name" value="{{ $searchName }}"
                               placeholder="Cari nama atau NIS..."
                               class="w-full pl-11 pr-4 py-3 bg-white border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all duration-300 placeholder-slate-400 font-medium">
                    </div>

                    <!-- Grade Filter -->
                    <div class="md:col-span-3 lg:col-span-2">
                        <select name="filter_grade" onchange="this.form.submit()"
                                class="w-full px-4 py-3 bg-white border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all duration-300 font-medium text-slate-600 appearance-none">
                            <option value="">Semua Kelas</option>
                            @foreach($grades as $grade)
                                <option value="{{ $grade }}" {{ $filterGrade == $grade ? 'selected' : '' }}>
                                    Kelas {{ $grade }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Major Filter -->
                    <div class="md:col-span-3 lg:col-span-3">
                        <select name="filter_major" onchange="this.form.submit()"
                                class="w-full px-4 py-3 bg-white border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all duration-300 font-medium text-slate-600 appearance-none">
                            <option value="">Semua Jurusan</option>
                            @foreach($majors as $major)
                                <option value="{{ $major }}" {{ $filterMajor == $major ? 'selected' : '' }}>
                                    {{ $major }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Reset Filter Button -->
                    <div class="md:col-span-12 lg:col-span-2 flex justify-end md:justify-start lg:justify-end">
                         @if($searchName || $filterGrade || $filterMajor)
                            <a href="{{ route('admin.students.index') }}" class="inline-flex items-center justify-center w-full px-4 py-3 text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl font-medium transition-colors border-2 border-slate-200">
                                Reset Filter
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white/60 backdrop-blur-sm border border-white/20 rounded-3xl overflow-hidden shadow-xl shadow-indigo-500/5">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gradient-to-r from-slate-50 to-slate-100 border-b border-slate-200">
                        <th class="px-8 py-5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Siswa</th>
                        <th class="px-8 py-5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">NIS</th>
                        <th class="px-8 py-5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Kelas & Jurusan</th>
                        <th class="px-8 py-5 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($students as $student)
                        <tr class="hover:bg-indigo-50/30 transition-colors duration-300 group">
                            <td class="px-8 py-5">
                                <div class="flex items-center">
                                    <div class="relative">
                                        <div class="h-12 w-12 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 p-[2px] shadow-lg shadow-indigo-500/20 group-hover:scale-110 transition-transform duration-300">
                                            <!-- PERBAIKAN LOGIKA FOTO DI SINI -->
                                            @php
                                                // Bersihkan path foto dari 'public/' jika ada, karena storage link sudah mapping ke public
                                                $photoPath = $student->photo ? str_replace('public/', '', $student->photo) : null;
                                            @endphp

                                            @if($photoPath && Storage::disk('public')->exists($photoPath))
                                                <img class="h-full w-full rounded-xl object-cover border-2 border-white"
                                                     src="{{ asset('storage/' . $photoPath) }}"
                                                     alt="{{ $student->name }}"
                                                     onerror="this.onerror=null;this.src='https://ui-avatars.com/api/?name={{ urlencode($student->name) }}&background=random';">
                                            @else
                                                <div class="h-full w-full rounded-xl bg-white flex items-center justify-center">
                                                    <span class="text-indigo-600 font-bold text-lg">{{ substr($student->name, 0, 1) }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-bold text-slate-800">{{ $student->name }}</div>
                                        <div class="text-xs text-slate-500 font-medium">{{ $student->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-5">
                                <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                                    {{ $student->nis }}
                                </span>
                            </td>
                            <td class="px-8 py-5">
                                <div class="flex flex-col gap-1">
                                    @php
                                        // Memisahkan kelas dan jurusan
                                        $parts = explode(' ', $student->class, 2);
                                        $grade = $parts[0] ?? '';
                                        $major = $parts[1] ?? $student->class;
                                    @endphp
                                    <span class="text-sm font-bold text-slate-700">{{ $grade }}</span>
                                    <span class="text-xs text-slate-500 font-medium">{{ $major }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-5">
                                <div class="flex items-center justify-center gap-2 opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-x-2 group-hover:translate-x-0">
                                    <a href="{{ route('admin.students.edit', $student) }}" class="p-2 bg-amber-50 text-amber-600 rounded-xl hover:bg-amber-100 hover:scale-110 transition-all duration-300 border border-amber-200" title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.students.destroy', $student) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data siswa ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 bg-red-50 text-red-600 rounded-xl hover:bg-red-100 hover:scale-110 transition-all duration-300 border border-red-200" title="Hapus">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-8 py-16 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="h-24 w-24 bg-slate-50 rounded-full flex items-center justify-center mb-4 border-2 border-slate-100">
                                        <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-bold text-slate-800 mb-1">Belum ada data siswa</h3>
                                    <p class="text-slate-500">Silakan tambahkan data manual atau import dari Excel</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination with Custom Design -->
        @if($students->hasPages())
            <div class="bg-gradient-to-r from-slate-50 to-slate-100 px-8 py-6 border-t border-slate-200">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-slate-600">
                        Menampilkan <span class="font-semibold">{{ $students->firstItem() }}</span> sampai <span class="font-semibold">{{ $students->lastItem() }}</span> dari <span class="font-semibold">{{ $students->total() }}</span> data siswa
                    </div>
                    <div class="pagination-wrapper">
                        {{ $students->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<style>
    .pagination-wrapper .pagination {
        @apply flex items-center gap-2;
    }

    .pagination-wrapper .page-link {
        @apply px-4 py-2 rounded-xl border-2 border-slate-200 bg-white text-slate-700 font-semibold hover:bg-indigo-500 hover:text-white hover:border-indigo-500 transition-all duration-300;
    }

    .pagination-wrapper .page-item.active .page-link {
        @apply bg-gradient-to-r from-indigo-600 to-purple-600 border-indigo-600 text-white shadow-lg shadow-indigo-500/25;
    }

    .pagination-wrapper .page-item.disabled .page-link {
        @apply bg-slate-100 text-slate-400 border-slate-200 cursor-not-allowed;
    }
</style>

<script>
function confirmDeleteAll() {
    if (confirm('⚠️ PERINGATAN!\n\nApakah Anda yakin ingin menghapus SEMUA data siswa?\n\nTindakan ini tidak dapat dibatalkan dan akan menghapus seluruh data siswa beserta foto-foto mereka.\n\nKetik "HAPUS SEMUA" untuk melanjutkan.')) {
        const userInput = prompt('Ketik "HAPUS SEMUA" (tanpa tanda kutip) untuk mengkonfirmasi:');
        if (userInput === 'HAPUS SEMUA') {
            return true;
        } else {
            alert('Konfirmasi salah. Penghapusan dibatalkan.');
            return false;
        }
    }
    return false;
}
</script>
@endsection
