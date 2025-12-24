<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('inventorys')->insertOrIgnore([
            [
                'id' => 1,
                'nama_produk' => 'Kertas A4 80gsm',
                'jumlah' => 50,
                'satuan' => 'ream',
                'lokasi' => 'Rak A1',
                'keterangan' => 'Kertas putih standar untuk printing',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'nama_produk' => 'Tinta Printing CMYK',
                'jumlah' => 8,
                'satuan' => 'botol',
                'lokasi' => 'Lemari B2',
                'keterangan' => 'Set lengkap CMYK untuk printer UV',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'nama_produk' => 'Roll Plastik PVC',
                'jumlah' => 3,
                'satuan' => 'roll',
                'lokasi' => 'Gudang Utama',
                'keterangan' => 'Plastik PVC untuk laminating (urgent - stok tinggal 3)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'nama_produk' => 'Lem UV Liquid',
                'jumlah' => 15,
                'satuan' => 'botol',
                'lokasi' => 'Rak C3',
                'keterangan' => 'Lem UV untuk finishing laminate',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 5,
                'nama_produk' => 'Kardus Kemasan',
                'jumlah' => 120,
                'satuan' => 'box',
                'lokasi' => 'Area Packing',
                'keterangan' => 'Kardus untuk packaging produk jadi (stok mencukupi)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
