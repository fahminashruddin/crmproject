@extends('desain.layout')

@section('content')
<div class="space-y-8">
    {{-- Header --}}
    <div>
        <h2 class="text-3xl font-bold tracking-tight">Dashboard Tim Desain 🎨</h2>
        <p class="text-muted-foreground text-gray-500">Kelola proses desain dan approval</p>
    </div>

    {{-- Cards Statistik --}}
    <div class="grid gap-4 md:grid-cols-3">
        {{-- Card 1: Menunggu Desain --}}
        <div class="rounded-xl border bg-white p-6 shadow-sm">
            <div class="flex justify-between items-center pb-2">
                <h3 class="text-sm font-medium text-gray-500">Menunggu Desain</h3>
                <i data-lucide="file-plus" class="h-4 w-4 text-gray-500"></i>
            </div>
            <div class="text-2xl font-bold">{{ $menunggu ?? 2 }}</div>
            <p class="text-xs text-gray-500">Perlu dikerjakan</p>
        </div>
        
        {{-- Card 2: Desain Disetujui (Siap Produksi) --}}
        <div class="rounded-xl border bg-white p-6 shadow-sm">
            <div class="flex justify-between items-center pb-2">
                <h3 class="text-sm font-medium text-gray-500">Desain Disetujui</h3>
                <i data-lucide="check-circle" class="h-4 w-4 text-green-500"></i>
            </div>
            <div class="text-2xl font-bold ">{{ $sedangProses ?? 1 }}</div>
            <p class="text-xs text-gray-500">Siap produksi</p>
        </div>
        
        {{-- Card 3: Perlu Revisi --}}
        <div class="rounded-xl border bg-white p-6 shadow-sm">
            <div class="flex justify-between items-center pb-2">
                <h3 class="text-sm font-medium text-gray-500">Perlu Revisi</h3>
                <i data-lucide="alert-triangle" class="h-4 w-4 text-red-500"></i>
            </div>
            <div class="text-2xl font-bold">{{ $selesai ?? 1 }}</div>
            <p class="text-xs text-gray-500">Perlu diperbaiki</p>
        </div>
    </div>

    {{-- Daftar Pekerjaan Aktif --}}
    <div class="space-y-4">
        <h3 class="text-3xl font-bold">Antrian Desain</h3>
        <p class="text-muted-foreground text-gray-500">pesanan yang menunggu proses desain</p>

        <div class="grid gap-4 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3">

            @forelse($antrian as $item)
            <div class="rounded-xl border bg-white p-5 shadow-sm flex flex-col justify-between">
                
                {{-- Bagian Atas: Header dan Status --}}
                <div class="flex justify-between items-start mb-3">
                    <div class="space-y-1">
                        {{-- ID Pesanan --}}
                        <div class="text-lg font-bold text-gray-800">
                            ORD-{{ str_pad($item->id, 3, '0', STR_PAD_LEFT) }}
                        </div>
                        {{-- Nama Pelanggan --}}
                        <div class="text-sm text-gray-600">{{ $item->nama_pelanggan }}</div>
                    </div>
                    
                    {{-- Badge Status --}}
                    @php
                        $badgeClass = 'bg-blue-100 text-blue-800 border-blue-300';
                        if ($item->status_desain == 'Revisi') {
                            $badgeClass = 'bg-red-100 text-red-800 border-red-300';
                        } elseif ($item->status_desain == 'Proses') {
                            $badgeClass = 'bg-yellow-100 text-yellow-800 border-yellow-300';
                        }
                    @endphp
                    <span class="inline-flex items-center rounded-full border px-3 py-1 text-xs font-semibold {{ $badgeClass }}">
                        {{ $item->status_desain }}
                    </span>
                </div>
                
                {{-- Keterangan / Catatan --}}
                @if(isset($item->catatan))
                    <div class="mt-2 text-xs text-gray-500 italic border-t pt-3">
                        **Catatan:** "{{ Str::limit($item->catatan, 50) }}"
                    </div>
                @endif

                {{-- Bagian Bawah: Aksi --}}
                <div class="mt-4 flex justify-between items-center pt-3 border-t">
                    <div class="text-xs text-gray-400">
                        {{-- Menggunakan created_at (yaitu tanggal_pesanan) sebagai placeholder --}}
                        Tanggal Pesanan: {{ \Carbon\Carbon::parse($item->created_at)->format('d M') }}
                    </div>
                    {{-- Tombol Aksi --}}
                    <button class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium 
                                   ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 
                                   focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none 
                                   disabled:opacity-50 h-9 px-3 py-1 bg-black text-white hover:bg-gray-800">
                        Lihat Detail
                    </button>
                </div>
            </div>
            @empty
            {{-- Pesan Kosong --}}
            <div class="col-span-full text-center text-gray-500 py-8 border rounded-xl bg-white">
                <i data-lucide="inbox" class="h-6 w-6 mx-auto mb-2"></i>
                <p>Tidak ada tugas desain aktif saat ini.</p>
            </div>
            @endforelse
            
        </div>
    </div>
</div>
@endsection