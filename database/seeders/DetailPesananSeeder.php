<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon; // ⬅️ WAJIB

class DetailPesananSeeder extends Seeder
{
    public function run(): void
    {
        $pesananIds = DB::table('pesanans')->pluck('id');
        $layananIds = DB::table('jenis_layanans')->pluck('id');

        if ($pesananIds->isEmpty() || $layananIds->isEmpty()) {
            return;
        }

        foreach ($pesananIds as $pesananId) {
            $jumlah = rand(1, 10);
            $hargaSatuan = rand(20000, 150000);
            $subtotal = $jumlah * $hargaSatuan;

            DB::table('detail_pesanans')->insert([
                'pesanan_id'       => $pesananId,
                'jenis_layanan_id' => $layananIds->random(),
                'jumlah'           => $jumlah,
                'harga_satuan'     => $hargaSatuan,
                'subtotal'         => $subtotal,
                'spesifikasi'      => 'Spesifikasi produk dari seeder',
                'created_at'       => Carbon::now(),
                'updated_at'       => Carbon::now(),
            ]);
        }
    }
}
