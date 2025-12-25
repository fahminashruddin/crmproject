@extends('produksi.layout')

@section('title', 'Inventori')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div>
        <h2 class="text-3xl font-bold text-slate-900">Inventori</h2>
        <p class="text-slate-500 mt-1">Kelola stok bahan dan supplies</p>
    </div>

    {{-- Card Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($inventorys as $inventory)
        <div class="border border-slate-200 rounded-lg p-4 hover:shadow-md transition-shadow bg-white">
            {{-- Header Card --}}
            <div class="flex justify-between items-start mb-3">
                <div class="flex-1">
                    <h4 class="font-semibold text-slate-900 text-base">{{ $inventory->nama_produk }}</h4>
                </div>
            </div>

            {{-- Jumlah --}}
            <div class="mb-3">
                <p class="text-3xl font-bold text-slate-900">{{ $inventory->jumlah }}</p>
                <p class="text-sm text-slate-500 mt-1">{{ $inventory->satuan }}</p>
            </div>

            {{-- Status --}}
            <div>
                @php
                    $status = 'Normal';
                    $statusClass = 'bg-green-600 text-white';
                    if($inventory->jumlah <= 5) {
                        $status = 'Kritis';
                        $statusClass = 'bg-red-600 text-white';
                    } elseif($inventory->jumlah <= 10) {
                        $status = 'Rendah';
                        $statusClass = 'bg-yellow-600 text-white';
                    }
                @endphp
                <span class="inline-flex items-center rounded px-3 py-1 text-sm font-semibold {{ $statusClass }}">
                    {{ $status }}
                </span>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-12 text-slate-500">
            <i data-lucide="inbox" class="h-12 w-12 mx-auto mb-3 text-slate-300"></i>
            <p class="text-lg">Belum ada data inventori</p>
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($inventorys->hasPages())
    <div class="flex justify-center gap-2 mt-6">
        {{ $inventorys->links() }}
    </div>
    @endif
</div>

<script>
    lucide.createIcons();
</script>
@endsection
