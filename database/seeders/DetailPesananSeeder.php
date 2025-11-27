<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DetailPesananSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Ambil semua ID Pesanan
        $pesanans = DB::table('pesanans')->pluck('id');

        // 2. Ambil data Layanan beserta harganya
        // Kita butuh 'harga_dasar' untuk mengisi 'harga_satuan'
        $layanans = DB::table('jenis_layanans')->select('id', 'nama_layanan', 'harga_dasar')->get();

        // Safety check
        if ($pesanans->isEmpty() || $layanans->isEmpty()) {
            $this->command->warn('Data Pesanan atau Jenis Layanan kosong. Harap seed tabel tersebut dulu.');
            return;
        }

        // 3. Daftar Spesifikasi Dummy (Agar data terlihat nyata)
        $specs = [
            'Bahan Art Paper 260gsm, Laminasi Doff, Potong Kotak',
            'Bahan Flexi 280gr, Finishing Mata Ayam 4 Sisi',
            'Bahan Sticker Vinyl, Kiss Cut, Tahan Air',
            'Kertas HVS 70gsm, Print Hitam Putih, Jilid Spiral',
            'Kaos Cotton Combed 30s, Sablon Plastisol Depan Belakang',
            'Bahan Albatros, Laminasi Glossy, Include Tiang X-Banner',
            'Undangan Hardcover, Poly Emas, Amplop Jasmine',
            'Mug Keramik Standar SNI, Coating Import, Dus Putih'
        ];

        // 4. Loop setiap pesanan untuk dibuatkan detailnya
        foreach ($pesanans as $pesananId) {

            // Tentukan: 1 pesanan bisa punya 1 atau 2 jenis item
            $jumlahItem = rand(1, 2);

            for ($i = 0; $i < $jumlahItem; $i++) {
                // Pilih layanan secara acak
                $layanan = $layanans->random();

                // Tentukan jumlah order (misal 10 - 500 pcs)
                $qty = rand(1, 50) * 10;

                // Tentukan harga satuan (Ambil dari harga dasar layanan + sedikit variasi margin)
                // Jika harga_dasar null/0, kita kasih default 10.000
                $basePrice = $layanan->harga_dasar > 0 ? $layanan->harga_dasar : 10000;

                // Simulasi: Kadang ada biaya tambahan 5% - 20% untuk finishing sulit
                $markup = rand(0, 20) / 100;
                $hargaFinal = $basePrice + ($basePrice * $markup);

                DB::table('detail_pesanans')->insert([
                    'pesanan_id'       => $pesananId,
                    'jenis_layanan_id' => $layanan->id,
                    'spesifikasi'      => $specs[array_rand($specs)], // Pilih spek acak
                    'jumlah'           => $qty,
                    'harga_satuan'     => $hargaFinal, // Harga decimal (10, 2)
                    'created_at'       => now(),
                    'updated_at'       => now(),
                ]);
            }
        }
    }
}
