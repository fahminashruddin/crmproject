<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProduksiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Ambil ID Pesanan yang ada
        $pesanans = DB::table('pesanans')->pluck('id');

        // Safety Check
        if ($pesanans->isEmpty()) {
            $this->command->warn('Tabel pesanans kosong. Jalankan PesananSeeder terlebih dahulu.');
            return;
        }

        // 2. Daftar Catatan Random untuk variasi
        $catatanList = [
            'Warna sedikit turun di awal, sudah dikoreksi.',
            'Bahan baku stok lama, tapi kualitas masih oke.',
            'Menunggu antrian mesin potong.',
            'Finishing laminasi doff berjalan lancar.',
            'Salah cetak 5 lembar, sudah diganti.',
            'Customer minta dipercepat.',
            null, // Membiarkan beberapa catatan kosong
            null
        ];

        // 3. Loop pesanan untuk membuat data produksi
        foreach ($pesanans as $pesananId) {

            // Simulasi: Anggaplah 70% pesanan masuk ke tahap produksi
            if (rand(1, 10) > 7) {
                continue; // Skip, anggap pesanan ini masih di tahap Desain/Pending
            }

            // Tentukan Tanggal Mulai (Random dalam 30 hari terakhir)
            $tanggalMulai = Carbon::now()->subDays(rand(5, 30));

            // Tentukan apakah produksi sudah selesai atau belum (50:50 chance)
            $isCompleted = (bool)rand(0, 1);
            $tanggalSelesai = $isCompleted 
                ? $tanggalMulai->copy()->addDays(rand(3, 10))
                : $tanggalMulai->copy()->addDays(rand(5, 14));

            // Status
            $status = $isCompleted ? 'selesai' : (rand(0, 1) ? 'berjalan' : 'pending');

            DB::table('produksis')->insertOrIgnore([
                'pesanan_id' => $pesananId,
                'tanggal_mulai' => $tanggalMulai,
                'tanggal_selesai' => $tanggalSelesai,
                'status_produksi' => $status,
                'catatan' => $catatanList[array_rand($catatanList)],
                'created_at' => $tanggalMulai,
                'updated_at' => now(),
            ]);
        }

        // 4. Insert sample inventorys
        $inventories = [
            ['nama_produk' => 'Kertas A4 Premium', 'jumlah' => 500, 'satuan' => 'ream', 'lokasi' => 'Rak A1', 'keterangan' => 'Stok dari supplier terpercaya'],
            ['nama_produk' => 'Kertas HVS 70 gr', 'jumlah' => 20, 'satuan' => 'ream', 'lokasi' => 'Rak A2', 'keterangan' => 'Stock menipis, segera order'],
            ['nama_produk' => 'Tinta Hitam', 'jumlah' => 5, 'satuan' => 'liter', 'lokasi' => 'Gudang Utama', 'keterangan' => null],
            ['nama_produk' => 'Tinta Warna CMYK', 'jumlah' => 8, 'satuan' => 'liter', 'lokasi' => 'Gudang Utama', 'keterangan' => null],
            ['nama_produk' => 'Plastik Laminating Glossy', 'jumlah' => 3, 'satuan' => 'roll', 'lokasi' => 'Rak B1', 'keterangan' => 'Untuk finishing produk premium'],
            ['nama_produk' => 'Plastik Laminating Doff', 'jumlah' => 15, 'satuan' => 'roll', 'lokasi' => 'Rak B2', 'keterangan' => null],
            ['nama_produk' => 'Kawat Spiral Hitam', 'jumlah' => 100, 'satuan' => 'pcs', 'lokasi' => 'Rak C1', 'keterangan' => null],
            ['nama_produk' => 'Lem Kertas', 'jumlah' => 2, 'satuan' => 'kg', 'lokasi' => 'Gudang Utama', 'keterangan' => 'Persediaan terbatas'],
        ];

        foreach ($inventories as $inventory) {
            DB::table('inventorys')->insertOrIgnore(array_merge($inventory, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        $this->command->info('ProduksiSeeder berhasil dijalankan!');
    }
}

            $tanggalSelesai = null;

            if ($isCompleted) {
                // Jika selesai, tanggal selesai = tanggal mulai + durasi pengerjaan (1-7 hari)
                $tanggalSelesai = (clone $tanggalMulai)->addDays(rand(1, 7));
            }

            // Insert ke database
            DB::table('produksis')->insert([
                'pesanan_id'      => $pesananId,
                'tanggal_mulai'   => $tanggalMulai->toDateString(), // Format Y-m-d
                'tanggal_selesai' => $tanggalSelesai ? $tanggalSelesai->toDateString() : null,
                'catatan'         => $catatanList[array_rand($catatanList)],
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);
        }
        
    }
}
