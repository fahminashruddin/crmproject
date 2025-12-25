@extends('produksi.layout')

@section('content')
<div class="space-y-6">
    {{-- 1. Header Section --}}
    <div>
        <h2 class="text-3xl font-bold tracking-tight">Dashboard Tim Produksi</h2>
        <p class="text-muted-foreground text-gray-500">Kelola proses produksi cetak</p>
    </div>

    {{-- 2. Summary Cards Section --}}
    <div class="grid gap-4 md:grid-cols-3">
        {{-- Card: Menunggu Produksi --}}
        <div class="rounded-xl border bg-white text-card-foreground shadow-sm">
            <div class="p-6 flex flex-row items-center justify-between space-y-0 pb-2">
                <h3 class="tracking-tight text-sm font-medium">Menunggu Produksi</h3>
                {{-- Icon: Clock (Lucide) --}}
                <svg xmlns="http://www.w3.oarg/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 text-gray-500"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            </div>
            <div class="p-6 pt-0">
                {{-- Variabel $menunggu dari Controller --}}
                <div class="text-2xl font-bold">{{ $menunggu ?? 0 }}</div>
                <p class="text-xs text-gray-500">Siap diproduksi</p>
            </div>
        </div>

        {{-- Card: Sedang Diproduksi --}}
        <div class="rounded-xl border bg-white text-card-foreground shadow-sm">
            <div class="p-6 flex flex-row items-center justify-between space-y-0 pb-2">
                <h3 class="tracking-tight text-sm font-medium">Sedang Diproduksi</h3>
                {{-- Icon: Factory (Lucide) --}}
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 text-gray-500"><path d="M2 20a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8l-7 5V8l-7 5V4a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"/><line x1="17" x2="17" y1="13" y2="23"/><line x1="12" x2="12" y1="13" y2="23"/><line x1="7" x2="7" y1="13" y2="23"/></svg>
            </div>
            <div class="p-6 pt-0">
                <div class="text-2xl font-bold">{{ $sedangProses ?? 0 }}</div>
                <p class="text-xs text-gray-500">Dalam proses</p>
            </div>
        </div>

        {{-- Card: Selesai Produksi --}}
        <div class="rounded-xl border bg-white text-card-foreground shadow-sm">
            <div class="p-6 flex flex-row items-center justify-between space-y-0 pb-2">
                <h3 class="tracking-tight text-sm font-medium">Selesai Produksi</h3>
                {{-- Icon: Check (Lucide) --}}
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 text-gray-500"><polyline points="20 6 9 17 4 12"/></svg>
            </div>
            <div class="p-6 pt-0">
                <div class="text-2xl font-bold">{{ $selesai ?? 0 }}</div>
                <p class="text-xs text-gray-500">Siap dikirim</p>
            </div>
        </div>
    </div>

    {{-- 3. Antrian Produksi List (Recent Orders) --}}
    <div class="rounded-xl border bg-white text-card-foreground shadow-sm">
        <div class="flex flex-col space-y-1.5 p-6">
            <h3 class="font-semibold leading-none tracking-tight">Antrian Produksi</h3>
            <p class="text-sm text-gray-500">Pesanan yang siap untuk diproduksi (5 Terbaru)</p>
        </div>
        <div class="p-6 pt-0">
            <div class="space-y-4">
                {{-- Loop data antrian --}}
                @forelse($antrian as $item)
                    <div class="border rounded-lg p-4">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                {{-- Menampilkan ID Pesanan dengan format ORD-00X --}}
                                <h3 class="font-semibold text-gray-900">
                                    ORD-{{ str_pad($item->id, 3, '0', STR_PAD_LEFT) }}
                                </h3>
                                <p class="text-sm text-gray-500">{{ $item->nama_pelanggan ?? 'Nama Pelanggan' }}</p>
                            </div>
                            
                            {{-- Logic Badge Status Warna-warni --}}
                            @php
                                $status = $item->status_produksi ?? 'Pending';
                                $badgeClass = match($status) {
                                    'Pending', 'Menunggu' => 'bg-yellow-100 text-yellow-800',
                                    'Proses Desain' => 'bg-orange-100 text-orange-800',
                                    'Desain Disetujui' => 'bg-blue-100 text-blue-800',
                                    'Diproses', 'Produksi' => 'bg-blue-100 text-blue-800',
                                    'Selesai' => 'bg-green-100 text-green-800',
                                    'Dibatalkan' => 'bg-red-100 text-red-800',
                                    default => 'bg-gray-100 text-gray-800',
                                };
                                $statusLabel = match($status) {
                                    'Pending', 'Menunggu' => 'Menunggu Produksi',
                                    'Proses Desain' => 'Proses Desain',
                                    'Desain Disetujui' => 'Desain Disetujui',
                                    'Diproses', 'Produksi' => 'Sedang Diproduksi',
                                    'Selesai' => 'Selesai',
                                    'Dibatalkan' => 'Dibatalkan',
                                    default => ucfirst($status),
                                };
                            @endphp
                            <div class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 {{ $badgeClass }}">
                                {{ $statusLabel }}
                            </div>
                        </div>

                        {{-- Detail Pesanan --}}
                        <div class="space-y-2 text-sm text-gray-700">
                            <p>
                                <span class="font-medium text-gray-900">Layanan:</span> 
                                {{ $item->jenis_layanan ?? '-' }}
                            </p>
                            <p>
                                <span class="font-medium text-gray-900">Jumlah:</span> 
                                {{ $item->jumlah ?? 0 }} pcs
                            </p>
                            
                            @if(!empty($item->spesifikasi))
                                <p>
                                    <span class="font-medium text-gray-900">Spesifikasi:</span> 
                                    {{ $item->spesifikasi }}
                                </p>
                            @endif
                        </div>
                    </div>
                @empty
                    {{-- Tampilan jika tidak ada data --}}
                    <div class="text-center py-8 text-muted-foreground text-gray-500">
                        Tidak ada pesanan dalam antrian produksi
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection