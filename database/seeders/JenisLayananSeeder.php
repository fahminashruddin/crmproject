<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JenisLayananSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $layanan = [
            [
                'nama_layanan' => 'Digital Printing A3+',
                'deskripsi'    => 'Cetak cepat full color ukuran A3+ menggunakan mesin laser. Cocok untuk poster, brosur, dan flyer jumlah sedikit.',
                'harga_dasar'  => 5000.00, // Harga per lembar
            ],
            [
                'nama_layanan' => 'Outdoor Banner (Spanduk)',
                'deskripsi'    => 'Cetak spanduk bahan Flexi China 280gr untuk keperluan luar ruangan. Tahan air dan panas.',
                'harga_dasar'  => 25000.00, // Harga per meter
            ],
            [
                'nama_layanan' => 'Kartu Nama (1 Box)',
                'deskripsi'    => 'Cetak kartu nama 1 muka bahan Art Carton 260gsm. Isi 100 lembar per box.',
                'harga_dasar'  => 45000.00, // Harga per box
            ],
            [
                'nama_layanan' => 'Sablon Kaos (Plastisol)',
                'deskripsi'    => 'Sablon manual kualitas premium menggunakan tinta plastisol. Tahan lama dan warna tajam.',
                'harga_dasar'  => 85000.00, // Harga jasa + kaos
            ],
            [
                'nama_layanan' => 'Cetak Undangan (Hardcover)',
                'deskripsi'    => 'Undangan pernikahan eksklusif hardcover dengan finishing poly emas dan emboss.',
                'harga_dasar'  => 7500.00, // Harga per pcs
            ],
            [
                'nama_layanan' => 'Stiker Chromo (A3+)',
                'deskripsi'    => 'Cetak stiker bahan kertas mengkilap. Cocok untuk label kemasan makanan kering.',
                'harga_dasar'  => 12000.00, // Harga per lembar A3+ cutting
            ],
            [
                'nama_layanan' => 'Brosur Lipat 3 (1 Rim)',
                'deskripsi'    => 'Paket cetak brosur ukuran A4 lipat 3 bahan Art Paper 150gsm. Hemat untuk promosi massal.',
                'harga_dasar'  => 650000.00, // Harga per rim (500 lbr)
            ],
            [
                'nama_layanan' => 'Merchandise Mug',
                'deskripsi'    => 'Cetak mug keramik standar SNI coating impor. Cocok untuk souvenir.',
                'harga_dasar'  => 25000.00, // Harga satuan
            ],
        ];

        foreach ($layanan as $item) {
            // Gunakan updateOrInsert agar tidak duplikat jika seeder dijalankan berulang kali
            DB::table('jenis_layanans')->updateOrInsert(
                ['nama_layanan' => $item['nama_layanan']], // Kunci pencarian
                [
                    'deskripsi'   => $item['deskripsi'],
                    'harga_dasar' => $item['harga_dasar'],
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]
            );
        }
    }
}
