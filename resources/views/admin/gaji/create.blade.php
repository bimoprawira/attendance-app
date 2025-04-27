@extends('layouts.app')

@section('title', 'Tambah Gaji')

@section('content')
<style>
    body {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    }
    .section-title { font-size: 1.5rem; }
    .form-label { font-size: 1.1rem; }
    .form-input { font-size: 1.1rem; }
</style>
<div class="flex justify-center items-start min-h-screen py-8">
    <div class="w-full max-w-3xl bg-white/90 rounded-3xl shadow-2xl p-10">
        <h2 class="section-title font-bold text-blue-700 mb-8">Tambah Gaji</h2>
        <form action="{{ route('admin.gaji.store') }}" method="POST" class="bg-white p-8 rounded-2xl shadow-lg">
        @csrf
            <div class="mb-6">
                <label for="employee_id" class="form-label block font-medium text-gray-700 mb-2">Karyawan</label>
                <select name="employee_id" id="employee_id" class="form-input mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-3" required>
                @foreach($karyawans as $employee)
                    <option value="{{ $employee->employee_id }}">{{ $employee->name }}</option>
                @endforeach
            </select>
        </div>
            <div class="mb-6">
                <label for="periode_bayar" class="form-label block font-medium text-gray-700 mb-2">Periode Bayar</label>
            <input type="text" name="periode_bayar" id="periode_bayar" placeholder="Contoh: April 2025"
                       class="form-input mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-3" required>
        </div>
            <div class="mb-6">
                <label for="gaji_pokok" class="form-label block font-medium text-gray-700 mb-2">Gaji Pokok</label>
            <input type="number" name="gaji_pokok" id="gaji_pokok"
                       class="form-input mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-3" required>
        </div>
            <div class="mb-6">
                <label for="potongan" class="form-label block font-medium text-gray-700 mb-2">Potongan</label>
            <input type="number" name="potongan" id="potongan"
                       class="form-input mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-3">
        </div>
            <div class="mb-6">
                <label for="komponen_tambahan" class="form-label block font-medium text-gray-700 mb-2">Tambahan</label>
            <input type="number" name="komponen_tambahan" id="komponen_tambahan"
                       class="form-input mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-3">
        </div>
            <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-lg text-lg font-semibold hover:bg-blue-700 transition">
            Simpan
        </button>
    </form>
    </div>
</div>
@endsection
