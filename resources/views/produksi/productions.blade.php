@extends('produksi.layout')

@section('title', 'Antrian Produksi')

@section('content')
<div class="space-y-6">
    {{-- Header & Search --}}
    <div class="flex flex-col md:flex-row justify-between md:items-center gap-4">
        <div>
            <h2 class="text-3xl font-bold tracking-tight">Antrian Produksi</h2>
            <p class="text-slate-500">Kelola daftar pesanan yang masuk ke tahap produksi</p>
        </div>
        
        {{-- Alert Sukses (Muncul jika ada update status) --}}
        @if(session('success'))
            <div class="absolute top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded shadow-md z-50" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif
        
        <form action="{{ url('/produksi/productions') }}" method="GET" class="relative w-full md:w-72">
            <input type="text" name="search" value="{{ request('search') }}" 
                   placeholder="Cari ID Pesanan atau Pelanggan..." 
                   class="w-full pl-10 pr-4 py-2 border border-slate-200 rounded-md focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-transparent text-sm">
            <i data-lucide="search" class="absolute left-3 top-2.5 h-4 w-4 text-slate-400"></i>
        </form>
    </div>

    {{-- Daftar Kartu Pesanan --}}
    <div class="grid gap-4">
        @forelse($productions as $item)
            <div class="bg-white border rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow">
                {{-- Info Utama --}}
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4 gap-2">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-lg">ORD-{{ str_pad($item->id, 3, '0', STR_PAD_LEFT) }}</span>
                            <span class="text-slate-400 mx-1">•</span>
                            <span class="text-slate-600 font-medium">{{ $item->nama_pelanggan }}</span>
                        </div>
                        <p class="text-xs text-slate-400 mt-1">Masuk: {{ \Carbon\Carbon::parse($item->tanggal_pesanan)->format('d M Y') }}</p>
                    </div>

                    @php
                        $badgeClass = match($item->status_produksi) {
                            'Menunggu', 'Desain Disetujui' => 'bg-slate-100 text-slate-700 border-slate-200',
                            'Produksi' => 'bg-blue-50 text-blue-700 border-blue-200',
                            'Selesai' => 'bg-green-50 text-green-700 border-green-200',
                            default => 'bg-gray-50 text-gray-600 border-gray-200',
                        };
                        $statusLabel = match($item->status_produksi) {
                            'Desain Disetujui' => 'Siap Produksi',
                            'Produksi' => 'Sedang Diproduksi',
                            default => $item->status_produksi
                        };
                    @endphp
                    <span class="px-3 py-1 rounded-full text-xs font-semibold border {{ $badgeClass }}">
                        {{ $statusLabel }}
                    </span>
                </div>

                <div class="border-t border-slate-100 my-4"></div>

                {{-- Detail Grid --}}
                <div class="grid md:grid-cols-3 gap-6 text-sm mb-4">
                    <div>
                        <p class="text-slate-500 mb-1">Jenis Layanan</p>
                        <p class="font-semibold text-slate-900">{{ $item->jenis_layanan }}</p>
                    </div>
                    <div>
                        <p class="text-slate-500 mb-1">Jumlah</p>
                        <p class="font-semibold text-slate-900">{{ $item->jumlah }} pcs</p>
                    </div>
                    <div>
                        <p class="text-slate-500 mb-1">File Desain</p>
                        <a href="#" class="flex items-center gap-1 text-blue-600 hover:underline font-medium">
                            <i data-lucide="file" class="h-3 w-3"></i> {{ $item->file_desain }}
                        </a>
                    </div>
                </div>

                {{-- Catatan --}}
                @if($item->catatan)
                    <div class="bg-slate-50 p-3 rounded-md mb-4">
                        <p class="text-xs text-slate-500 font-bold mb-1">CATATAN:</p>
                        <p class="text-sm text-slate-700 italic">"{{ $item->catatan }}"</p>
                    </div>
                @endif

                {{-- Tombol Aksi (Functional) --}}
                <div class="flex flex-wrap gap-2 mt-2">
                    
                    {{-- Tombol: MULAI PRODUKSI --}}
                    @if($item->status_produksi == 'Desain Disetujui' || $item->status_produksi == 'Menunggu')
                        <form action="{{ route('produksi.productions.start', $item->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-900 text-white text-sm font-medium rounded-md hover:bg-slate-800 transition-colors">
                                <i data-lucide="play" class="h-4 w-4"></i> Mulai Produksi
                            </button>
                        </form>
                    @endif

                    {{-- Tombol: SELESAI PRODUKSI --}}
                    @if($item->status_produksi == 'Produksi')
                        <form action="{{ route('produksi.productions.complete', $item->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 transition-colors">
                                <i data-lucide="check" class="h-4 w-4"></i> Selesai Produksi
                            </button>
                        </form>

                        {{-- Tombol: LAPOR KENDALA (Link ke Halaman Issues) --}}
                        <a href="{{ route('produksi.issues') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-red-200 text-red-600 text-sm font-medium rounded-md hover:bg-red-50 transition-colors">
                            <i data-lucide="alert-circle" class="h-4 w-4"></i> Lapor Kendala
                        </a>
                    @endif

                    {{-- Tombol: PRINT (Simple JS Print) --}}
                    <button onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 text-slate-700 text-sm font-medium rounded-md hover:bg-slate-50 transition-colors">
                        <i data-lucide="printer" class="h-4 w-4"></i> Print Job Sheet
                    </button>
                </div>
            </div>
        @empty
            <div class="text-center py-12 bg-white border border-dashed border-slate-300 rounded-xl">
                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-slate-100 mb-4">
                    <i data-lucide="inbox" class="h-6 w-6 text-slate-400"></i>
                </div>
                <h3 class="text-lg font-medium text-slate-900">Tidak ada antrian</h3>
                <p class="text-slate-500">Belum ada pesanan masuk saat ini.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $productions->links() }}
    </div>
</div>
@endsection