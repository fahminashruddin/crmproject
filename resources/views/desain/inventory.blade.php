@extends('desain.layout')

@section('title', 'Inventory')

@section('content')
<div class="space-y-6">
    {{-- Header & Button --}}
    <div class="flex justify-between items-start">
        <div>
            <h2 class="text-3xl font-bold text-slate-900">Inventory</h2>
            <p class="text-slate-500 mt-1">Kelola stok dan inventori bahan produksi</p>
        </div>
        <button onclick="openAddModal()" 
            class="flex items-center gap-2 bg-slate-900 text-white px-4 py-2 rounded-lg hover:bg-slate-800 transition-colors">
            <i data-lucide="plus" class="h-5 w-5"></i>
            Tambah Stok
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
                <h3 class="text-xl font-bold" id="modalTitle">Tambah Stok Inventory</h3>
                <button onclick="document.getElementById('formModal').classList.add('hidden')" 
                    class="text-slate-500 hover:text-slate-700">
                    <i data-lucide="x" class="h-5 w-5"></i>
                </button>
            </div>

            <form id="inventoryForm" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" id="inventoryId" value="">
                <input type="hidden" id="methodField" name="_method" value="POST">

                {{-- Nama Produk --}}
                <div>
                    <label class="block text-sm font-medium text-slate-900 mb-2">Nama Produk</label>
                    <input type="text" id="namaProduk" name="nama_produk" required placeholder="Contoh: Kertas A4 Premium"
                        class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-slate-900">
                    @error('nama_produk')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Jumlah --}}
                <div>
                    <label class="block text-sm font-medium text-slate-900 mb-2">Jumlah</label>
                    <input type="number" id="jumlah" name="jumlah" required min="0" placeholder="0"
                        class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-slate-900">
                    @error('jumlah')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Satuan --}}
                <div>
                    <label class="block text-sm font-medium text-slate-900 mb-2">Satuan</label>
                    <select id="satuan" name="satuan" required 
                        class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-slate-900">
                        <option value="">Pilih Satuan</option>
                        <option value="pcs">Pcs (Pieces)</option>
                        <option value="ream">Ream</option>
                        <option value="roll">Roll</option>
                        <option value="kg">Kg</option>
                        <option value="box">Box</option>
                        <option value="liter">Liter</option>
                        <option value="meter">Meter</option>
                    </select>
                    @error('satuan')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Lokasi --}}
                <div>
                    <label class="block text-sm font-medium text-slate-900 mb-2">Lokasi Penyimpanan (Opsional)</label>
                    <input type="text" id="lokasi" name="lokasi" placeholder="Contoh: Rak A1, Gudang Utama"
                        class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-slate-900">
                    @error('lokasi')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Keterangan --}}
                <div>
                    <label class="block text-sm font-medium text-slate-900 mb-2">Keterangan (Opsional)</label>
                    <textarea id="keterangan" name="keterangan" rows="2" placeholder="Catatan tambahan..."
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
                    <th class="px-6 py-3 text-left text-sm font-semibold text-slate-900">Nama Produk</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-slate-900">Jumlah</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-slate-900">Satuan</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-slate-900">Lokasi</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-slate-900">Status Stok</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold text-slate-900">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($inventorys as $inventory)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-3 text-sm text-slate-900">{{ $inventory->nama_produk ?? '-' }}</td>
                    <td class="px-6 py-3 text-sm text-slate-700">{{ $inventory->jumlah ?? '0' }}</td>
                    <td class="px-6 py-3 text-sm text-slate-700">{{ $inventory->satuan ?? '-' }}</td>
                    <td class="px-6 py-3 text-sm text-slate-700">{{ $inventory->lokasi ?? '-' }}</td>
                    <td class="px-6 py-3 text-sm">
                        @php
                            $status = 'Tersedia';
                            $statusClass = 'bg-green-100 text-green-700';
                            if($inventory->jumlah <= 10) {
                                $status = 'Menipis';
                                $statusClass = 'bg-yellow-100 text-yellow-700';
                            }
                            if($inventory->jumlah <= 0) {
                                $status = 'Habis';
                                $statusClass = 'bg-red-100 text-red-700';
                            }
                        @endphp
                        <span class="inline-flex items-center rounded-full {{ $statusClass }} px-3 py-1 text-sm font-medium">
                            {{ $status }}
                        </span>
                    </td>
                    <td class="px-6 py-3 text-center">
                        <div class="flex gap-2 justify-center">
                            <button onclick="editInventory({{ $inventory->id }}, '{{ $inventory->nama_produk }}', {{ $inventory->jumlah }}, '{{ $inventory->satuan }}', '{{ $inventory->lokasi ?? '' }}', '{{ $inventory->keterangan ?? '' }}')"
                                class="text-blue-600 hover:text-blue-800 hover:bg-blue-50 px-2 py-1 rounded transition-colors">
                                <i data-lucide="edit-2" class="h-4 w-4"></i>
                            </button>
                            <form action="{{ route('desain.inventory.delete', $inventory->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 hover:bg-red-50 px-2 py-1 rounded transition-colors">
                                    <i data-lucide="trash-2" class="h-4 w-4"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-slate-500">
                        <div class="flex flex-col items-center gap-2">
                            <i data-lucide="inbox" class="h-8 w-8 text-slate-300"></i>
                            <p>Belum ada data inventory</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($inventorys->hasPages())
    <div class="flex justify-center gap-2">
        {{ $inventorys->links() }}
    </div>
    @endif
</div>

<script>
    lucide.createIcons();

    // Function untuk open modal tambah baru
    function openAddModal() {
        document.getElementById('formModal').classList.remove('hidden');
        document.getElementById('modalTitle').textContent = 'Tambah Stok Inventory';
        document.getElementById('inventoryForm').action = '{{ route("desain.inventory.store") }}';
        document.getElementById('methodField').value = 'POST';
        document.getElementById('inventoryId').value = '';
        document.getElementById('inventoryForm').reset();
    }

    // Function untuk edit inventory
    function editInventory(id, nama, jumlah, satuan, lokasi, keterangan) {
        document.getElementById('formModal').classList.remove('hidden');
        document.getElementById('modalTitle').textContent = 'Edit Stok Inventory';
        document.getElementById('inventoryForm').action = '{{ route("desain.inventory.update", ":id") }}'.replace(':id', id);
        document.getElementById('methodField').value = 'PUT';
        document.getElementById('inventoryId').value = id;
        document.getElementById('namaProduk').value = nama;
        document.getElementById('jumlah').value = jumlah;
        document.getElementById('satuan').value = satuan;
        document.getElementById('lokasi').value = lokasi;
        document.getElementById('keterangan').value = keterangan;
    }
</script>
@endsection
