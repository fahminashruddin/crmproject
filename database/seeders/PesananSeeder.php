<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PesananSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan ada data pelanggan terlebih dahulu - tambahkan jika kurang
        $existingPelanggans = DB::table('pelanggans')->count();
        if ($existingPelanggans < 5) {
            $existingEmails = DB::table('pelanggans')->pluck('email')->toArray();
            $newPelanggans = [];
            
            for ($i = 0; $i < 5 - $existingPelanggans; $i++) {
                $email = 'pelanggan' . uniqid() . '@example.com';
                $newPelanggans[] = [
                    'nama' => 'Pelanggan ' . ($existingPelanggans + $i + 1),
                    'alamat' => 'Jalan Pelanggan ' . ($existingPelanggans + $i + 1),
                    'telepon' => '0812345678' . str_pad($existingPelanggans + $i, 2, '0', STR_PAD_LEFT),
                    'email' => $email,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            
            if (!empty($newPelanggans)) {
                DB::table('pelanggans')->insert($newPelanggans);
            }
        }

        // Pastikan ada data status pesanan
        if (DB::table('status_pesanans')->count() == 0) {
            DB::table('status_pesanans')->insert([
                ['nama_status' => 'Pending', 'created_at' => now(), 'updated_at' => now()],
                ['nama_status' => 'Diproses', 'created_at' => now(), 'updated_at' => now()],
                ['nama_status' => 'Selesai', 'created_at' => now(), 'updated_at' => now()],
            ]);
        }

        // Pastikan ada data pengguna
        if (DB::table('penggunas')->count() == 0) {
            DB::table('penggunas')->insert([
                ['nama' => 'Staff Produksi', 'email' => 'staff.produksi' . uniqid() . '@example.com', 'password' => bcrypt('password'), 'role_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ]);
        }

        // Insert 5 data pesanan hanya jika belum ada cukup
        $existingPesanan = DB::table('pesanans')->count();
        if ($existingPesanan < 5) {
            $pelanggans = DB::table('pelanggans')->pluck('id')->toArray();
            $pengguna = DB::table('penggunas')->first();
            $statusPending = DB::table('status_pesanans')->where('nama_status', 'Pending')->first();
            $statusDiproses = DB::table('status_pesanans')->where('nama_status', 'Diproses')->first();
            
            // Debug
            $pelangganCount = count($pelanggans);
            $penggunaExist = $pengguna ? 1 : 0;
            $statusExist = $statusPending ? 1 : 0;
            
            if ($pelangganCount == 0 || !$pengguna || !$statusPending) {
                throw new \Exception("Seeder tidak lengkap: pelanggans=$pelangganCount, pengguna=$penggunaExist, status=$statusExist");
            }
            
            $pesananBaru = [];
            for ($i = 0; $i < 5 - $existingPesanan; $i++) {
                $pesananBaru[] = [
                    'tanggal_pesanan' => now()->subDays(10 - $i)->toDateString(),
                    'catatan' => 'Pesanan ' . ($existingPesanan + $i + 1) . ' dari seeder',
                    'pelanggan_id' => $pelanggans[$i % $pelangganCount],
                    'pengguna_id' => $pengguna->id,
                    'status_pesanan_id' => ($i % 2 == 0) ? $statusPending->id : ($statusDiproses->id ?? $statusPending->id),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            
            if (!empty($pesananBaru)) {
                DB::table('pesanans')->insert($pesananBaru);
            }
        }
    }
}
