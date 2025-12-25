@extends('desain.layout')

@section('title', 'Kelola Desain')

@section('content')

@php
    // Hitung statistik dari SEMUA data (allDesigns)
    $allDesigns = collect($allDesigns ?? []);
    
    $inProgress = $allDesigns->filter(fn($item) => strtolower($item->status_desain ?? '') == 'menunggu')->count();
    $revisi     = $allDesigns->filter(fn($item) => strtolower($item->status_desain ?? '') == 'revisi')->count();
    $done       = $allDesigns->filter(fn($item) => strtolower($item->status_desain ?? '') == 'disetujui')->count();
@endphp

<div class="mb-6">
    <h1 class="text-2xl font-bold tracking-tight">Kelola Desain</h1>
    <p class="text-sm text-slate-500">Kelola semua tugas desain yang sedang berlangsung.</p>
</div>

<div class="grid gap-4 md:grid-cols-3 mb-8">
    <div class="rounded-xl border bg-white p-6 shadow-sm">
        <h3 class="text-sm font-medium text-gray-500">Menunggu Desain</h3>
        <div class="text-2xl font-bold">{{ $inProgress }}</div>
    </div>

    <div class="rounded-xl border bg-white p-6 shadow-sm">
        <h3 class="text-sm font-medium text-gray-500">Perlu Revisi</h3>
        <div class="text-2xl font-bold">{{ $revisi }}</div>
    </div>

    <div class="rounded-xl border bg-white p-6 shadow-sm">
        <h3 class="text-sm font-medium text-gray-500">Desain Disetujui</h3>
        <div class="text-2xl font-bold">{{ $done }}</div>
    </div>
</div>

<div class="space-y-4">
    <h2 class="text-xl font-bold">Antrian Desain</h2>

    <div class="grid gap-4">
        @forelse ($designs as $design)
            <div class="rounded-xl border bg-white p-6 shadow-sm space-y-3">

                <div class="flex justify-between">
                    <div>
                        <h3 class="text-lg font-bold">{{ $design->nomor_order }}</h3>
                        <p class="text-sm text-slate-700">{{ $design->pelanggan }}</p>
                        <p class="text-xs text-slate-500">{{ $design->tanggal_order }}</p>
                    </div>

                    <span class="rounded-full px-3 py-1 text-xs font-medium
                        @if ($design->status_desain == 'Menunggu') bg-gray-100
                        @elseif ($design->status_desain == 'Revisi') bg-red-100
                        @elseif ($design->status_desain == 'Disetujui') bg-green-100
                        @endif">
                        {{ $design->status_desain }}
                    </span>
                </div>

                <div class="text-sm space-y-1">
                    <p><strong>Layanan:</strong> {{ $design->jenis_layanan_id }}</p>
                    <p><strong>Jumlah:</strong> {{ $design->jumlah }} pcs</p>
                    <p><strong>Catatan:</strong> {{ $design->catatan_desain }}</p>
                </div>

                <div class="flex gap-2 pt-3 border-t">
                    {{-- UPLOAD (TETAP) --}}
                    <button
                        onclick="openUploadModal('{{ $design->nomor_order }}')"
                        class="flex items-center gap-1 rounded-md bg-white border px-4 py-2 text-sm hover:bg-slate-50">
                        Upload Desain
                    </button>

                    {{-- SETUJUI (TETAP) --}}
                   <form action="{{ route('desain.setujui') }}" method="POST">
    @csrf
    <input type="hidden" name="nomor_order" value="{{ $design->nomor_order }}">

    <button
        type="submit"
        class="rounded-md bg-slate-900 px-4 py-2 text-sm text-white hover:bg-slate-800">
        Setujui Desain
    </button>
</form>

                    {{-- ✅ REVISI (BARU) --}}
                    <button
                        onclick="openRevisiModal('{{ $design->nomor_order }}')"
                        class="rounded-md border border-red-300 px-4 py-2 text-sm text-red-700 hover:bg-red-50">
                        Perlu Revisi
                    </button>
                </div>
            </div>
        @empty
            <div class="text-center text-gray-500">Tidak ada data</div>
        @endforelse
    </div>
</div>

{{-- ================= MODAL UPLOAD (TETAP) ================= --}}
<div id="uploadModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl w-full max-w-md p-6 space-y-4">
        <div class="flex justify-between items-center">
            <h3 class="text-lg font-bold">Upload File Desain</h3>
            <button onclick="closeUploadModal()">✕</button>
        </div>

        <form action="{{ route('desain.upload') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="nomor_order" id="nomorOrder">

            <input type="file" name="file_desain" required
                class="w-full border rounded-md p-2">

            <div class="flex justify-end gap-2 pt-4">
                <button type="button" onclick="closeUploadModal()" class="px-4 py-2 border rounded-md">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-black text-white rounded-md">
                    Upload
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ================= MODAL REVISI (BARU) ================= --}}
<div id="revisiModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl w-full max-w-md p-6 space-y-4">
        <div class="flex justify-between items-center">
            <h3 class="text-lg font-bold">Catatan Revisi</h3>
            <button onclick="closeRevisiModal()">✕</button>
        </div>

        <form action="{{ route('desain.revisi') }}" method="POST">
            @csrf
            <input type="hidden" name="nomor_order" id="revisiNomorOrder">

            <textarea
                name="catatan_revisi"
                rows="4"
                required
                class="w-full border rounded-md p-2"
                placeholder="Tuliskan revisi yang diperlukan..."></textarea>

            <div class="flex justify-end gap-2 pt-4">
                <button type="button" onclick="closeRevisiModal()" class="px-4 py-2 border rounded-md">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md">
                    Kirim Revisi
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ================= JS ================= --}}
<script>
function openUploadModal(nomor) {
    document.getElementById('nomorOrder').value = nomor;
    document.getElementById('uploadModal').classList.remove('hidden');
    document.getElementById('uploadModal').classList.add('flex');
}

function closeUploadModal() {
    document.getElementById('uploadModal').classList.add('hidden');
    document.getElementById('uploadModal').classList.remove('flex');
}

// ✅ REVISI
function openRevisiModal(nomor) {
    document.getElementById('revisiNomorOrder').value = nomor;
    document.getElementById('revisiModal').classList.remove('hidden');
    document.getElementById('revisiModal').classList.add('flex');
}

function closeRevisiModal() {
    document.getElementById('revisiModal').classList.add('hidden');
    document.getElementById('revisiModal').classList.remove('flex');
}
</script>

@endsection
