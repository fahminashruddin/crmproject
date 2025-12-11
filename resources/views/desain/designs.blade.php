@extends('desain.layout')

@section('title', 'Kelola Desain')

@section('content')

{{-- Hitung variabel overview dari data $designs --}}
@php
// --- PERBAIKAN UTAMA DIMULAI DI SINI ---
// Pastikan $designs adalah Collection yang valid (bukan null)
$designs = $designs ?? collect([]); 

// Variabel ini harus disuplai oleh DesainController::kelolaDesain
// Menggunakan .count() pada Collection (atau count() pada Array) lebih aman.
$designCollection = collect($designs); // Convert to Collection for easy chaining

$total = $designCollection->count();
// Gunakan metode Collection untuk filter dan count
$inProgress = $designCollection->where('status_desain', 'Menunggu Desain')->count();
$done = $designCollection->where('status_desain', 'Disetujui')->count();
$revisi = $designCollection->where('status_desain', 'Perlu Revisi')->count();
// --- PERBAIKAN UTAMA SELESAI ---
@endphp

<!-- JUDUL + STATUS -->

<div class="mb-6">
<h1 class="text-2xl font-bold tracking-tight">Kelola Desain</h1>
<p class="text-sm text-slate-500">Kelola semua tugas desain yang sedang berlangsung.</p>
</div>

{{-- Cards Statistik --}}

<div class="grid gap-4 md:grid-cols-3 mb-8">

{{-- Card 1: Menunggu Desain --}}

<div class="rounded-xl border bg-white p-6 shadow-sm">
<div class="flex justify-between items-center pb-2">
<h3 class="text-sm font-medium text-gray-500">Menunggu Desain</h3>
<i data-lucide="clock" class="h-4 w-4 text-gray-500"></i>
</div>
{{-- Menggunakan $inProgress dari perhitungan di atas --}}
<div class="text-2xl font-bold">{{ $inProgress }}</div>
<p class="text-xs text-gray-500">Perlu dikerjakan</p>
</div>

{{-- Card 2: Perlu Revisi --}}

<div class="rounded-xl border bg-white p-6 shadow-sm">
<div class="flex justify-between items-center pb-2">
<h3 class="text-sm font-medium text-gray-500">Perlu Revisi</h3>
<i data-lucide="file-text" class="h-4 w-4 text-orange-500"></i>
</div>
{{-- Menggunakan $revisi dari perhitungan di atas --}}
<div class="text-2xl font-bold">{{ $revisi }}</div>
<p class="text-xs text-gray-500">Perlu diperbaiki</p>
</div>

{{-- Card 3: Desain Disetujui (Siap Produksi) --}}

<div class="rounded-xl border bg-white p-6 shadow-sm">
<div class="flex justify-between items-center pb-2">
<h3 class="text-sm font-medium text-gray-500">Desain Disetujui</h3>
<i data-lucide="check-circle" class="h-4 w-4 text-green-500"></i>
</div>
{{-- Menggunakan $done dari perhitungan di atas --}}
<div class="text-2xl font-bold ">{{ $done }}</div>
<p class="text-xs text-gray-500">Siap produksi</p>
</div>

</div>

{{-- Daftar Pekerjaan Aktif / Antrian Desain --}}

<div class="space-y-4">
<h2 class="text-xl font-bold tracking-tight">Antrian Desain</h2>
<p class="text-sm text-gray-500">Pesanan yang menunggu proses desain.</p>

{{-- Container untuk setiap item antrian --}}

<div class="grid gap-4">

@forelse ($designs as $design)
    <div class="rounded-xl border bg-white p-6 shadow-sm space-y-3">
        <div class="flex justify-between items-start">
            <div>
                {{-- Nomor Pesanan --}}
                {{-- Menggunakan operator optional chaining untuk akses aman --}}
                <h3 class="text-lg font-bold">{{ $design->nomor_order ?? 'ORD-XXX' }}</h3>
                {{-- Nama Pelanggan dan Tanggal --}}
                {{-- Perbaikan: menggunakan operator optional chaining (?->) untuk relasi pelanggan --}}
                <p class="text-sm text-slate-700">{{ $design->pelanggan?->nama_perusahaan ?? 'PT. Maju Jaya' }}</p>
                <p class="text-xs text-slate-500">{{ $design->tanggal_order ?? '2024-01-15' }}</p>
            </div>
            {{-- Status Desain --}}
            <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium 
                @if ($design->status_desain == 'Menunggu Desain')
                    bg-gray-100 text-gray-800
                @elseif ($design->status_desain == 'Perlu Revisi')
                    bg-red-100 text-red-800
                @elseif ($design->status_desain == 'Disetujui')
                    bg-green-100 text-green-800
                @else
                    bg-blue-100 text-blue-800
                @endif
            ">
                {{ $design->status_desain ?? 'Menunggu Desain' }}
            </span>
        </div>

        <div class="text-sm space-y-1">
            <p><strong>Layanan:</strong> {{ $design->layanan ?? 'Digital Printing' }}</p>
            {{-- Perbaikan: Menggunakan optional chaining (?->) pada properti yang tidak pasti ada --}}
            <p><strong>Jumlah:</strong> {{ number_format($design->jumlah ?? 0, 0, ',', '.') }} pcs</p>
            <p><strong>Spesifikasi:</strong> {{ $design->spesifikasi ?? 'Brosur A4, kertas art paper 150gsm, full color' }}</p>
            <p><strong>Catatan:</strong> {{ $design->catatan_desain ?? 'Desain logo harus menggunakan warna biru corporate' }}</p>
        </div>
        
        {{-- Tombol Aksi --}}
        <div class="flex flex-wrap gap-2 pt-3 border-t">
            <button class="flex items-center gap-1 rounded-md bg-white border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 transition-colors">
                <i data-lucide="upload" class="h-4 w-4"></i>
                Upload Desain
            </button>
            <button class="flex items-center gap-1 rounded-md bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-700 transition-colors">
                <i data-lucide="check" class="h-4 w-4"></i>
                Setujui Desain
            </button>
            <button class="flex items-center gap-1 rounded-md bg-white border border-red-300 px-4 py-2 text-sm font-medium text-red-700 hover:bg-red-50 transition-colors">
                <i data-lucide="repeat" class="h-4 w-4"></i>
                Perlu Revisi
            </button>
        </div>
    </div>
@empty
    <div class="rounded-xl border border-dashed bg-gray-50 p-6 text-center text-gray-500">
        Tidak ada antrian desain aktif saat ini.
    </div>
@endforelse


</div>

</div>
@endsection