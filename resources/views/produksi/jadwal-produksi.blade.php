@extends('produksi.layout')

@section('title', 'Jadwal Produksi')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div>
        <h2 class="text-3xl font-bold text-slate-900">Jadwal Produksi</h2>
        <p class="text-slate-500 mt-1">Kelola jadwal dan kapasitas produksi</p>
    </div>

    {{-- Jadwal List --}}
    <div class="space-y-4">
        <h3 class="text-xl font-semibold text-slate-900">Jadwal Minggu Ini</h3>

        <div class="space-y-3">
            @forelse($jadwals as $jadwal)
            <div class="border rounded-lg p-4 hover:shadow-md transition-shadow bg-white">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <div class="flex items-center gap-2">
                            <h4 class="font-semibold text-slate-900">ORD-{{ str_pad($jadwal->pesanan_id, 3, '0', STR_PAD_LEFT) }}</h4>
                        </div>
                        <p class="text-sm text-slate-600 mt-1">{{ $jadwal->pelanggan_nama ?? '-' }}</p>
                        <p class="text-sm text-slate-500 mt-2">
                            <i data-lucide="calendar" class="h-4 w-4 inline mr-1"></i>
                            {{ date('d/m/Y', strtotime($jadwal->tanggal_mulai)) }} - {{ date('d/m/Y', strtotime($jadwal->tanggal_selesai)) }}
                        </p>
                    </div>
                    <div class="flex flex-col items-end gap-2">
                        @php
                            // Mapping status dari pesanans.status_pesanan_id
                            $statusLabels = [
                                'Pending' => 'Pending',
                                'Menunggu' => 'Menunggu',
                                'Proses Desain' => 'Proses Desain',
                                'Desain Disetujui' => 'Desain Disetujui',
                                'Diproses' => 'Sedang Diproduksi',
                                'Produksi' => 'Sedang Diproduksi',
                                'Selesai' => 'Selesai',
                                'Dibatalkan' => 'Dibatalkan',
                            ];
                            $statusColors = [
                                'Pending' => 'bg-yellow-100 text-yellow-700',
                                'Menunggu' => 'bg-yellow-100 text-yellow-700',
                                'Proses Desain' => 'bg-orange-100 text-orange-700',
                                'Desain Disetujui' => 'bg-blue-100 text-blue-700',
                                'Diproses' => 'bg-blue-100 text-blue-700',
                                'Produksi' => 'bg-blue-100 text-blue-700',
                                'Selesai' => 'bg-green-100 text-green-700',
                                'Dibatalkan' => 'bg-red-100 text-red-700',
                            ];
                            $statusDisplay = $jadwal->nama_status ?? 'Unknown';
                        @endphp
                        <span class="inline-flex items-center rounded-full {{ $statusColors[$statusDisplay] ?? 'bg-slate-100 text-slate-700' }} px-3 py-1 text-xs font-semibold">
                            {{ $statusLabels[$statusDisplay] ?? $statusDisplay }}
                        </span>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-12 text-slate-500">
                <i data-lucide="inbox" class="h-12 w-12 mx-auto mb-3 text-slate-300"></i>
                <p class="text-lg">Belum ada jadwal produksi</p>
            </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($jadwals->hasPages())
        <div class="flex justify-center gap-2 mt-6">
            {{ $jadwals->links() }}
        </div>
        @endif
    </div>
</div>

<script>
    lucide.createIcons();
</script>
@endsection
