<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PesananSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Pastikan ada Jenis Layanan dulu (buat jaga-jaga)
        $layananId = DB::table('jenis_layanans')->insertGetId([
            'nama_layanan' => 'Cetak Banner',
            'harga_satuan' => 50000,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        // Tambah layanan lain
        DB::table('jenis_layanans')->insert([
            ['nama_layanan' => 'Kartu Nama', 'harga_satuan' => 35000, 'created_at' => now(), 'updated_at' => now()],
            ['nama_layanan' => 'Brosur A5', 'harga_satuan' => 2500, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 2. Buat Dummy Pesanan
        $dataPesanan = [];
        
        // Kita bikin 15 pesanan acak
        for ($i = 1; $i <= 15; $i++) {
            // Random status: 3 (Desain Disetujui), 4 (Produksi), 5 (Selesai) agar muncul di dashboard produksi
            // Sesekali kasih status 1 atau 2 biar terlihat bedanya
            $statusId = rand(1, 5); 
            
            $pesananId = DB::table('pesanans')->insertGetId([
                'tanggal_pesanan' => Carbon::now()->subDays(rand(0, 10)),
                'catatan' => 'Pesanan prioritas nomor ' . $i . ' tolong dipercepat.',
                'pelanggan_id' => rand(1, 3), // Pastikan PelangganSeeder sudah jalan
                'pengguna_id' => 1, // Asumsi ID 1 adalah admin/staff
                'status_pesanan_id' => $statusId, 
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 3. ISI DETAIL BARANG (PENTING!)
            // Tanpa ini, kolom "Jumlah" dan "Layanan" di dashboard akan 0
            DB::table('detail_pesanans')->insert([
                'pesanan_id' => $pesananId,
                'jenis_layanan_id' => rand(1, 3), // Random layanan
                'jumlah' => rand(10, 500), // Jumlah acak
                'harga_satuan' => 10000,
                'subtotal' => 10000 * rand(10, 500),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}