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
        
        {{-- Card 2: Desain Disetujui --}}
        <div class="rounded-xl border bg-white p-6 shadow-sm">
            <div class="flex justify-between items-center pb-2">
                <h3 class="text-sm font-medium text-gray-500">Desain Disetujui</h3>
                <i data-lucide="check-circle" class="h-4 w-4 text-green-500"></i>
            </div>
            <div class="text-2xl font-bold">{{ $sedangProses ?? 1 }}</div>
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
                
                {{-- Header --}}
                <div class="flex justify-between items-start mb-3">
                    <div class="space-y-1">
                        <div class="text-lg font-bold text-gray-800">
                            ORD-{{ str_pad($item->id, 3, '0', STR_PAD_LEFT) }}
                        </div>
                        <div class="text-sm text-gray-600">{{ $item->nama_pelanggan }}</div>
                    </div>
                    
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

                {{-- Footer --}}
                <div class="mt-4 flex justify-between items-center pt-3 border-t">
                    <div class="text-xs text-gray-400">
                        Tanggal Pesanan:
                        {{ \Carbon\Carbon::parse($item->created_at)->format('d M') }}
                    </div>

                    {{-- BUTTON POPUP --}}
                    <button
                        onclick="openModal(this)"
                        data-order="ORD-{{ str_pad($item->id, 3, '0', STR_PAD_LEFT) }}"
                        data-nama="{{ $item->nama_pelanggan }}"
                        data-status="{{ $item->status_desain }}"
                        data-tanggal="{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}"
                        class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium
                               h-9 px-3 py-1 bg-black text-white hover:bg-gray-800">
                        Lihat Detail
                    </button>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center text-gray-500 py-8 border rounded-xl bg-white">
                <i data-lucide="inbox" class="h-6 w-6 mx-auto mb-2"></i>
                <p>Tidak ada tugas desain aktif saat ini.</p>
            </div>
            @endforelse
            
        </div>
    </div>
</div>

{{-- ================= MODAL DETAIL ================= --}}
<div id="detailModal"
     class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">

    <div class="bg-white rounded-xl w-full max-w-md p-6 space-y-4 relative">

        <button onclick="closeModal()"
                class="absolute top-3 right-3 text-gray-400 hover:text-black">
            ✕
        </button>

        <h3 class="text-xl font-bold">Detail Pesanan</h3>

        <div class="space-y-2 text-sm">
            <p><strong>Nomor Order:</strong> <span id="modal-order"></span></p>
            <p><strong>Nama Pelanggan:</strong> <span id="modal-nama"></span></p>
            <p><strong>Status Desain:</strong> <span id="modal-status"></span></p>
            <p><strong>Tanggal Pesanan:</strong> <span id="modal-tanggal"></span></p>
        </div>

        <div class="text-right pt-4">
            <button onclick="closeModal()"
                    class="px-4 py-2 bg-black text-white rounded-md text-sm">
                Tutup
            </button>
        </div>
    </div>
</div>

{{-- ================= SCRIPT ================= --}}
<script>
function openModal(btn) {
    document.getElementById('modal-order').innerText   = btn.dataset.order;
    document.getElementById('modal-nama').innerText    = btn.dataset.nama;
    document.getElementById('modal-status').innerText  = btn.dataset.status;
    document.getElementById('modal-tanggal').innerText = btn.dataset.tanggal;

    document.getElementById('detailModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('detailModal').classList.add('hidden');
}
</script>

@endsection
