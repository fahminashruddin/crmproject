@extends('produksi.layout')

@section('title', 'Dashboard Produksi')

@section('content')
    <div class="mb-6">
        <h1 class="text-3xl font-extrabold">Dashboard Produksi</h1>
        <p class="text-sm text-gray-500 mt-1">Kelola proses produksi pesanan</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Produksi Menunggu</p>
                    <p class="mt-2 text-2xl font-semibold text-gray-900">0</p>
                </div>
                <div class="w-10 h-10 rounded-md border border-orange-200 bg-orange-50 flex items-center justify-center">
                    <i data-lucide="wrench" class="w-6 h-6 text-orange-400"></i>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Produksi Berjalan</p>
                    <p class="mt-2 text-2xl font-semibold text-gray-900">0</p>
                </div>
                <div class="w-10 h-10 rounded-md border border-yellow-200 bg-yellow-50 flex items-center justify-center">
                    <i data-lucide="play-circle" class="w-6 h-6 text-yellow-400"></i>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Kendala Aktif</p>
                    <p class="mt-2 text-2xl font-semibold text-gray-900">0</p>
                </div>
                <div class="w-10 h-10 rounded-md border border-red-200 bg-red-50 flex items-center justify-center">
                    <i data-lucide="alert-circle" class="w-6 h-6 text-red-400"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-medium text-gray-900">Produksi Terbaru</h3>
            <div class="mt-4 space-y-3">
                <div class="text-sm text-gray-500">Belum ada proses produksi.</div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-medium text-gray-900">Kendala Produksi</h3>
            <div class="mt-4 space-y-3">
                <div class="text-sm text-gray-500">Belum ada kendala tercatat.</div>
            </div>
        </div>
    </div>

@endsection
