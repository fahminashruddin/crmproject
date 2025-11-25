@extends('admin.layout')

@section('title', 'Pembayaran')

@section('content')
    <div class="mb-6">
        <h1 class="text-3xl font-extrabold">Pembayaran</h1>
        <p class="text-sm text-gray-500 mt-1">Kelola dan verifikasi pembayaran pelanggan</p>
    </div>

    <div class="space-y-4">
        @forelse($payments as $p)
            <div class="border rounded-lg p-4 bg-white">
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <h3 class="font-semibold">Pembayaran #{{ $p->id }} untuk Pesanan #{{ $p->pesanan_id ?? '—' }}</h3>
                        <div class="text-xs text-gray-500">Status: {{ $p->status }} • {{ $p->created_at ?? '' }}</div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm font-semibold">Rp {{ number_format($p->nominal ?? 0, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-sm text-gray-500">Belum ada pembayaran.</div>
        @endforelse
    </div>

@endsection
