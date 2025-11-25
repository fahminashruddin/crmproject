@extends('admin.layout')

@section('title', 'Kelola Pesanan')

@section('content')
    <div class="mb-6">
        <h1 class="text-3xl font-extrabold">Kelola Pesanan</h1>
        <p class="text-sm text-gray-500 mt-1">Daftar semua pesanan</p>
    </div>

    <div class="space-y-4">
        @forelse($orders as $o)
            <div class="border rounded-lg p-4 bg-white">
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <h3 class="font-semibold">#{{ $o->id }} — {{ $o->pelanggan_nama ?? 'Pelanggan' }}</h3>
                        <div class="text-xs text-gray-500">{{ $o->nama_status ?? '—' }} • {{ date('d M Y', strtotime($o->tanggal_pesanan)) }}</div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm font-semibold">Rp {{ number_format($o->harga_total ?? 0, 0, ',', '.') }}</div>
                    </div>
                </div>
                <div class="text-sm text-gray-700">{{ $o->keterangan ?? '' }}</div>
            </div>
        @empty
            <div class="text-sm text-gray-500">Belum ada pesanan.</div>
        @endforelse
    </div>

@endsection
