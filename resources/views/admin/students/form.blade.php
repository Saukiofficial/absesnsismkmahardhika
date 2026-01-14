@extends('layouts.admin')

@section('header', $pageTitle)

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 py-12 px-4 relative overflow-hidden">
    <!-- Background Pattern -->
    <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%236366f1\' fill-opacity=\'0.03\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-30 pointer-events-none"></div>

    <div class="max-w-5xl mx-auto relative z-10">
        <!-- Header -->
        <div class="mb-10 text-center">
            <h1 class="text-4xl font-extrabold text-slate-800 tracking-tight mb-2">{{ $pageTitle }}</h1>
            <p class="text-slate-600 font-medium text-lg">
                {{ $isEdit ? 'Perbarui informasi lengkap siswa di bawah ini' : 'Lengkapi formulir untuk menambahkan siswa baru' }}
            </p>
        </div>

        <!-- Error Alert -->
        @if(session('error'))
            <div class="mb-8 p-4 bg-rose-50 border-l-4 border-rose-500 text-rose-700 rounded-r-xl shadow-sm flex items-center gap-4 animate-pulse">
                <div class="p-2 bg-rose-100 rounded-full">
                    <svg class="w-6 h-6 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold">Terjadi Kesalahan</h3>
                    <p class="text-sm">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <!-- Form Card -->
        <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl shadow-indigo-100/50 border border-white/50 overflow-hidden">
            <div class="h-2 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500"></div>

            <form action="{{ $isEdit ? route('admin.students.update', $student->id) : route('admin.students.store') }}" method="POST" enctype="multipart/form-data" class="p-8 md:p-10">
                @csrf
                @if($isEdit)
                    @method('PUT')
                @endif

                <div class="space-y-10">
                    <!-- Foto Profil Section -->
                    <div class="flex flex-col md:flex-row items-start gap-8 p-6 bg-slate-50/50 rounded-2xl border border-slate-100">
                        <div class="relative group mx-auto md:mx-0">
                            <div class="w-32 h-32 rounded-full bg-white border-4 border-white shadow-xl flex items-center justify-center overflow-hidden relative">
                                @if($student->photo)
                                    <img id="preview-photo" src="{{ asset('storage/' . $student->photo) }}" class="w-full h-full object-cover">
                                @else
                                    <div id="placeholder-icon" class="text-indigo-200 {{ $student->photo ? 'hidden' : '' }}">
                                        <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <img id="preview-photo" class="hidden w-full h-full object-cover">
                                @endif

                                <!-- Overlay Hover -->
                                <div class="absolute inset-0 bg-black/30 hidden group-hover:flex items-center justify-center transition-all">
                                    <span class="text-white text-xs font-bold">Ubah</span>
                                </div>
                            </div>

                            <!-- Upload Button -->
                            <label for="photo" class="absolute bottom-0 right-0 p-2.5 bg-indigo-600 text-white rounded-full shadow-lg cursor-pointer hover:bg-indigo-700 hover:scale-110 transition-all duration-200 z-10">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </label>
                            <input type="file" id="photo" name="photo" class="hidden" accept="image/*" onchange="previewImage(this)">
                        </div>

                        <div class="flex-1 text-center md:text-left">
                            <h3 class="text-xl font-bold text-slate-800 mb-1">Foto Profil</h3>
                            <p class="text-slate-500 text-sm mb-3">Unggah foto siswa dengan format JPG/PNG. Maksimal ukuran file 10MB.</p>
                            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-50 text-indigo-600 text-xs font-bold">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Disarankan rasio 1:1 (Persegi)
                            </div>
                        </div>
                    </div>

                    <!-- Grid Layout for Inputs -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Informasi Pribadi -->
                        <div class="space-y-6">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="p-2 bg-indigo-100 text-indigo-600 rounded-lg">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                </div>
                                <h3 class="text-lg font-bold text-slate-800">Informasi Pribadi</h3>
                            </div>

                            <div class="group">
                                <label class="block text-sm font-bold text-slate-700 mb-2">Nama Lengkap <span class="text-rose-500">*</span></label>
                                <input type="text" name="name" value="{{ old('name', $student->name) }}" class="w-full px-4 py-3 bg-white border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all font-medium placeholder:text-slate-300" placeholder="Contoh: Ahmad Dahlan" required>
                                @error('name') <span class="text-xs text-rose-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="group">
                                <label class="block text-sm font-bold text-slate-700 mb-2">NIS <span class="text-rose-500">*</span></label>
                                <input type="text" name="nis" value="{{ old('nis', $student->nis) }}" class="w-full px-4 py-3 bg-white border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all font-medium placeholder:text-slate-300" placeholder="Nomor Induk Siswa" required>
                                @error('nis') <span class="text-xs text-rose-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="group">
                                <label class="block text-sm font-bold text-slate-700 mb-2">Email <span class="text-rose-500">*</span></label>
                                <input type="email" name="email" value="{{ old('email', $student->email) }}" class="w-full px-4 py-3 bg-white border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all font-medium placeholder:text-slate-300" placeholder="email@sekolah.sch.id" required>
                                @error('email') <span class="text-xs text-rose-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Informasi Akademik -->
                        <div class="space-y-6">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="p-2 bg-purple-100 text-purple-600 rounded-lg">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                </div>
                                <h3 class="text-lg font-bold text-slate-800">Akademik & Akun</h3>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div class="group">
                                    <label class="block text-sm font-bold text-slate-700 mb-2">Tingkat <span class="text-rose-500">*</span></label>
                                    <select name="grade" class="w-full px-4 py-3 bg-white border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all font-medium appearance-none" required>
                                        <option value="">Pilih</option>
                                        @foreach($grades as $grade)
                                            <option value="{{ $grade }}" {{ old('grade', $currentGrade) == $grade ? 'selected' : '' }}>{{ $grade }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="group">
                                    <label class="block text-sm font-bold text-slate-700 mb-2">Jurusan <span class="text-rose-500">*</span></label>
                                    <select name="major" class="w-full px-4 py-3 bg-white border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all font-medium appearance-none" required>
                                        <option value="">Pilih Jurusan</option>
                                        @foreach($majors as $major)
                                            <option value="{{ $major }}" {{ old('major', $currentMajor) == $major ? 'selected' : '' }}>{{ $major }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @error('grade') <span class="text-xs text-rose-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                            @error('major') <span class="text-xs text-rose-500 font-bold mt-1 block">{{ $message }}</span> @enderror

                            <div class="group">
                                <label class="block text-sm font-bold text-slate-700 mb-2">Wali Murid <span class="text-rose-500">*</span></label>
                                <select name="guardian_id" class="w-full px-4 py-3 bg-white border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all font-medium" required>
                                    <option value="">Pilih Wali Murid</option>
                                    @foreach($guardians as $guardian)
                                        <option value="{{ $guardian->id }}" {{ old('guardian_id', $student->guardian_id) == $guardian->id ? 'selected' : '' }}>
                                            {{ $guardian->name }} ({{ $guardian->guardian_phone ?? '-' }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('guardian_id') <span class="text-xs text-rose-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="group">
                                <label class="block text-sm font-bold text-slate-700 mb-2">ID Kartu (RFID)</label>
                                <div class="relative">
                                    <input type="text" name="card_uid" value="{{ old('card_uid', $student->card_uid) }}" class="w-full pl-10 pr-4 py-3 bg-white border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all font-medium placeholder:text-slate-300" placeholder="Tempelkan kartu...">
                                    <div class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                    </div>
                                </div>
                                @error('card_uid') <span class="text-xs text-rose-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Keamanan Section -->
                    <div class="p-6 bg-slate-50/80 rounded-2xl border border-slate-200">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="p-2 bg-emerald-100 text-emerald-600 rounded-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            </div>
                            <h3 class="text-lg font-bold text-slate-800">Keamanan Akun</h3>
                        </div>

                        @if($isEdit)
                            <div class="mb-4 flex items-start gap-3 p-3 bg-amber-50 text-amber-700 rounded-lg border border-amber-100">
                                <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <p class="text-sm">Biarkan kolom password kosong jika tidak ingin mengubah password saat ini.</p>
                            </div>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="group">
                                <label class="block text-sm font-bold text-slate-700 mb-2">Password {{ $isEdit ? 'Baru' : '' }}</label>
                                <input type="password" name="password" class="w-full px-4 py-3 bg-white border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all font-medium" {{ !$isEdit ? 'required' : '' }}>
                                @error('password') <span class="text-xs text-rose-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="group">
                                <label class="block text-sm font-bold text-slate-700 mb-2">Konfirmasi Password</label>
                                <input type="password" name="password_confirmation" class="w-full px-4 py-3 bg-white border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all font-medium" {{ !$isEdit ? 'required' : '' }}>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-10 pt-6 border-t border-slate-100 flex items-center justify-end gap-4">
                    <a href="{{ route('admin.students.index') }}" class="px-6 py-3 bg-white text-slate-600 font-bold rounded-xl border-2 border-slate-200 hover:bg-slate-50 hover:border-slate-300 transition-all">
                        Batal
                    </a>
                    <button type="submit" class="group relative px-8 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-bold rounded-xl shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/50 hover:scale-[1.02] transition-all duration-200">
                        <span class="relative z-10 flex items-center gap-2">
                            <svg class="w-5 h-5 group-hover:animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            {{ $isEdit ? 'Simpan Perubahan' : 'Simpan Data Siswa' }}
                        </span>
                        <div class="absolute inset-0 rounded-xl bg-gradient-to-r from-indigo-500 to-purple-500 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var img = document.getElementById('preview-photo');
                var placeholder = document.getElementById('placeholder-icon');

                img.src = e.target.result;
                img.classList.remove('hidden');

                if(placeholder) {
                    placeholder.classList.add('hidden');
                }
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

<style>
    /* Animasi Shake untuk Error */
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }
    .animate-shake {
        animation: shake 0.5s cubic-bezier(.36,.07,.19,.97) both;
    }
</style>
@endsection
