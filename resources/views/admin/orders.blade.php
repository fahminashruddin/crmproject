@extends('layouts.admin')

@section('title', 'Kelola Pesanan')

@section('content')
    <div class="px-8 py-8" x-data="{ showForm: false }">

        <div class="flex justify-between items-start mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Kelola Pesanan</h1>
                <p class="text-gray-500 mt-1">Kelola semua pesanan pelanggan</p>
            </div>

            <button @click="showForm = !showForm"
                    class="bg-gray-900 hover:bg-gray-800 text-white px-4 py-2 rounded-lg flex items-center gap-2 text-sm font-medium transition shadow-sm">
                <i data-lucide="plus" x-show="!showForm" class="w-4 h-4"></i>
                <i data-lucide="x" x-show="showForm" class="w-4 h-4" style="display: none;"></i>
                <span x-text="showForm ? 'Tutup Form' : 'Tambah Pesanan'"></span>
            </button>
        </div>

        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg flex items-center gap-2 text-sm">
                <i data-lucide="check-circle" class="h-5 w-5"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <div x-show="showForm"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform -translate-y-4"
             x-transition:enter-end="opacity-100 transform translate-y-0"
             class="bg-white border border-gray-200 rounded-xl p-6 mb-8 shadow-sm"
             style="display: none;">

            <div class="mb-6 border-b border-gray-100 pb-4">
                <h2 class="text-lg font-bold text-gray-900">Tambah Pesanan Baru</h2>
                <p class="text-sm text-gray-500">Isi form di bawah untuk menambah pesanan baru</p>
            </div>

            <form action="{{ route('admin.orders.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Pelanggan *</label>
                        <input type="text" name="nama_pelanggan" required class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-slate-900 focus:outline-none text-sm" placeholder="Masukkan nama pelanggan">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">No. Telepon</label>
                        <input type="text" name="no_telepon" required class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-slate-900 focus:outline-none text-sm" placeholder="Masukkan nomor telepon">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" required class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-slate-900 focus:outline-none text-sm" placeholder="Masukkan email">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Layanan *</label>
                        <select name="jenis_layanan" required class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-slate-900 focus:outline-none text-sm bg-white">
                            <option value="">Pilih jenis layanan</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}">{{ $service->nama_layanan }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah *</label>
                        <input type="number" name="jumlah" required min="1" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-slate-900 focus:outline-none text-sm" placeholder="Masukkan jumlah">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Total Harga (Rp)</label>
                        <input type="number" name="total_harga" required min="0" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-slate-900 focus:outline-none text-sm" placeholder="Masukkan total harga">
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Spesifikasi</label>
                    <textarea name="spesifikasi" rows="3" required class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-slate-900 focus:outline-none text-sm" placeholder="Masukkan spesifikasi detail"></textarea>
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="bg-slate-900 hover:bg-slate-800 text-white px-6 py-2 rounded-lg text-sm font-medium transition shadow-sm">
                        Simpan Pesanan
                    </button>
                    <button type="button" @click="showForm = false" class="border border-gray-300 text-gray-700 hover:bg-gray-50 px-6 py-2 rounded-lg text-sm font-medium transition">
                        Batal
                    </button>
                </div>
            </form>
        </div>

        <form action="{{ route('admin.orders') }}" method="GET" class="flex gap-4 mb-8">
            <div class="relative flex-1">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i data-lucide="search" class="h-5 w-5 text-gray-400"></i>
                </span>
                <input type="text" name="search" value="{{ request('search') }}"
                       class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-200 text-sm"
                       placeholder="Cari pesanan atau nama pelanggan...">
            </div>

            <div class="w-48 relative">
                <select name="status" onchange="this.form.submit()"
                        class="w-full pl-3 pr-8 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-200 text-sm bg-white cursor-pointer appearance-none">
                    <option value="">Semua Status</option>
                    @foreach($allStatuses as $s)
                        <option value="{{ $s->nama_status }}" {{ request('status') == $s->nama_status ? 'selected' : '' }}>
                            {{ $s->nama_status }}
                        </option>
                    @endforeach
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-500">
                    <i data-lucide="chevron-down" class="h-4 w-4"></i>
                </div>
            </div>
        </form>

        <h2 class="text-xl font-bold mb-4">Daftar Pesanan ({{ $orders->total() }})</h2>

        <div class="space-y-4">
            @forelse($orders as $order)
                <div class="border border-gray-200 rounded-xl p-6 bg-white hover:border-gray-300 transition-colors shadow-sm">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h3 class="font-bold text-lg text-gray-900">ORD-{{ str_pad($order->id, 3, '0', STR_PAD_LEFT) }}</h3>
                            <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($order->tanggal_pesanan)->format('d M Y') }}</p>
                        </div>
                        <div class="flex gap-2">
                            @php
                                $status = strtolower($order->statusPesanan->nama_status);
                                $bgStatus = 'bg-gray-100 text-gray-700';
                                if($status == 'selesai') $bgStatus = 'bg-green-100 text-green-800';
                                elseif($status == 'produksi') $bgStatus = 'bg-blue-100 text-blue-800';
                                elseif($status == 'desain') $bgStatus = 'bg-purple-100 text-purple-800';
                                elseif($status == 'pending') $bgStatus = 'bg-yellow-100 text-yellow-800';
                            @endphp
                            <span class="{{ $bgStatus }} px-3 py-1 rounded-full text-xs font-medium">{{ $order->statusPesanan->nama_status }}</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <div>
                            <p class="text-sm font-semibold text-gray-900 mb-1">Pelanggan:</p>
                            <p class="text-sm font-medium">{{ $order->pelanggan->nama ?? 'Pelanggan Umum' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900 mb-1">Layanan:</p>
                            <p class="text-sm font-medium">{{ $order->detailPesanans->first()?->jenisLayanan->nama_layanan ?? '-' }}</p>
                            <p class="text-sm text-gray-500">{{ $order->detailPesanans->sum('jumlah') }} pcs</p>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900 mb-1">Total:</p>
                            <p class="text-xl font-bold">Rp {{ number_format($order->detailPesanans->sum(fn($d) => $d->jumlah * $d->harga_satuan), 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <div class="mb-6">
                        <p class="text-sm font-semibold text-gray-900 mb-1">Spesifikasi:</p>
                        <p class="text-sm text-gray-500">{{ $order->catatan ?? '-' }}</p>
                    </div>

                    <div class="pt-4 border-t border-gray-100">
                        <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="inline-block">
                            @csrf @method('PATCH')
                            <div class="relative group">
                                <select name="status_id" onchange="this.form.submit()"
                                        class="appearance-none border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 pl-4 pr-10 py-2 rounded-lg text-sm font-medium cursor-pointer focus:outline-none focus:ring-2 focus:ring-gray-200">
                                    <option disabled selected>Ubah Status</option>
                                    @foreach($allStatuses as $s)
                                        <option value="{{ $s->id }}">{{ $s->nama_status }}</option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                    <i data-lucide="chevron-down" class="h-4 w-4"></i>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            @empty
                <div class="text-center py-12 text-gray-500">Belum ada pesanan.</div>
            @endforelse
        </div>

        @if($orders->hasPages())
        <div class="mt-8">{{ $orders->withQueryString()->links() }}</div>
        @endif

    </div>
@endsection
