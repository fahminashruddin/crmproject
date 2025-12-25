@extends('desain.layout')

@section('title', 'Jadwal Produksi')

@section('content')
<div class="space-y-6">
    {{-- Header & Button --}}
    <div class="flex justify-between items-start">
        <div>
            <h2 class="text-3xl font-bold text-slate-900">Jadwal Produksi</h2>
            <p class="text-slate-500 mt-1">Kelola dan pantau jadwal produksi pesanan</p>
        </div>
        <button onclick="document.getElementById('formModal').classList.toggle('hidden')" 
            class="flex items-center gap-2 bg-slate-900 text-white px-4 py-2 rounded-lg hover:bg-slate-800 transition-colors">
            <i data-lucide="plus" class="h-5 w-5"></i>
            Tambah Jadwal
        </button>
    </div>

    {{-- Success/Error Messages --}}
    @if($message = session('success'))
    <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-green-800">
        <div class="flex gap-3">
            <i data-lucide="check-circle" class="h-5 w-5 flex-shrink-0 text-green-600"></i>
            <p>{{ $message }}</p>
        </div>
    </div>
    @endif

    @if($message = session('error'))
    <div class="bg-red-50 border border-red-200 rounded-lg p-4 text-red-800">
        <div class="flex gap-3">
            <i data-lucide="alert-circle" class="h-5 w-5 flex-shrink-0 text-red-600"></i>
            <p>{{ $message }}</p>
        </div>
    </div>
    @endif

    {{-- Modal Form --}}
    <div id="formModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold">Tambah Jadwal Produksi</h3>
                <button onclick="document.getElementById('formModal').classList.add('hidden')" 
                    class="text-slate-500 hover:text-slate-700">
                    <i data-lucide="x" class="h-5 w-5"></i>
                </button>
            </div>

            <form action="{{ route('desain.jadwal-produksi.store') }}" method="POST" class="space-y-4">
                @csrf

                {{-- Pesanan Dropdown --}}
                <div>
                    <label class="block text-sm font-medium text-slate-900 mb-2">Pesanan</label>
                    <select name="pesanan_id" required 
                        class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-slate-900">
                        <option value="">Pilih Pesanan</option>
                        @foreach($pesanans as $pesanan)
                        <option value="{{ $pesanan->id }}">
                            #{{ $pesanan->id }} - {{ $pesanan->pelanggan_nama }}
                        </option>
                        @endforeach
                    </select>
                    @error('pesanan_id')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tanggal Mulai --}}
                <div>
                    <label class="block text-sm font-medium text-slate-900 mb-2">Tanggal Mulai</label>
                    <input type="date" name="tanggal_mulai" required 
                        class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-slate-900">
                    @error('tanggal_mulai')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tanggal Selesai --}}
                <div>
                    <label class="block text-sm font-medium text-slate-900 mb-2">Tanggal Selesai</label>
                    <input type="date" name="tanggal_selesai" required 
                        class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-slate-900">
                    @error('tanggal_selesai')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Catatan --}}
                <div>
                    <label class="block text-sm font-medium text-slate-900 mb-2">Catatan (Opsional)</label>
                    <textarea name="catatan" rows="3" 
                        class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-slate-900"></textarea>
                </div>

                {{-- Buttons --}}
                <div class="flex gap-3 pt-4">
                    <button type="button" onclick="document.getElementById('formModal').classList.add('hidden')" 
                        class="flex-1 px-4 py-2 border border-slate-300 rounded-lg text-slate-700 hover:bg-slate-50 transition-colors">
                        Batal
                    </button>
                    <button type="submit" 
                        class="flex-1 px-4 py-2 bg-slate-900 text-white rounded-lg hover:bg-slate-800 transition-colors">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-lg border overflow-hidden">
        <table class="w-full">
            <thead>
                <tr class="border-b bg-slate-50">
                    <th class="px-6 py-3 text-left text-sm font-semibold text-slate-900">ID Pesanan</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-slate-900">Pelanggan</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-slate-900">Tanggal Mulai</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-slate-900">Tanggal Selesai</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-slate-900">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($jadwals as $jadwal)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-3 text-sm text-slate-900">#{{ $jadwal->pesanan_id }}</td>
                    <td class="px-6 py-3 text-sm text-slate-700">{{ $jadwal->pelanggan_nama ?? '-' }}</td>
                    <td class="px-6 py-3 text-sm text-slate-700">
                        {{ $jadwal->tanggal_mulai ? date('d/m/Y', strtotime($jadwal->tanggal_mulai)) : '-' }}
                    </td>
                    <td class="px-6 py-3 text-sm text-slate-700">
                        {{ $jadwal->tanggal_selesai ? date('d/m/Y', strtotime($jadwal->tanggal_selesai)) : '-' }}
                    </td>
                    <td class="px-6 py-3 text-sm">
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
                        <span class="inline-flex items-center rounded-full {{ $statusColors[$statusDisplay] ?? 'bg-slate-100 text-slate-700' }} px-3 py-1 text-sm font-medium">
                            {{ $statusLabels[$statusDisplay] ?? $statusDisplay }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-slate-500">
                        <div class="flex flex-col items-center gap-2">
                            <i data-lucide="inbox" class="h-8 w-8 text-slate-300"></i>
                            <p>Belum ada jadwal produksi</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($jadwals->hasPages())
    <div class="flex justify-center gap-2">
        {{ $jadwals->links() }}
    </div>
    @endif
</div>

<script>
    lucide.createIcons();
</script>
@endsection
