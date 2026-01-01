<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Pesanan;
use App\Models\StatusDesain;

class DesainSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ambil ID Pesanan & Status yang valid
        $pesananIds = Pesanan::pluck('id')->toArray();
        $statuses = StatusDesain::pluck('nama_status', 'id')->toArray();

        if (empty($pesananIds) || empty($statuses)) {
            $this->command->info('⚠️ Tabel Pesanan atau StatusDesain kosong. Skip DesainSeeder.');
            return;
        }

        // 2. Tentukan berapa banyak data yang mau dibuat
        // (Misal: Kita buat desain untuk SEMUA pesanan yang ada agar tidak ada yang kosong)
        foreach ($pesananIds as $pesananId) {

            // Pilih status secara acak
            $statusId = array_rand($statuses);
            $namaStatus = strtolower($statuses[$statusId]);

            // === LOGIKA BARU: SEMUA HARUS ADA DATANYA ===

            // 1. File Desain: Selalu terisi (Dummy)
            $filePath = 'desain/dummy_' . $pesananId . '_' . time() . '.jpg';

            // 2. Catatan Revisi:
            // Jika status 'Revisi', isi kalimat asli.
            // Jika status lain, isi '-' agar tidak NULL (sesuai request "jangan kosong")
            $catatan = '-';

            if (str_contains($namaStatus, 'revisi')) {
                $catatan = 'Tolong perbaiki kontras warna background dan logo diperbesar sedikit.';
            }

            // Insert atau Update jika sudah ada
            DB::table('desains')->updateOrInsert(
                ['pesanan_id' => $pesananId], // Cek biar tidak duplikat per pesanan
                [
                    'status_desain_id' => $statusId,
                    'file_desain_path' => $filePath,
                    'catatan_revisi'   => $catatan,
                    'created_at'       => now(),
                    'updated_at'       => now(),
                ]
            );
        }
    }
}
