<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>SPK - ORD-{{ str_pad($item->id, 3, '0', STR_PAD_LEFT) }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            @page { margin: 0; size: A4; }
            body { margin: 1.5cm; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .no-print { display: none !important; }
            header, footer, nav, aside { display: none !important; }
        }
        body { font-family: sans-serif; }
    </style>
</head>
<body class="bg-white text-slate-900 text-sm" onload="window.print()">

    {{-- KOP --}}
    <div class="border-b-2 border-slate-900 pb-4 mb-6 flex justify-between items-center">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-slate-900 text-white flex items-center justify-center rounded">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 20a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8l-7 5V8l-7 5V4a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"/><line x1="17" x2="17" y1="13" y2="23"/><line x1="12" x2="12" y1="13" y2="23"/><line x1="7" x2="7" y1="13" y2="23"/></svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold uppercase">Job Sheet Produksi</h1>
                <p class="text-slate-500 text-xs">Surat Perintah Kerja (SPK)</p>
            </div>
        </div>
        <div class="text-right">
            <p class="text-xl font-mono font-bold border px-2 py-1 bg-slate-100">ORD-{{ str_pad($item->id, 3, '0', STR_PAD_LEFT) }}</p>
        </div>
    </div>

    {{-- ISI --}}
    <div class="grid grid-cols-2 gap-8 mb-6">
        <div class="border p-4 rounded bg-slate-50">
            <h3 class="font-bold border-b pb-2 mb-2 text-xs uppercase">Pelanggan</h3>
            <table class="w-full">
                <tr><td class="w-20 text-slate-500">Nama</td><td class="font-bold">: {{ $item->nama_pelanggan }}</td></tr>
                <tr><td class="text-slate-500">Kontak</td><td>: {{ $item->no_hp ?? '-' }}</td></tr>
                <tr><td class="text-slate-500">Alamat</td><td>: {{ $item->alamat ?? '-' }}</td></tr>
            </table>
        </div>
        <div class="border p-4 rounded bg-slate-50">
            <h3 class="font-bold border-b pb-2 mb-2 text-xs uppercase">Pekerjaan</h3>
            <table class="w-full">
                <tr><td class="w-20 text-slate-500">Layanan</td><td class="font-bold text-lg">: {{ $item->jenis_layanan }}</td></tr>
                <tr><td class="text-slate-500">Jumlah</td><td class="font-bold text-lg">: {{ $item->jumlah }} Pcs</td></tr>
                <tr><td class="text-slate-500">Status</td><td>: {{ $item->status_produksi }}</td></tr>
            </table>
        </div>
    </div>

    <div class="mb-10">
        <h3 class="font-bold text-xs uppercase mb-1">Catatan Produksi</h3>
        <div class="border border-dashed border-slate-400 p-4 rounded min-h-[100px]">
            {{ $item->catatan ?? 'Tidak ada catatan.' }}
        </div>
    </div>

    {{-- TTD --}}
    <div class="grid grid-cols-3 gap-4 text-center mt-auto">
        <div><p class="text-xs mb-12">Admin</p><div class="border-t border-slate-900 mx-8"></div></div>
        <div><p class="text-xs mb-12">Operator</p><div class="border-t border-slate-900 mx-8"></div><p class="text-xs font-bold">{{ Auth::user()->name }}</p></div>
        <div><p class="text-xs mb-12">QC / Logistik</p><div class="border-t border-slate-900 mx-8"></div></div>
    </div>

    <div class="fixed bottom-0 w-full text-center text-[10px] text-slate-400 pt-2 border-t mt-4 no-print">
        Dicetak pada: {{ now()->format('d/m/Y H:i') }}
    </div>
</body>
</html>