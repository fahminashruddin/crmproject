@extends('manajemen.layout')

@section('title', 'Dashboard Manajemen')

@section('content')
    <div class="mb-6">
        <h1 class="text-3xl font-extrabold">Dashboard Manajemen</h1>
        <p class="text-sm text-gray-500 mt-1">Laporan dan analisis operasional</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Pesanan</p>
                    <p class="mt-2 text-2xl font-semibold text-gray-900">0</p>
                </div>
                <div class="w-10 h-10 rounded-md border border-purple-200 bg-purple-50 flex items-center justify-center">
                    <i data-lucide="shopping-cart" class="w-6 h-6 text-purple-400"></i>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Pesanan Selesai</p>
                    <p class="mt-2 text-2xl font-semibold text-gray-900">0</p>
                </div>
                <div class="w-10 h-10 rounded-md border border-green-200 bg-green-50 flex items-center justify-center">
                    <i data-lucide="check" class="w-6 h-6 text-green-400"></i>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Pendapatan</p>
                    <p class="mt-2 text-2xl font-semibold text-gray-900">Rp 0</p>
                </div>
                <div class="w-10 h-10 rounded-md border border-yellow-200 bg-yellow-50 flex items-center justify-center">
                    <i data-lucide="dollar-sign" class="w-6 h-6 text-yellow-400"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-medium text-gray-900">Laporan Bulanan</h3>
            <div class="mt-4 space-y-3">
                <div class="text-sm text-gray-500">Belum ada laporan tersedia.</div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-medium text-gray-900">Analitik Kinerja</h3>
            <div class="mt-4 space-y-3">
                <div class="text-sm text-gray-500">Data analitik sedang diproses.</div>
            </div>
        </div>
    </div>

@endsection
