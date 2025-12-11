@extends('produksi.layout')

@section('title', 'Daftar Semua Pesanan')

@section('content')
<div class="max-w-7xl mx-auto font-sans text-slate-900">

    {{-- HEADER --}}
    <div class="mb-8">
        <h1 class="text-3xl font-bold tracking-tight text-black">Kelola Produksi</h1>
        <p class="text-slate-500 mt-1">Daftar seluruh riwayat pesanan produksi</p>
    </div>

    {{-- KARTU STATISTIK --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        {{-- Card 1 --}}
        <div class="bg-white border border-slate-200 rounded-lg p-6 flex justify-between items-start shadow-sm">
            <div>
                <p class="text-sm font-bold text-slate-900 mb-2">Menunggu Produksi</p>
                <h2 class="text-4xl font-bold text-black mb-1">{{ $menunggu }}</h2>
                <p class="text-xs text-slate-400">Siap diproduksi</p>
            </div>
            <div class="text-slate-400 pt-1">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            </div>
        </div>
        {{-- Card 2 --}}
        <div class="bg-white border border-slate-200 rounded-lg p-6 flex justify-between items-start shadow-sm">
            <div>
                <p class="text-sm font-bold text-slate-900 mb-2">Sedang Diproduksi</p>
                <h2 class="text-4xl font-bold text-black mb-1">{{ $sedangProses }}</h2>
                <p class="text-xs text-slate-400">Dalam proses</p>
            </div>
            <div class="text-slate-400 pt-1">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18"/><path d="M5 21V7l8-4 8 4v14"/><path d="M17 21v-8.5a2.5 2.5 0 0 0-5 0V21"/></svg>
            </div>
        </div>
        {{-- Card 3 --}}
        <div class="bg-white border border-slate-200 rounded-lg p-6 flex justify-between items-start shadow-sm">
            <div>
                <p class="text-sm font-bold text-slate-900 mb-2">Selesai Produksi</p>
                <h2 class="text-4xl font-bold text-black mb-1">{{ $selesai }}</h2>
                <p class="text-xs text-slate-400">Siap dikirim</p>
            </div>
            <div class="text-slate-400 pt-1">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
            </div>
        </div>
    </div>

    {{-- LIST DATA --}}
    <div class="bg-white border border-slate-200 rounded-xl p-8 shadow-sm">
        <div class="flex flex-col md:flex-row justify-between md:items-center mb-6 gap-4">
            <div>
                <h3 class="text-2xl font-bold text-black">Data Produksi</h3>
                <p class="text-slate-500 text-sm mt-1">Total {{ $productions->total() }} pesanan</p>
            </div>
            <form action="{{ url('/produksi/productions') }}" method="GET" class="relative w-full md:w-72">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari pesanan..." class="w-full pl-10 pr-4 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-black">
                <svg class="absolute left-3 top-2.5 h-4 w-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            </form>
        </div>

        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg text-sm flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                {{ session('success') }}
            </div>
        @endif

        <div class="space-y-6">
            @forelse($productions as $item)
            <div class="border border-slate-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                
                {{-- Header --}}
                <div class="flex flex-col md:flex-row justify-between items-start mb-4">
                    <div class="mb-2 md:mb-0">
                        <h4 class="text-xl font-bold text-black">ORD-{{ str_pad($item->id, 3, '0', STR_PAD_LEFT) }}</h4>
                        <p class="text-slate-500 text-sm font-medium">{{ $item->nama_pelanggan }}</p>
                        <p class="text-slate-400 text-xs mt-1">{{ \Carbon\Carbon::parse($item->tanggal_pesanan)->format('Y-m-d') }}</p>
                    </div>
                    
                    {{-- Badge Status (Ambil dari Controller, tidak ada PHP di sini lagi) --}}
                    <span class="{{ $item->warna_badge }} text-white text-xs font-bold px-4 py-1.5 rounded-full self-start">
                        {{ $item->label_status }}
                    </span>
                </div>

                {{-- Detail --}}
                <div class="space-y-2 mb-6 text-sm text-slate-800">
                    <div class="flex flex-col sm:flex-row">
                        <span class="font-bold w-40 shrink-0 text-slate-900">Layanan:</span>
                        <span>{{ $item->layanan }}</span>
                    </div>
                    <div class="flex flex-col sm:flex-row">
                        <span class="font-bold w-40 shrink-0 text-slate-900">Jumlah:</span>
                        <span>{{ $item->jumlah }} pcs</span>
                    </div>
                    <div class="flex flex-col sm:flex-row">
                        <span class="font-bold w-40 shrink-0 text-slate-900">Spesifikasi:</span>
                        <span>{{ $item->spesifikasi }}</span>
                    </div>
                    <div class="flex flex-col sm:flex-row">
                        <span class="font-bold w-40 shrink-0 text-slate-900">File Desain:</span>
                        <span class="text-slate-600 font-medium">{{ $item->nama_file_desain }}</span>
                    </div>
                </div>

                {{-- Tombol Aksi --}}
                <div class="flex flex-wrap gap-3 mt-4">
                    @if(in_array($item->status_produksi, ['Diproses', 'Produksi', 'Sedang Diproduksi']))
                        <form action="{{ route('produksi.productions.complete', $item->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-black hover:bg-slate-800 text-white text-sm font-bold px-5 py-2.5 rounded-md flex items-center gap-2 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                Selesai Produksi
                            </button>
                        </form>
                        <button onclick="openModal({{ $item->id }})" class="bg-white border border-slate-300 text-slate-700 hover:bg-slate-50 text-sm font-bold px-5 py-2.5 rounded-md flex items-center gap-2 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                            Laporkan Kendala
                        </button>
                    @elseif(in_array($item->status_produksi, ['Pending', 'Menunggu', 'Desain Disetujui']))
                        <form action="{{ route('produksi.productions.start', $item->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-black hover:bg-slate-800 text-white text-sm font-bold px-5 py-2.5 rounded-md flex items-center gap-2 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                                Mulai Produksi
                            </button>
                        </form>
                    @endif

                    <a href="{{ route('produksi.print', $item->id) }}" target="_blank" class="bg-white border border-slate-300 text-slate-700 hover:bg-slate-50 text-sm font-bold px-5 py-2.5 rounded-md flex items-center gap-2 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                        Print Job Sheet
                    </a>
                </div>
            </div>
            @empty
            <div class="text-center py-16 bg-white border border-dashed border-slate-300 rounded-lg">
                <p class="text-slate-500 font-medium">Data pesanan tidak ditemukan.</p>
            </div>
            @endforelse
        </div>

        <div class="mt-8">{{ $productions->links() }}</div>
    </div>
</div>

{{-- MODAL KENDALA (Perbaikan Konflik CSS Hidden vs Flex) --}}
<div id="modal" class="fixed inset-0 z-50 hidden bg-black/40 backdrop-blur-sm transition-all items-center justify-center">
    <div class="bg-white p-6 rounded-xl w-full max-w-md shadow-2xl">
        <div class="flex justify-between items-center mb-5">
            <h3 class="font-bold text-lg text-black">Lapor Kendala</h3>
            <button onclick="closeModal()" class="text-slate-400 hover:text-black transition text-2xl leading-none">&times;</button>
        </div>
        <form action="{{ route('produksi.issues.store') }}" method="POST">
            @csrf 
            <input type="hidden" name="pesanan_id" id="modal-id">
            <div class="mb-5">
                <label class="block text-sm font-bold text-slate-700 mb-2">Deskripsi Masalah</label>
                <textarea name="deskripsi" class="w-full border border-slate-300 rounded-lg p-3 focus:ring-2 focus:ring-black focus:border-transparent outline-none transition text-sm text-slate-800" rows="4" required placeholder="Jelaskan detail kendala secara singkat..."></textarea>
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeModal()" class="px-5 py-2.5 rounded-lg border border-slate-300 text-slate-700 text-sm font-bold hover:bg-slate-50 transition">Batal</button>
                <button type="submit" class="px-5 py-2.5 rounded-lg bg-black text-white text-sm font-bold hover:bg-slate-800 transition shadow-sm">Kirim Laporan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModal(id) {
        document.getElementById('modal-id').value = id;
        const modal = document.getElementById('modal');
        modal.classList.remove('hidden');
        modal.classList.add('flex'); // Tambahkan flex hanya saat dibuka
    }

    function closeModal() {
        const modal = document.getElementById('modal');
        modal.classList.add('hidden');
        modal.classList.remove('flex'); // Hapus flex saat ditutup
    }
</script>
@endsection