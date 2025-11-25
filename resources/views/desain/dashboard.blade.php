@extends('desain.layout')

@section('title', 'Dashboard Desain')

@section('content')
    <div class="mb-6">
        <h1 class="text-3xl font-extrabold">Dashboard Desain</h1>
        <p class="text-sm text-gray-500 mt-1">Kelola proses desain pesanan</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Desain Menunggu</p>
                    <p class="mt-2 text-2xl font-semibold text-gray-900">0</p>
                </div>
                <div class="w-10 h-10 rounded-md border border-blue-200 bg-blue-50 flex items-center justify-center">
                    <i data-lucide="palette" class="w-6 h-6 text-blue-400"></i>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Desain Selesai</p>
                    <p class="mt-2 text-2xl font-semibold text-gray-900">0</p>
                </div>
                <div class="w-10 h-10 rounded-md border border-green-200 bg-green-50 flex items-center justify-center">
                    <i data-lucide="check-circle" class="w-6 h-6 text-green-400"></i>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Revisi Menunggu</p>
                    <p class="mt-2 text-2xl font-semibold text-gray-900">0</p>
                </div>
                <div class="w-10 h-10 rounded-md border border-orange-200 bg-orange-50 flex items-center justify-center">
                    <i data-lucide="undo-2" class="w-6 h-6 text-orange-400"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-medium text-gray-900">Desain Pesanan Terbaru</h3>
            <div class="mt-4 space-y-3">
                <div class="text-sm text-gray-500">Belum ada desain pesanan.</div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-medium text-gray-900">Revisi Terbaru</h3>
            <div class="mt-4 space-y-3">
                <div class="text-sm text-gray-500">Belum ada revisi pesanan.</div>
            </div>
        </div>
    </div>

@endsection
