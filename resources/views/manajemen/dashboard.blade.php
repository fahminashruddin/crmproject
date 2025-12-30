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

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
    * { font-family: 'Inter', sans-serif; }
</style>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">

    {{-- Total Pesanan --}}
    <div class="bg-white p-5 rounded-2xl shadow-md border border-gray-100 hover:shadow-xl transition-all duration-300">
        <div class="flex items-center gap-3 text-gray-600">
            <i data-lucide="shopping-bag" class="w-5"></i>
            <p class="text-sm font-medium">Total Pesanan</p>
        </div>
        <h2 class="mt-3 text-3xl font-bold text-gray-900">{{ $totalPesanan }}</h2>
        <p class="text-xs text-gray-400 mt-1">Semua periode</p>
    </div>

    {{-- Tingkat Penyelesaian --}}
    <div class="bg-white p-5 rounded-2xl shadow-md border border-gray-100 hover:shadow-xl transition-all duration-300">
        <div class="flex items-center gap-3 text-gray-600">
            <i data-lucide="check-circle" class="w-5"></i>
            <p class="text-sm font-medium">Tingkat Penyelesaian</p>
        </div>
        <h2 class="mt-3 text-3xl font-bold text-gray-900">
            {{ $totalPesanan > 0 ? round(($pesananSelesai / $totalPesanan) * 100) : 0 }}%
        </h2>
        <p class="text-xs text-gray-400 mt-1">{{ $pesananSelesai }} / {{ $totalPesanan }} pesanan</p>
    </div>

    {{-- Total Pendapatan --}}
    <div class="bg-white p-5 rounded-2xl shadow-md border border-gray-100 hover:shadow-xl transition-all duration-300">
        <div class="flex items-center gap-3 text-gray-600">
            <i data-lucide="wallet" class="w-5"></i>
            <p class="text-sm font-medium">Total Pendapatan</p>
        </div>
        <h2 class="mt-3 text-3xl font-bold text-gray-900">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h2>
        <p class="text-xs text-gray-400 mt-1">Pembayaran terverifikasi</p>
    </div>

    {{-- Rata-rata Nilai Pesanan --}}
    <div class="bg-white p-5 rounded-2xl shadow-md border border-gray-100 hover:shadow-xl transition-all duration-300">
        <div class="flex items-center gap-3 text-gray-600">
            <i data-lucide="bar-chart-2" class="w-5"></i>
            <p class="text-sm font-medium">Rata-rata Pesanan</p>
        </div>
        <h2 class="mt-3 text-3xl font-bold text-gray-900">Rp {{ number_format($rataRataPesanan, 0, ',', '.') }}</h2>
        <p class="text-xs text-gray-400 mt-1">Per pesanan</p>
    </div>

</div>

{{-- Distribusi Layanan --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    <div class="bg-white rounded-3xl shadow-lg border border-gray-100 p-6 max-h-80 overflow-y-auto">
        <div class="flex items-center gap-2 mb-5">
            <i data-lucide="layers" class="w-5 text-gray-700"></i>
            <h3 class="text-lg font-semibold text-gray-900">Distribusi Layanan</h3>
        </div>

        @php $totalLayanan = array_sum($distribusiLayanan); @endphp

        @foreach($distribusiLayanan as $layanan => $jumlah)
            @php $percent = $totalLayanan > 0 ? ($jumlah / $totalLayanan) * 100 : 0; @endphp
            <div class="flex items-center justify-between mb-4">
                <span class="text-sm font-medium text-gray-700">{{ $layanan }}</span>
                <div class="flex items-center gap-3">
                    <div class="w-36 bg-gray-100 rounded-full h-2.5 overflow-hidden">
                        <div class="h-2.5 rounded-full transition-all duration-500" style="width: {{ $percent }}%"></div>
                    </div>
                    <span class="text-sm font-semibold text-gray-900">{{ $jumlah }}</span>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Status Pesanan --}}
    <div class="bg-white rounded-3xl shadow-lg border border-gray-100 p-6 max-h-80 overflow-y-auto">
        <div class="flex items-center gap-2 mb-5">
            <i data-lucide="pie-chart" class="w-5 text-gray-700"></i>
            <h3 class="text-lg font-semibold text-gray-900">Status Pesanan</h3>
        </div>

        @php $totalStatus = array_sum($statusCounts); @endphp

        @foreach($statusCounts as $status => $jumlah)
            @php $percent = $totalStatus > 0 ? ($jumlah / $totalStatus) * 100 : 0; @endphp
            <div class="flex items-center justify-between mb-4">
                <span class="text-sm font-medium text-gray-700">{{ $status }}</span>
                <div class="flex items-center gap-3">
                    <div class="w-36 bg-gray-100 rounded-full h-2.5 overflow-hidden">
                        <div class="h-2.5 rounded-full transition-all duration-500" style="width: {{ $percent }}%"></div>
                    </div>
                    <span class="text-sm font-semibold text-gray-900">{{ $jumlah }}</span>
                </div>
            </div>
        @endforeach
    </div>

</div>

<script> lucide.createIcons(); </script>
<script>
    lucide.createIcons();
</script>

@endsection
