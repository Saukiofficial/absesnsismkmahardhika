@extends('guardian.layouts.app')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Kolom Kiri: Informasi Wali -->
    <div class="lg:col-span-1">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-xl font-bold text-gray-800 mb-4 border-b pb-3">Data Wali Murid</h3>
            <div class="space-y-3">
                <div class="flex justify-between"><span class="text-gray-500">Nama:</span> <span class="font-semibold text-gray-800">{{ $guardian->name }}</span></div>
                <div class="flex justify-between"><span class="text-gray-500">No. WhatsApp:</span> <span class="font-semibold text-gray-800">{{ $guardian->guardian_phone }}</span></div>
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Ganti Password -->
    <div class="lg:col-span-2">
        <div class="bg-white p-8 rounded-lg shadow-md">
            <h3 class="text-xl font-bold text-gray-800 mb-6">Ganti Password</h3>

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-md text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('guardian.profile.password.update') }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label for="current_password" class="block text-sm font-medium text-gray-700">Password Saat Ini</label>
                    <input type="password" name="current_password" id="current_password" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm">
                    @error('current_password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password Baru</label>
                    <input type="password" name="password" id="password" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm">
                    @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm">
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full bg-teal-600 text-white py-2 px-4 rounded-md shadow-sm hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500">
                        Perbarui Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
