@extends('layouts.admin')

@section('content')
    <div class="mb-8 animate-in fade-in slide-in-from-bottom-4 duration-500">
        <h1 class="text-3xl font-bold text-slate-900">Dashboard Administrator</h1>
        <p class="text-slate-500 mt-1">Kelola semua aspek operasional percetakan</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-all duration-300">
            <div class="flex justify-between items-start mb-4">
                <span class="text-sm font-medium text-slate-500">Total Pesanan</span>
                <div class="p-2 bg-slate-50 rounded-lg">
                    <i data-lucide="shopping-cart" class="h-4 w-4 text-slate-900"></i>
                </div>
            </div>
            <div class="text-3xl font-bold text-slate-900 mb-1">{{ $totalPesanan }}</div>
            <div class="text-xs text-slate-500">Semua pesanan</div>
        </div>

        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-all duration-300">
            <div class="flex justify-between items-start mb-4">
                <span class="text-sm font-medium text-slate-500">Pesanan Selesai</span>
                <div class="p-2 bg-slate-50 rounded-lg">
                    <i data-lucide="package" class="h-4 w-4 text-slate-900"></i>
                </div>
            </div>
            <div class="text-3xl font-bold text-slate-900 mb-1">{{ $pesananSelesai }}</div>
            <div class="text-xs text-slate-500">
                @if($totalPesanan > 0)
                    {{ round(($pesananSelesai / $totalPesanan) * 100) }}% dari total
                @else
                    0%
                @endif
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-all duration-300">
            <div class="flex justify-between items-start mb-4">
                <span class="text-sm font-medium text-slate-500">Pembayaran Pending</span>
                <div class="p-2 bg-slate-50 rounded-lg">
                    <i data-lucide="alert-circle" class="h-4 w-4 text-slate-900"></i>
                </div>
            </div>
            <div class="text-3xl font-bold text-slate-900 mb-1">{{ $pembayaranPending }}</div>
            <div class="text-xs text-slate-500">Perlu verifikasi</div>
        </div>

        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-all duration-300">
            <div class="flex justify-between items-start mb-4">
                <span class="text-sm font-medium text-slate-500">Total Pendapatan</span>
                <div class="p-2 bg-slate-50 rounded-lg">
                    <i data-lucide="dollar-sign" class="h-4 w-4 text-slate-900"></i>
                </div>
            </div>
            <div class="text-3xl font-bold text-slate-900 mb-1">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
            <div class="text-xs text-slate-500">Pembayaran terverifikasi</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm h-full">
            <div class="p-6 border-b border-gray-100">
                <h2 class="text-lg font-bold text-slate-900">Pesanan Terbaru</h2>
            </div>
            <div class="p-6 space-y-4">
                @forelse($pesananTerbaru as $order)
                <div class="flex items-center justify-between p-4 rounded-lg bg-slate-50 border border-transparent hover:border-gray-200 transition-colors">
                    <div>
                        <h3 class="font-bold text-slate-900 text-sm">{{ $order->pelanggan_nama }}</h3>
                        <p class="text-xs text-slate-500 mt-1">{{ $order->jenis_layanan ?? 'Layanan Cetak' }}</p>
                    </div>

                    @php
                        $status = strtolower($order->nama_status);
                        $badgeText = $order->nama_status;
                        if ($status == 'desain') $badgeText = 'Proses Desain';
                        if ($status == 'pending') $badgeText = 'Menunggu Konfirmasi';
                        if ($status == 'produksi') $badgeText = 'Produksi';
                        if ($status == 'selesai') $badgeText = 'Selesai';
                    @endphp

                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide bg-slate-900 text-white">
                        {{ $badgeText }}
                    </span>
                </div>
                @empty
                <div class="text-center py-8 text-slate-400">Belum ada pesanan terbaru</div>
                @endforelse
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm h-full">
            <div class="p-6 border-b border-gray-100">
                <h2 class="text-lg font-bold text-slate-900">Aktivitas User</h2>
            </div>
            <div class="p-6 space-y-4">
                @forelse($aktivitasUser as $user)
                <div class="flex items-center justify-between p-4 rounded-lg border border-gray-100 hover:border-gray-300 transition-colors">
                    <div class="flex items-center gap-3">
                        <div class="h-8 w-8 rounded-full bg-slate-100 flex items-center justify-center text-xs font-bold text-slate-700">
                            {{ substr($user->name, 0, 2) }}
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-900 text-sm">{{ $user->name }}</h3>
                            <p class="text-xs text-slate-500">{{ $user->email }}</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium border border-gray-200 text-slate-600 bg-white">
                        {{ ucfirst($user->nama_role) }}
                    </span>
                </div>
                @empty
                 <div class="text-center py-8 text-slate-400">Belum ada user</div>
                @endforelse
            </div>
        </div>

    </div>
@endsection
