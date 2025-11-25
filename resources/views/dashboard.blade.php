<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - CRM Percetakan</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen">

    @include('partials.navbar')

    <main class="ml-64 pt-20 px-8"> <!-- offset untuk sidebar + topbar -->
        <div class="max-w-7xl mx-auto">
            <div class="mb-6">
                <h1 class="text-3xl font-extrabold">Dashboard Administrator</h1>
                <p class="text-sm text-gray-500 mt-1">Kelola semua aspek operasional percetakan</p>
            </div>

            <!-- Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Total Pesanan</p>
                        <p class="mt-2 text-2xl font-semibold text-gray-900">{{ number_format($totalPesanan) }}</p>
                        <p class="text-xs text-gray-400 mt-1">Semua pesanan</p>
                    </div>
                    <div class="text-gray-300">
                        <!-- icon -->
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h18v18H3V3z"/></svg>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Pesanan Selesai</p>
                        <p class="mt-2 text-2xl font-semibold text-gray-900">{{ number_format($pesananSelesai) }}</p>
                        <p class="text-xs text-gray-400 mt-1">{{ $totalPesanan ? round(($pesananSelesai / max($totalPesanan,1)) * 100) . '% dari total' : '—' }}</p>
                    </div>
                    <div class="text-gray-300">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Pembayaran Pending</p>
                        <p class="mt-2 text-2xl font-semibold text-gray-900">{{ number_format($pembayaranPending) }}</p>
                        <p class="text-xs text-gray-400 mt-1">Perlu verifikasi</p>
                    </div>
                    <div class="text-gray-300">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3"/></svg>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Total Pendapatan</p>
                        <p class="mt-2 text-2xl font-semibold text-gray-900">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
                        <p class="text-xs text-gray-400 mt-1">Pembayaran terverifikasi</p>
                    </div>
                    <div class="text-gray-300">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 1.567-3 3.5S10.343 15 12 15s3-1.567 3-3.5S13.657 8 12 8z"/></svg>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
                <div class="lg:col-span-2 bg-white rounded-lg shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-medium text-gray-900">Pesanan Terbaru</h3>
                    <div class="mt-4 space-y-4">
                        @forelse($pesananTerbaru as $p)
                            <div class="flex items-center justify-between border rounded-md p-4">
                                <div>
                                    <div class="text-sm font-semibold text-gray-900">{{ $p->pelanggan_nama ?? 'Pelanggan' }}</div>
                                    <div class="text-xs text-gray-500">{{ $p->nama_status ?? '—' }}</div>
                                </div>
                                <div class="flex items-center gap-4">
                                    <div class="text-sm text-gray-500">{{ date('d M Y', strtotime($p->tanggal_pesanan)) }}</div>
                                    <div>
                                        <span class="px-3 py-1 rounded-full text-xs bg-gray-900 text-white">{{ $p->nama_status ? \Illuminate\Support\Str::limit($p->nama_status, 20) : '—' }}</span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-sm text-gray-500">Tidak ada pesanan terbaru.</div>
                        @endforelse
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-medium text-gray-900">Aktivitas User</h3>
                    <div class="mt-4 space-y-3">
                        @forelse($aktivitasUser as $u)
                            <div class="flex items-center justify-between border rounded-md p-4">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $u->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $u->email }}</div>
                                </div>
                                <div>
                                    <span class="px-3 py-1 text-xs rounded-full bg-gray-100 text-gray-700">{{ $u->nama_role ?? '—' }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="text-sm text-gray-500">Belum ada pengguna.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </main>

</body>
</html>
