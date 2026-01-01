@extends('layouts.admin')

@section('title', 'Kelola Pembayaran')

@section('content')
    <div class="p-8">
        <h1 class="text-3xl font-bold text-gray-900">Kelola Pembayaran</h1>
        <p class="text-gray-500 mt-1">Verifikasi dan kelola pembayaran pelanggan</p>

        <div class="grid gap-4 md:grid-cols-3 mt-8 mb-8">

            {{-- Kartu 1: Menunggu Verifikasi --}}
            <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                <div class="flex flex-row items-center justify-between space-y-0 pb-2">
                    <h3 class="text-sm font-medium text-slate-500">Menunggu Verifikasi</h3>
                    <i data-lucide="clock" class="h-4 w-4 text-slate-400"></i>
                </div>
                <div class="text-2xl font-bold text-slate-900">{{ $pendingPayments->count() }}</div>
                <p class="text-xs text-slate-500">Pembayaran pending</p>
            </div>

            {{-- Kartu 2: Terverifikasi --}}
            <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                <div class="flex flex-row items-center justify-between space-y-0 pb-2">
                    <h3 class="text-sm font-medium text-slate-500">Terverifikasi</h3>
                    <i data-lucide="check-circle" class="h-4 w-4 text-slate-400"></i>
                </div>
                <div class="text-2xl font-bold text-slate-900">{{ $verifiedPayments->count() }}</div>
                <p class="text-xs text-slate-500">Pembayaran verified</p>
            </div>

            {{-- Kartu 3: Total Pendapatan --}}
            <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                <div class="flex flex-row items-center justify-between space-y-0 pb-2">
                    <h3 class="text-sm font-medium text-slate-500">Total Pendapatan</h3>
                    <i data-lucide="dollar-sign" class="h-4 w-4 text-slate-400"></i>
                </div>
                <div class="text-2xl font-bold text-slate-900">
                    Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                </div>
                <p class="text-xs text-slate-500">Pembayaran terverifikasi</p>
            </div>
        </div>

        <div class="space-y-8">

            {{-- 1. PEMBAYARAN MENUNGGU VERIFIKASI --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
                <div class="p-6 border-b border-gray-100">
                    <h2 class="text-lg font-bold text-slate-900">Pembayaran Menunggu Verifikasi</h2>
                    <p class="text-sm text-slate-500">{{ $pendingPayments->count() }} pembayaran menunggu verifikasi</p>
                </div>

                <div class="p-4 space-y-4">
                    @forelse($pendingPayments as $payment)
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex justify-between items-start mb-3">
                            <div class="text-sm">
                                <h3 class="font-bold text-slate-900">ORD-{{ $payment->pesanan->id ?? 'N/A' }}</h3>
                                <p class="text-slate-600">{{ $payment->pesanan->pelanggan->nama }}</p>
                                <p class="text-xs text-slate-400">{{ \Carbon\Carbon::parse($payment->created_at)->format('d M Y') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-xl">Rp {{ number_format($payment->nominal, 0, ',', '.') }}</p>
                                <span class="bg-red-500 text-white px-2 py-0.5 rounded-full text-xs font-medium">Menunggu Pembayaran</span>
                            </div>
                        </div>

                        <div class="text-sm mb-4">
                            <p><strong>Layanan:</strong> {{ $payment->service ?? '-' }}</p>
                            <p><strong>Jumlah:</strong> {{ $payment->qty ?? '-' }} pcs</p>
                        </div>

                        {{-- Form Aksi Verifikasi --}}
                        <form action="{{ route('admin.payments.verify', $payment->id) }}" method="POST" class="flex gap-2">
                            @csrf
                            <select name="payment_method" required class="border border-gray-300 rounded-lg text-sm py-1.5 pl-3 pr-8 bg-white appearance-none cursor-pointer">
                                <option value="">Pilih metode pembayaran</option>
                                @foreach($paymentMethods as $method)
                                    <option value="{{ $method }}">{{ $method }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-1.5 rounded-lg text-sm font-medium transition shadow-sm">
                                <i data-lucide="check" class="w-4 h-4 mr-2"></i> Verifikasi
                            </button>
                            <a href="{{ route('admin.payments.reject', $payment->id) }}" class="bg-red-600 hover:bg-red-700 text-white px-4 py-1.5 rounded-lg text-sm font-medium transition shadow-sm">
                                Tolak
                            </a>
                        </form>
                    </div>
                    @empty
                    <div class="text-center py-8 text-slate-400">Tidak ada pembayaran yang menunggu verifikasi.</div>
                    @endforelse
                </div>
            </div>

            {{-- 2. RIWAYAT PEMBAYARAN --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
                <div class="p-6 border-b border-gray-100">
                    <h2 class="text-lg font-bold text-slate-900">Riwayat Pembayaran</h2>
                    <p class="text-sm text-slate-500">Menampilkan semua transaksi masuk</p>
                </div>

                <div class="p-6 space-y-4">
                    {{-- Loop menggunakan variable $paymentsData (Semua data) --}}
                    @forelse($paymentsData as $payment)
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between p-4 border rounded-lg hover:bg-slate-50 transition-colors">
                        <div class="mb-2 sm:mb-0">
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-slate-900 text-lg">ORD-{{ str_pad($payment->pesanan->id, 3, '0', STR_PAD_LEFT) }}</span>
                                <span class="text-slate-600 font-medium">- {{ $payment->pesanan->pelanggan->nama }}</span>
                            </div>
                            <p class="text-sm text-slate-500 mt-1">
                                {{-- Tampilkan nama metode, jika null tampilkan strip --}}
                                {{ $payment->metodePembayaran->nama_metode ?? '-' }}
                                <span class="mx-1">•</span>
                                {{ \Carbon\Carbon::parse($payment->created_at)->format('d M Y H:i') }}
                            </p>
                        </div>

                        <div class="text-left sm:text-right">
                            <p class="font-bold text-slate-900 text-lg">Rp {{ number_format($payment->nominal, 0, ',', '.') }}</p>

                            {{-- Logika Warna Badge Status --}}
                            @php
                                $status = strtolower($payment->status);
                                $badgeClass = 'bg-gray-100 text-gray-600'; // Default
                                $label = ucfirst($status);

                                if ($status == 'verifikasi' || $status == 'verified') {
                                    $badgeClass = 'bg-green-100 text-green-700 border border-green-200';
                                    $label = 'Terverifikasi';
                                } elseif ($status == 'pending') {
                                    $badgeClass = 'bg-yellow-50 text-yellow-700 border border-yellow-200';
                                    $label = 'Menunggu';
                                } elseif ($status == 'gagal' || $status == 'failed') {
                                    $badgeClass = 'bg-red-50 text-red-700 border border-red-200';
                                    $label = 'Gagal / Ditolak';
                                }
                            @endphp

                            <span class="inline-flex items-center px-2.5 py-0.5 mt-1 rounded-full text-xs font-medium {{ $badgeClass }}">
                                {{ $label }}
                            </span>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8 text-slate-400 border-2 border-dashed border-gray-100 rounded-lg">
                        <i data-lucide="receipt" class="mx-auto h-10 w-10 mb-2 opacity-50"></i>
                        <p>Belum ada riwayat pembayaran.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
