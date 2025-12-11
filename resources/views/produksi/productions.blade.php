@extends('produksi.layout')
@section('title', 'Daftar Produksi')

@section('content')
<div class="max-w-7xl mx-auto font-sans text-slate-800">

    {{-- HEADER HALAMAN --}}
    <div class="flex flex-col md:flex-row justify-between gap-4 mb-8 border-b pb-5">
        <div>
            <h1 class="text-2xl font-bold text-black">Daftar Semua Produksi</h1>
            <p class="text-slate-500">Kelola semua pesanan produksi di sini.</p>
        </div>
        
        {{-- Filter & Search --}}
        <div class="flex gap-3">
            <a href="{{ url('/produksi/productions') }}" class="px-4 py-2 border rounded-lg text-sm {{ !request('status') ? 'bg-slate-900 text-white' : 'bg-white hover:bg-slate-50' }}">Semua</a>
            <a href="{{ url('/produksi/productions?status=Produksi') }}" class="px-4 py-2 border rounded-lg text-sm {{ request('status') == 'Produksi' ? 'bg-slate-900 text-white' : 'bg-white hover:bg-slate-50' }}">Sedang Proses</a>
            
            <form action="" method="GET" class="flex">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari pesanan..." class="border rounded-l-lg px-3 py-2 text-sm focus:ring-2 focus:ring-black outline-none">
                <button type="submit" class="bg-slate-200 px-3 rounded-r-lg hover:bg-slate-300">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                </button>
            </form>
        </div>
    </div>



    {{-- 2. Summary Cards Section --}}
    <div class="grid gap-4 md:grid-cols-3">
        {{-- Card: Menunggu Produksi --}}
        <div class="rounded-xl border bg-white text-card-foreground shadow-sm">
            <div class="p-6 flex flex-row items-center justify-between space-y-0 pb-2">
                <h3 class="tracking-tight text-sm font-medium">Menunggu Produksi</h3>
                {{-- Icon: Clock (Lucide) --}}
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 text-gray-500"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
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


    {{-- LIST PRODUKSI --}}
    <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-sm">
        <div class="mb-6">
            <h3 class="text-xl font-bold text-black">List Pesanan</h3>
            <p class="text-slate-500 text-sm">Menampilkan data produksi berdasarkan filter.</p>
        </div>

        <div class="space-y-6">
            {{-- PERBAIKAN DI SINI: Ubah $antrian menjadi $productions --}}
            @forelse($productions as $item)
            <div class="border border-slate-200 rounded-lg p-6">
                {{-- Header Card: ID, Toko, Tanggal, & Badge Status --}}
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h4 class="text-lg font-bold text-black">ORD-{{ str_pad($item->id, 3, '0', STR_PAD_LEFT) }}</h4>
                        <p class="text-slate-500 text-sm">{{ $item->nama_pelanggan }}</p>
                        <p class="text-slate-400 text-xs mt-1">{{ \Carbon\Carbon::parse($item->tanggal_pesanan)->isoFormat('Y-MM-DD') }}</p>
                    </div>
                    
                    {{-- Badge Status --}}
                    @if($item->status_produksi == 'Produksi')
                        <span class="bg-black text-white text-xs font-semibold px-3 py-1 rounded-full">Sedang Diproduksi</span>
                    @elseif($item->status_produksi == 'Selesai')
                         <span class="bg-green-600 text-white text-xs font-semibold px-3 py-1 rounded-full">Selesai</span>
                    @else
                        <span class="bg-slate-100 text-slate-600 text-xs font-semibold px-3 py-1 rounded-full border border-slate-200">{{ $item->status_produksi }}</span>
                    @endif
                </div>

                {{-- Detail Info --}}
                <div class="space-y-2 mb-6">
                    <div class="flex flex-col sm:flex-row sm:gap-2">
                        <span class="font-semibold text-sm w-32 shrink-0 text-slate-900">Layanan:</span>
                        <span class="text-sm text-slate-700">{{ $item->layanan }}</span>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:gap-2">
                        <span class="font-semibold text-sm w-32 shrink-0 text-slate-900">Jumlah:</span>
                        <span class="text-sm text-slate-700">{{ $item->jumlah }} pcs</span>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:gap-2">
                        <span class="font-semibold text-sm w-32 shrink-0 text-slate-900">Spesifikasi:</span>
                        <span class="text-sm text-slate-700">{{ $item->spesifikasi }}</span>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:gap-2">
                        <span class="font-semibold text-sm w-32 shrink-0 text-slate-900">File Desain:</span>
                        <a href="#" class="text-sm text-blue-600 hover:underline truncate">{{ $item->nama_file_desain }}</a>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex flex-wrap gap-3">
                    {{-- Tombol Utama --}}
                    @if($item->status_produksi == 'Produksi')
                        <form action="{{ url('/produksi/complete/' . $item->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-black hover:bg-slate-800 text-white text-sm font-medium px-4 py-2 rounded-md flex items-center gap-2 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                Selesai Produksi
                            </button>
                        </form>
                    @elseif($item->status_produksi == 'Menunggu' || $item->status_produksi == 'Desain Disetujui')
                         <form action="{{ url('/produksi/start/' . $item->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-black hover:bg-slate-800 text-white text-sm font-medium px-4 py-2 rounded-md flex items-center gap-2 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                                Mulai Produksi
                            </button>
                        </form>
                    @endif

                    {{-- Tombol Lapor Kendala --}}
                    <button onclick="openModal ({{ $item->id }})" class="bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 text-sm font-medium px-4 py-2 rounded-md flex items-center gap-2 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                        Laporkan Kendala
                    </button>

                    {{-- Tombol Print --}}
                    <a href="{{ url('/produksi/print/' . $item->id) }}" target="_blank" class="bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 text-sm font-medium px-4 py-2 rounded-md flex items-center gap-2 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                        Print Job Sheet
                    </a>
                </div>
            </div>
            @empty
            <div class="text-center py-10">
                <p class="text-slate-500">Tidak ada data produksi ditemukan.</p>
            </div>
            @endforelse
        </div>
        
        {{-- Pagination Link --}}
        <div class="mt-6">
            {{ $productions->links() }}
        </div>
    </div>
</div>

{{-- MODAL KENDALA --}}
<div id="modal" class="fixed inset-0 z-50 hidden bg-black/40 backdrop-blur-sm flex items-center justify-center">
    <div class="bg-white p-6 rounded-xl w-full max-w-md shadow-2xl transform transition-all">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-bold text-lg">Lapor Kendala</h3>
            <button onclick="document.getElementById('modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">&times;</button>
        </div>
        <form action="{{ route('produksi.issues.store') }}" method="POST">
            @csrf 
            <input type="hidden" name="pesanan_id" id="modal-id">
            <label class="block text-sm font-medium text-slate-700 mb-2">Deskripsi Masalah</label>
            <textarea name="deskripsi" class="w-full border border-slate-300 rounded-lg p-3 mb-4 focus:ring-2 focus:ring-black focus:border-black outline-none transition" rows="3" required placeholder="Contoh: Mesin macet, bahan habis..."></textarea>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('modal').classList.add('hidden')" class="border border-slate-300 text-slate-700 px-4 py-2 rounded-lg hover:bg-slate-50 transition">Batal</button>
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition shadow-sm">Kirim Laporan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModal(id) {
        document.getElementById('modal-id').value = id;
        document.getElementById('modal').classList.remove('hidden');
    }
</script>
@endsection