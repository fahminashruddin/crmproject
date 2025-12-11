@extends('manajemen.layout')

@section('title', 'Laporan Sistem')

@section('content')

<div class="space-y-10">

    <!-- Header + Buttons -->
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
            <i data-lucide="file-text"></i> Laporan Sistem
        </h2>

        <div class="flex gap-3">
            <a href="{{ route('manajemen.laporan.export', ['format' => 'excel']) }}"
               class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition shadow-sm">
                Export Excel
            </a>
            <a href="{{ route('manajemen.laporan.export', ['format' => 'pdf']) }}"
               class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition shadow-sm">
                Export PDF
            </a>
        </div>
    </div>

    <!-- Filter Periode -->
    <div class="bg-white rounded-xl p-7 shadow-sm border">
        <form method="GET" action="{{ route('manajemen.laporan.index') }}" 
              class="grid grid-cols-1 md:grid-cols-3 gap-7">

            <div class="space-y-1">
                <label class="block font-semibold text-gray-700">Dari Tanggal</label>
                <input type="date" name="start_date" 
                       class="w-full border-gray-300 rounded-lg px-3 py-2 focus:ring-purple-500"
                       value="{{ $start ?? '' }}">
            </div>

            <div class="space-y-1">
                <label class="block font-semibold text-gray-700">Sampai Tanggal</label>
                <input type="date" name="end_date" 
                       class="w-full border-gray-300 rounded-lg px-3 py-2 focus:ring-purple-500"
                       value="{{ $end ?? '' }}">
            </div>

            <div class="flex items-end">
                <button class="w-full bg-purple-600 text-white py-2 rounded-lg hover:bg-purple-700 transition shadow-sm">
                    Filter
                </button>
            </div>

        </form>
    </div>

  <!-- Ringkasan -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-7">

        <div class="bg-white p-6 rounded-xl shadow-sm border">
            <p class="text-sm text-gray-500">Total Pesanan</p>
            <h3 class="text-2xl font-bold mt-1 text-gray-800">{{ $totalPesanan }}</h3>
            <p class="text-xs text-gray-400">{{ $start && $end ? "$start s/d $end" : 'Semua periode' }}</p>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border">
            <p class="text-sm text-gray-500">Pesanan Selesai</p>
            <h3 class="text-2xl font-bold mt-1 text-gray-800">{{ $selesai }}</h3>
            <p class="text-xs text-gray-400">{{ round($completionRate, 2) }}% completion rate</p>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border">
            <p class="text-sm text-gray-500">Total Pendapatan</p>
            <h3 class="text-2xl font-bold mt-1 text-gray-800">
                Rp {{ number_format($totalPendapatan,0,',','.') }}
            </h3>
            <p class="text-xs text-gray-400">Pembayaran terverifikasi</p>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border">
            <p class="text-sm text-gray-500">Pesanan Pending</p>
            <h3 class="text-2xl font-bold mt-1 text-gray-800">{{ $pending }}</h3>
            <p class="text-xs text-gray-400">Perlu tindak lanjut</p>
        </div>

    </div>
<!-- Performa per Layanan -->
<div class="bg-white p-7 rounded-xl shadow-sm border space-y-4">
    <h3 class="text-lg font-semibold text-gray-800">Performa per Layanan</h3>
    <p class="text-sm text-gray-500">Analisis pesanan dan pendapatan per jenis layanan</p>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mt-4">
        @foreach ($ringkasan['perLayanan'] as $l)
        <div class="bg-gray-50 p-4 rounded-lg shadow-sm border">
            <p class="font-semibold text-gray-700">{{ $l->nama_layanan }}</p>
            <p class="text-gray-500 text-sm">{{ $l->jumlah_pesanan }} pesanan</p>
            <p class="text-gray-800 font-bold">Rp {{ number_format($l->total_nominal,0,',','.') }}</p>
            <p class="text-gray-400 text-sm">{{ number_format($l->rata_rata,0,',','.') }} rata-rata</p>
        </div>
        @endforeach
    </div>
</div>

<!-- Aktivitas Terbaru -->
<div class="bg-white p-7 rounded-xl shadow-sm border space-y-4">
    <h3 class="text-lg font-semibold text-gray-800">Aktivitas Terbaru</h3>

    <div class="space-y-3 mt-4">
        @foreach ($ringkasan['aktivitasTerbaru'] as $a)
        <div class="p-3 bg-gray-50 rounded-lg border flex flex-col">
            <p class="font-semibold text-gray-700">ORD-{{ str_pad($a->id, 3, '0', STR_PAD_LEFT) }} - {{ $a->pelanggan }}</p>
            <p class="text-gray-500 text-sm">{{ $a->nama_layanan }} • {{ $a->tanggal_pesanan }}</p>
            <p class="text-gray-500 text-sm">{{ $a->nama_status }}</p>
            <p class="text-gray-800 font-bold">Rp {{ number_format($a->nominal ?? 0,0,',','.') }}</p>
        </div>
        @endforeach
    </div>
</div>

    <!-- Detail Pesanan -->
    <div class="bg-white p-7 rounded-xl shadow-sm border space-y-4">
        <h3 class="text-lg font-semibold text-gray-800">Detail Pesanan</h3>

        <div class="overflow-x-auto">
            <table class="w-full border-collapse">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="p-3 text-left">ID</th>
                        <th class="p-3 text-left">Pelanggan</th>
                        <th class="p-3 text-left">Layanan</th>
                        <th class="p-3 text-left">Tanggal</th>
                        <th class="p-3 text-left">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($detailPesanan as $p)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-3">{{ $p->id }}</td>
                        <td class="p-3">{{ $p->pelanggan }}</td>
                        <td class="p-3">{{ $p->nama_layanan }}</td>
                        <td class="p-3">{{ $p->tanggal_pesanan }}</td>
                        <td class="p-3">{{ $p->nama_status }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection
