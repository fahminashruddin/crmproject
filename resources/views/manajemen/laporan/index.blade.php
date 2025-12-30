@extends('manajemen.layout')
@section('title', 'Laporan Sistem')

@section('content')

<script src="https://cdn.tailwindcss.com"></script>
<script src="https://unpkg.com/lucide@latest"></script>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>*{font-family:'Inter',sans-serif}</style>

<div class="space-y-8">

    <!-- HEADER -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-2">
            <i data-lucide="file-text" class="w-6 text-gray-700"></i>
            <h2 class="text-2xl font-bold text-gray-900">Laporan Sistem</h2>
        </div>

        <div class="flex gap-3">
            <a href="{{ route('manajemen.laporan.export', ['format' => 'excel']) }}"
               class="flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white rounded-2xl hover:scale-105 hover:bg-emerald-700 transition shadow-md">
                <i data-lucide="sheet" class="w-4"></i> Excel
            </a>
            <a href="{{ route('manajemen.laporan.export', ['format' => 'pdf']) }}"
               class="flex items-center gap-2 px-4 py-2 bg-rose-600 text-white rounded-2xl hover:scale-105 hover:bg-rose-700 transition shadow-md">
                <i data-lucide="file" class="w-4"></i> PDF
            </a>
        </div>
    </div>

    <!-- FILTER -->
    <div class="bg-white rounded-3xl p-6 shadow-lg border border-gray-100">
        <form method="GET" action="{{ route('manajemen.laporan.index') }}"
              class="grid grid-cols-1 md:grid-cols-3 gap-5">

            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-1">Dari</label>
                <input type="date" name="start_date"
                       class="w-full px-4 py-2 border rounded-2xl focus:ring-2"
                       value="{{ $start ?? '' }}">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-1">Sampai</label>
                <input type="date" name="end_date"
                       class="w-full px-4 py-2 border rounded-2xl focus:ring-2"
                       value="{{ $end ?? '' }}">
            </div>

            <div class="flex items-end">
                <button class="w-full bg-indigo-600 text-white py-2 rounded-2xl hover:scale-105 hover:bg-indigo-700 transition shadow-md">
                    Filter
                </button>
            </div>

        </form>
    </div>

    <!-- RINGKASAN -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

        <div class="bg-white p-5 rounded-3xl shadow-md border hover:scale-[1.02] transition">
            <p class="text-xs font-medium text-gray-500 flex items-center gap-1">
                <i data-lucide="shopping-bag" class="w-4"></i> Total Pesanan
            </p>
            <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $totalPesanan }}</h3>
            <p class="text-[11px] text-gray-400 mt-1">{{ $start && $end ? "$start s/d $end" : 'Semua periode' }}</p>
        </div>

        <div class="bg-white p-5 rounded-3xl shadow-md border hover:scale-[1.02] transition">
            <p class="text-xs font-medium text-gray-500 flex items-center gap-1">
                <i data-lucide="check-circle" class="w-4"></i> Selesai
            </p>
            <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $selesai }}</h3>
            <p class="text-[11px] text-gray-400 mt-1">{{ round($completionRate, 2) }}% completion</p>
        </div>

        <div class="bg-white p-5 rounded-3xl shadow-md border hover:scale-[1.02] transition">
            <p class="text-xs font-medium text-gray-500 flex items-center gap-1">
                <i data-lucide="wallet" class="w-4"></i> Pendapatan
            </p>
            <h3 class="text-3xl font-bold text-gray-900 mt-2">Rp {{ number_format($totalPendapatan,0,',','.') }}</h3>
            <p class="text-[11px] text-gray-400 mt-1">Terverifikasi</p>
        </div>

        <div class="bg-white p-5 rounded-3xl shadow-md border hover:scale-[1.02] transition">
            <p class="text-xs font-medium text-gray-500 flex items-center gap-1">
                <i data-lucide="loader" class="w-4"></i> Pending
            </p>
            <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $pending }}</h3>
            <p class="text-[11px] text-gray-400 mt-1">Perlu tindak lanjut</p>
        </div>

    </div>

    <!-- PERFORMA LAYANAN -->
    <div class="bg-white p-6 rounded-3xl shadow-lg border border-gray-100">
        <div class="flex items-center gap-2">
            <i data-lucide="layers" class="w-5 text-gray-700"></i>
            <h3 class="text-lg font-bold text-gray-900">Performa Layanan</h3>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mt-5">
            @foreach ($ringkasan['perLayanan'] as $l)
            <div class="bg-gray-50 p-4 rounded-3xl border hover:shadow-md transition">
                <p class="font-bold text-gray-900">{{ $l->nama_layanan }}</p>
                <p class="text-xs text-gray-500 mt-1">{{ $l->jumlah_pesanan }} pesanan</p>
                <p class="text-lg font-extrabold text-gray-900 mt-2">Rp {{ number_format($l->total_nominal,0,',','.') }}</p>
                <p class="text-[11px] text-gray-400 mt-1">Avg: {{ number_format($l->rata_rata,0,',','.') }}</p>
            </div>
            @endforeach
        </div>
    </div>

    <!-- AKTIVITAS TERBARU -->
    <div class="bg-white p-6 rounded-3xl shadow-lg border border-gray-100">
        <div class="flex items-center gap-2 mb-5">
            <i data-lucide="activity" class="w-5 text-gray-700"></i>
            <h3 class="text-lg font-bold text-gray-900">Aktivitas Terbaru</h3>
        </div>

        <div class="space-y-3">
            @foreach ($ringkasan['aktivitasTerbaru'] as $a)
            <div class="bg-gray-50 p-4 rounded-3xl border flex justify-between items-start hover:shadow-sm transition">
                <div>
                    <p class="font-bold text-gray-900">ORD-{{ str_pad($a->id, 3, '0', STR_PAD_LEFT) }} — {{ $a->pelanggan }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $a->nama_layanan }} • {{ $a->tanggal_pesanan }}</p>
                    <span class="inline-block text-[10px] mt-2 px-3 py-1 bg-gray-200 text-gray-700 rounded-xl">{{ $a->nama_status }}</span>
                </div>
                <p class="text-sm font-extrabold text-gray-900">Rp {{ number_format($a->nominal ?? 0,0,',','.') }}</p>
            </div>
            @endforeach
        </div>
    </div>

    <!-- TABEL DETAIL -->
    <div class="bg-white p-6 rounded-3xl shadow-lg border border-gray-100">
        <div class="flex items-center gap-2 mb-5">
            <i data-lucide="list" class="w-5 text-gray-700"></i>
            <h3 class="text-lg font-bold text-gray-900">Detail Pesanan</h3>
        </div>

        <div class="overflow-x-auto rounded-3xl border">
            <table class="w-full text-sm">
                <thead class="bg-gray-900 text-white">
                    <tr>
                        <th class="p-4 text-left">ID</th>
                        <th class="p-4 text-left">Pelanggan</th>
                        <th class="p-4 text-left">Layanan</th>
                        <th class="p-4 text-left">Tanggal</th>
                        <th class="p-4 text-left">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($detailPesanan as $p)
                    <tr class="border-b hover:bg-gray-50 transition">
                        <td class="p-4 font-semibold">#{{ $p->id }}</td>
                        <td class="p-4">{{ $p->pelanggan }}</td>
                        <td class="p-4">{{ $p->nama_layanan }}</td>
                        <td class="p-4 text-gray-500">{{ $p->tanggal_pesanan }}</td>
                        <td class="p-4 font-medium">{{ $p->nama_status }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>

<script>lucide.createIcons()</script>

@endsection
