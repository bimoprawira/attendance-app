@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container mx-auto px-6 py-7">
    <div class="max-w-[1200px] mx-auto bg-white rounded-lg shadow-lg p-7">
        <h1 class="text-2xl font-bold text-gray-800 mb-3">Dashboard Karyawan</h1>
        <p class="text-base text-gray-600 mb-8">Selamat datang kembali, {{ auth()->user()->name }}!</p>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-7">
            <!-- Bagian Kehadiran -->
            <div class="bg-blue-50 rounded-lg p-[1.4rem]">
                <h2 class="text-lg font-semibold text-blue-700 mb-4">Kehadiran</h2>
                <div class="space-y-4">
                    <a href="{{ route('presence.index') }}"
                        class="block w-full bg-blue-500 hover:bg-blue-600 text-white text-center py-[0.7rem] px-[1.4rem] rounded text-base">
                        Cek Kehadiran
                    </a>
                    <a href="{{ route('presence.history') }}"
                        class="block w-full bg-blue-100 hover:bg-blue-200 text-blue-700 text-center py-[0.7rem] px-[1.4rem] rounded text-base">
                        Lihat Riwayat
                    </a>
                </div>
            </div>

            <!-- Bagian Manajemen Cuti -->
            <div class="bg-green-50 rounded-lg p-[1.4rem]">
                <h2 class="text-lg font-semibold text-green-700 mb-4">Manajemen Cuti</h2>
                <div class="space-y-4">
                    <a href="{{ route('leaves.create') }}"
                        class="block w-full bg-green-500 hover:bg-green-600 text-white text-center py-[0.7rem] px-[1.4rem] rounded text-base">
                        Ajukan Cuti
                    </a>
                    <a href="{{ route('leaves.index') }}"
                        class="block w-full bg-green-100 hover:bg-green-200 text-green-700 text-center py-[0.7rem] px-[1.4rem] rounded text-base">
                        Lihat Pengajuan
                    </a>
                </div>
            </div>

            <!-- Bagian Informasi Profil -->
            <div class="bg-purple-50 rounded-lg p-[1.4rem]">
                <h2 class="text-lg font-semibold text-purple-700 mb-4">Informasi Profil</h2>
                <div class="space-y-3">
                    <p class="text-gray-700 text-base">
                        <span class="font-medium">Jabatan:</span> {{ auth()->user()->position }}
                    </p>
                    <p class="text-gray-700 text-base">
                        <span class="font-medium">Tanggal Bergabung:</span> {{ auth()->user()->date_joined->format('Y-m-d') }}
                    </p>
                    <p class="text-gray-700 text-base">
                        <span class="font-medium">Email:</span> {{ auth()->user()->email }}
                    </p>
                </div>
            </div>

            <!-- Bagian Penggajian -->
            <div class="bg-yellow-50 rounded-lg p-[1.4rem]">
                <h2 class="text-lg font-semibold text-yellow-700 mb-4">Penggajian</h2>
                <div class="space-y-4">
                    <a href="{{ route('gaji.index') }}"
                        class="block w-full bg-yellow-500 hover:bg-yellow-600 text-white text-center py-[0.7rem] px-[1.4rem] rounded text-base">
                        Lihat Riwayat Gaji
                    </a>
                    <a href="{{ route('gaji.export') }}"
                        class="block w-full bg-yellow-100 hover:bg-yellow-200 text-yellow-700 text-center py-[0.7rem] px-[1.4rem] rounded text-base">
                        Unduh Riwayat Gaji (Excel)
                    </a>
                </div>
            </div>
        </div>

        <!-- Tindakan Cepat -->
        <div class="mt-7">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Tindakan Cepat</h2>
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit"
                    class="bg-red-500 hover:bg-red-600 text-white font-medium py-[0.7rem] px-[1.8rem] rounded text-base">
                    Keluar
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
