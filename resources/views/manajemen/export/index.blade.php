@extends('manajemen.layout')

@section('title', 'Export Data')

@section('content')
<div class="space-y-8">

    <h2 class="text-2xl font-bold">Export Data</h2>
    <p class="text-slate-500">Export data untuk analisis eksternal</p>

    <div class="bg-white p-6 rounded-xl shadow-sm border space-y-4">

        <h3 class="font-semibold mb-3">Export Options</h3>

        <!-- GRID 2 KOLOM -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            <a href="/manajemen/export/pesanan"
                class="block bg-slate-900 text-white px-4 py-3 rounded-lg text-center">
                Export Semua Pesanan
            </a>

            <a href="/manajemen/export/pelanggan"
                class="block bg-slate-900 text-white px-4 py-3 rounded-lg text-center">
                Export Data Pelanggan
            </a>

            <a href="/manajemen/export/keuangan"
                class="block bg-slate-900 text-white px-4 py-3 rounded-lg text-center">
                Export Laporan Keuangan
            </a>

            <a href="/manajemen/export/produksi"
                class="block bg-slate-900 text-white px-4 py-3 rounded-lg text-center">
                Export Laporan Produksi
            </a>

        </div>

    </div>

</div>
@endsection
