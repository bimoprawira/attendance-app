@extends('layouts.app')

@section('title', 'Tambah Gaji')

@section('content')
<div class="max-w-3xl mx-auto py-8">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Tambah Gaji</h2>

    <form action="{{ route('admin.gaji.store') }}" method="POST" class="bg-white p-6 rounded-lg shadow">
        @csrf

        <div class="mb-4">
            <label for="employee_id" class="block text-sm font-medium text-gray-700">Karyawan</label>
            <select name="employee_id" id="employee_id" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                @foreach($karyawans as $employee)
                    <option value="{{ $employee->employee_id }}">{{ $employee->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label for="periode_bayar" class="block text-sm font-medium text-gray-700">Periode Bayar</label>
            <input type="text" name="periode_bayar" id="periode_bayar" placeholder="Contoh: April 2025"
                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" required>
        </div>

        <div class="mb-4">
            <label for="gaji_pokok" class="block text-sm font-medium text-gray-700">Gaji Pokok</label>
            <input type="number" name="gaji_pokok" id="gaji_pokok"
                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" required>
        </div>

        <div class="mb-4">
            <label for="potongan" class="block text-sm font-medium text-gray-700">Potongan</label>
            <input type="number" name="potongan" id="potongan"
                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
        </div>

        <div class="mb-4">
            <label for="komponen_tambahan" class="block text-sm font-medium text-gray-700">Tambahan</label>
            <input type="number" name="komponen_tambahan" id="komponen_tambahan"
                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Simpan
        </button>
    </form>
</div>
@endsection
