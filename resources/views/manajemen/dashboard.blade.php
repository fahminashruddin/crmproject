@extends('manajemen.layout')

@section('title', 'Dashboard Manajemen')

@section('content')

{{-- Tailwind CDN --}}
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://unpkg.com/lucide@latest"></script>

<div class="mb-6">
    <h1 class="text-3xl font-extrabold">Dashboard Manajemen</h1>
    <p class="text-sm text-gray-500 mt-1">Monitoring dan analisis bisnis</p>
</div>

{{-- TOP 4 CARDS --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

    {{-- Total Pesanan --}}
    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
        <p class="text-sm text-gray-500">Total Pesanan</p>
        <h2 class="mt-2 text-2xl font-semibold">{{ $totalPesanan }}</h2>
        <p class="text-xs text-gray-400 mt-1">Semua periode</p>
    </div>

    {{-- Tingkat Penyelesaian --}}
    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
        <p class="text-sm text-gray-500">Tingkat Penyelesaian</p>
        <h2 class="mt-2 text-2xl font-semibold">
            {{ $totalPesanan > 0 ? round(($pesananSelesai / $totalPesanan) * 100) : 0 }}%
        </h2>
        <p class="text-xs text-gray-400 mt-1">
            {{ $pesananSelesai }} dari {{ $totalPesanan }} pesanan
        </p>
    </div>

    {{-- Total Pendapatan --}}
    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
        <p class="text-sm text-gray-500">Total Pendapatan</p>
        <h2 class="mt-2 text-2xl font-semibold">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h2>
        <p class="text-xs text-gray-400 mt-1">Pembayaran terverifikasi</p>
    </div>

    {{-- R
ata-rata Nilai Pesanan --}}
    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
        <p class="text-sm text-gray-500">Rata-rata Nilai Pesanan</p>
        <h2 class="mt-2 text
</div>-2xl font-semibold">Rp {{ number_format($rataRataPesanan, 0, ',', '.') }}</h2>
        <p class="text-xs text-gray-400 mt-1">Per pesanan</p>
    </div>

{{-- DISTRIBUSI & STATUS --}}
{{-- Distribusi Layanan --}}
<div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 max-h-80 overflow-y-auto">
    <h3 class="text-lg font-medium text-gray-900 mb-4">Distribusi Layanan</h3>

    @php
        $totalLayanan = array_sum($distribusiLayanan);
    @endphp

    @foreach($distribusiLayanan as $layanan => $jumlah)
        @php
            $percent = $totalLayanan > 0 ? ($jumlah / $totalLayanan) * 100 : 0;
        @endphp
        <div class="flex items-center justify-between mb-3">
            <span class="text-gray-700">{{ $layanan }}</span>

            <div class="flex items-center gap-3">
                <div class="w-32 bg-gray-200 rounded-full h-2">
                    <div class="bg-black h-2 rounded-full" style="width: {{ $percent }}%"></div>
                </div>
                <span class="text-gray-600 text-sm">{{ $jumlah }}</span>
            </div>
        </div>
    @endforeach
</div>

{{-- Status Pesanan --}}
<div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 max-h-80 overflow-y-auto mt-6">
    <h3 class="text-lg font-medium text-gray-900 mb-4">Status Pesanan</h3>

    @php
        $totalStatus = array_sum($statusCounts);
    @endphp

    @foreach($statusCounts as $status => $jumlah)
        @php
            $percent = $totalStatus > 0 ? ($jumlah / $totalStatus) * 100 : 0;
        @endphp
        <div class="flex items-center justify-between mb-3">
            <span class="text-gray-700">{{ $status }}</span>

            <div class="flex items-center gap-3">
                <div class="w-32 bg-gray-200 rounded-full h-2">
                    <div class="bg-black h-2 rounded-full" style="width: {{ $percent }}%"></div>
                </div>
                <span class="text-gray-600 text-sm">{{ $jumlah }}</span>
            </div>
        </div>
    @endforeach
</div>

<script>
    lucide.createIcons();
</script>

@endsection
