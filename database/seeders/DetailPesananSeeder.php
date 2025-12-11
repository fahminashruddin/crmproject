<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DetailPesananSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ambil ID Pesanan & Layanan...
        $pesanans = DB::table('pesanans')->pluck('id');
        $layanans = DB::table('jenis_layanans')->select('id', 'nama_layanan', 'harga_satuan')->get();

        // Safety check (tetap diperlukan)...
        if ($pesanans->isEmpty() || $layanans->isEmpty()) {
             $this->command->warn('Data Pesanan atau Jenis Layanan kosong. Harap seed tabel tersebut dulu.');
             return;
        }

        // 2. DAFTAR SPESIFIKASI DUMMY (DIPINDAHKAN KE DALAM RUN())
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

        // 3. Loop setiap pesanan...
        foreach ($pesanans as $pesananId) {
            $jumlahItem = rand(1, 2);

            for ($i = 0; $i < $jumlahItem; $i++) {

                // Pastikan $layanans TIDAK kosong sebelum $layanan->id dipanggil.
                if ($layanans->isEmpty()) continue;

                $layanan = $layanans->random();

                // ... (Logika perhitungan harga tetap sama) ...
                $basePrice = $layanan->harga_satuan > 0 ? $layanan->harga_satuan : 10000;
                $markup = rand(0, 20) / 100;
                $hargaFinal = $basePrice + ($basePrice * $markup);
                $qty = rand(1, 10) * 10;
                if ($basePrice >= 500000) { $qty = rand(1, 5); }

                // 4. Insert ke database (Baris 50)
                DB::table('detail_pesanans')->insert([
                    'pesanan_id'       => $pesananId,
                    'jenis_layanan_id' => $layanan->id,
                    'spesifikasi'      => $specs[array_rand($specs)], // <-- Sekarang ini aman
                    'jumlah'           => $qty,
                    'harga_satuan'     => round($hargaFinal, 2),
                    'created_at'       => now(),
                    'updated_at'       => now(),
                ]);
            }
        }
    }
}
