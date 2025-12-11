<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Sheet</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            body { 
                -webkit-print-color-adjust: exact; 
                print-color-adjust: exact; /* Ini perbaikannya */
            }
            .no-print { display: none; }
        }
        body { font-family: 'Courier New', Courier, monospace; }
    </style>
</head>
<body class="bg-gray-100 p-8">
    {{-- Isinya sama seperti sebelumnya, tidak perlu diubah jika sudah jalan --}}
    {{-- ... content print ... --}}
    <div class="max-w-2xl mx-auto bg-white p-8 border border-gray-300 shadow-sm print:shadow-none print:border-none">
        
        <div class="text-center border-b-2 border-black pb-4 mb-6">
            <h1 class="text-3xl font-bold uppercase tracking-widest">JOB SHEET PRODUKSI</h1>
            <p class="text-sm text-gray-600 mt-1">Surat Perintah Kerja (SPK)</p>
        </div>

        <div class="grid grid-cols-2 gap-8 mb-6">
            <div>
                <p class="text-xs font-bold text-gray-500 uppercase">No. Pesanan</p>
                <p class="text-xl font-bold text-black">ORD-{{ str_pad($item->id, 3, '0', STR_PAD_LEFT) }}</p>
            </div>
            <div class="text-right">
                <p class="text-xs font-bold text-gray-500 uppercase">Tanggal Masuk</p>
                <p class="text-lg font-bold text-black">{{ \Carbon\Carbon::parse($item->tanggal_pesanan)->format('d/m/Y') }}</p>
            </div>
        </div>

        <div class="mb-6 border border-gray-300 p-4">
            <h3 class="font-bold border-b border-gray-300 pb-2 mb-2 uppercase text-sm">Data Pelanggan</h3>
            <div class="grid grid-cols-1 gap-1 text-sm">
                <div class="flex">
                    <span class="w-24 text-gray-500">Nama</span>
                    <span class="font-bold">: {{ $item->nama_pelanggan }}</span>
                </div>
                <div class="flex">
                    <span class="w-24 text-gray-500">No. HP</span>
                    <span>: {{ $item->telepon ?? '-' }}</span>
                </div>
                <div class="flex">
                    <span class="w-24 text-gray-500">Alamat</span>
                    <span>: {{ $item->alamat ?? '-' }}</span>
                </div>
            </div>
        </div>

        <div class="mb-6">
            <h3 class="font-bold border-b-2 border-black pb-2 mb-4 uppercase text-sm">Spesifikasi Order</h3>
            
            <table class="w-full text-left text-sm">
                <tr class="border-b border-gray-200">
                    <td class="py-2 w-1/3 font-bold text-gray-600">Jenis Layanan</td>
                    <td class="py-2 font-bold text-xl">{{ $item->layanan }}</td>
                </tr>
                <tr class="border-b border-gray-200">
                    <td class="py-2 font-bold text-gray-600">Jumlah Cetak</td>
                    <td class="py-2 font-bold text-xl">{{ $item->jumlah }} Pcs</td>
                </tr>
                <tr class="border-b border-gray-200">
                    <td class="py-2 font-bold text-gray-600 align-top">Spesifikasi</td>
                    <td class="py-2">{{ $item->spesifikasi }}</td>
                </tr>
                <tr class="border-b border-gray-200">
                    <td class="py-2 font-bold text-gray-600">File Desain</td>
                    <td class="py-2 font-mono text-sm">{{ $item->file_desain }}</td>
                </tr>
                <tr>
                    <td class="py-2 font-bold text-gray-600 align-top">Catatan</td>
                    <td class="py-2 italic bg-gray-50 p-2 border border-dashed border-gray-300">
                        {{ $item->catatan ?? 'Tidak ada catatan tambahan.' }}
                    </td>
                </tr>
            </table>
        </div>

        <div class="grid grid-cols-2 gap-8 mt-12 text-center text-sm">
            <div>
                <p class="mb-16">Admin / Penerima</p>
                <div class="border-t border-black mx-10"></div>
            </div>
            <div>
                <p class="mb-16">Operator Produksi</p>
                <div class="border-t border-black mx-10"></div>
            </div>
        </div>

        <div class="mt-8 pt-4 border-t border-gray-200 text-center text-xs text-gray-400">
            Dicetak pada: {{ now()->format('d-m-Y H:i') }}
        </div>
    </div>

    <div class="fixed bottom-8 right-8 no-print">
        <button onclick="window.print()" class="bg-black text-white px-6 py-3 rounded-full font-bold shadow-lg hover:bg-gray-800 flex items-center gap-2 transition transform hover:scale-105">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
            CETAK SEKARANG
        </button>
    </div>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>