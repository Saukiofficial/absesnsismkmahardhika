@extends('layouts.admin')

@section('header', $pageTitle)

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 py-8 px-4">
    <div class="max-w-4xl mx-auto">

        <!-- Header -->
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-slate-800 mb-2">{{ $pageTitle }}</h1>
                <p class="text-slate-600">Impor data siswa, wali, dan kartu RFID dalam satu langkah.</p>
            </div>
            <a href="{{ route('admin.students.index') }}" class="group flex items-center gap-2 px-4 py-2 text-slate-600 hover:text-indigo-600 transition-all duration-200 rounded-lg hover:bg-white/50">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span class="font-medium">Kembali</span>
            </a>
        </div>

        <!-- Alert Messages (Menampilkan Error/Success dengan Aman) -->
        @if(session('success'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg shadow-sm animate-fade-in-down">
            <div class="flex">
                <div class="flex-shrink-0"><svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg></div>
                <div class="ml-3"><p class="text-sm text-green-700 font-medium">{{ session('success') }}</p></div>
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg shadow-sm animate-fade-in-down">
            <div class="flex">
                <div class="flex-shrink-0"><svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg></div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">Gagal Mengimpor:</h3>
                    <!-- Menggunakan {!! !!} agar baris baru (<br>) dari Controller terbaca -->
                    <div class="mt-2 text-sm text-red-700">{!! session('error') !!}</div>
                </div>
            </div>
        </div>
        @endif

        @if($errors->any())
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg shadow-sm">
            <ul class="list-disc list-inside text-sm text-red-700">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- Upload Card -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-slate-100">
            <div class="p-8">
                <!-- Template Download Section -->
                <div class="mb-8 p-6 bg-slate-50 rounded-xl border border-slate-200 border-dashed">
                    <div class="flex items-center justify-between flex-wrap gap-4">
                        <div class="flex items-center gap-4">
                            <div class="p-3 bg-indigo-100 text-indigo-600 rounded-lg">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-slate-800">Template Excel</h3>
                                <p class="text-slate-500 text-sm">Gunakan template ini untuk menghindari kesalahan format.</p>
                            </div>
                        </div>
                        <a href="{{ route('admin.students.download-template') }}" class="inline-flex items-center px-4 py-2 bg-white border border-slate-300 rounded-lg text-sm font-medium text-slate-700 hover:bg-slate-50 hover:text-indigo-600 transition-colors shadow-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4-4m0 0l-4 4m4-4v12"/></svg>
                            Download Template
                        </a>
                    </div>
                </div>

                <!-- Form Upload -->
                <form action="{{ route('admin.students.import.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6" onsubmit="showLoading()">
                    @csrf

                    <div class="relative group">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Upload File Excel/CSV</label>
                        <div class="relative border-2 border-dashed border-slate-300 rounded-xl p-8 transition-all duration-300 hover:border-indigo-500 hover:bg-indigo-50/30 text-center cursor-pointer" onclick="document.getElementById('file-upload').click()">
                            <input type="file" name="file" id="file-upload" class="hidden" accept=".xlsx, .xls, .csv" onchange="updateFileName(this)" required>

                            <div class="space-y-4">
                                <div class="mx-auto w-16 h-16 bg-indigo-50 text-indigo-500 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                                </div>
                                <div>
                                    <p class="text-lg font-medium text-slate-700" id="file-text">Klik untuk memilih file</p>
                                    <p class="text-sm text-slate-400 mt-1">Format: .xlsx, .xls, .csv</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit" id="btn-submit" class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white px-8 py-4 rounded-xl font-semibold text-lg transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 flex items-center justify-center gap-3">
                            <span id="btn-text">Import Data Siswa</span>
                            <span id="btn-loader" class="hidden">
                                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Loading Overlay (Hanya muncul saat submit, dengan z-index tinggi) -->
        <div id="loading-overlay" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden z-50 items-center justify-center backdrop-blur-sm">
            <div class="bg-white p-6 rounded-2xl shadow-2xl flex flex-col items-center">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600 mb-4"></div>
                <h3 class="text-lg font-bold text-gray-800">Sedang Memproses...</h3>
                <p class="text-sm text-gray-500">Mohon jangan tutup halaman ini.</p>
            </div>
        </div>

    </div>
</div>

<script>
function updateFileName(input) {
    const fileText = document.getElementById('file-text');
    if (input.files && input.files[0]) {
        fileText.textContent = input.files[0].name;
        fileText.classList.add('text-indigo-600');
    } else {
        fileText.textContent = 'Klik untuk memilih file';
        fileText.classList.remove('text-indigo-600');
    }
}

function showLoading() {
    // Tampilkan overlay loading
    document.getElementById('loading-overlay').classList.remove('hidden');
    document.getElementById('loading-overlay').classList.add('flex');

    // Ubah tombol menjadi loading state
    const btn = document.getElementById('btn-submit');
    const txt = document.getElementById('btn-text');
    const loader = document.getElementById('btn-loader');

    btn.classList.add('opacity-75', 'cursor-not-allowed');
    txt.textContent = 'Memproses...';
    loader.classList.remove('hidden');
}
</script>
@endsection
