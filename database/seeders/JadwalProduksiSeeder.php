<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JadwalProduksiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil pesanan yang ada
        $pesanans = DB::table('pesanans')->pluck('id')->toArray();
        
        if (count($pesanans) < 5) {
            // Jika pesanan kurang dari 5, buat jadwal sesuai pesanan yang ada
            $statuses = ['pending', 'berjalan', 'selesai', 'tertunda'];
            
            foreach ($pesanans as $index => $pesananId) {
                DB::table('produksis')->insertOrIgnore([
                    'pesanan_id' => $pesananId,
                    'tanggal_mulai' => now()->addDays($index)->toDateString(),
                    'tanggal_selesai' => now()->addDays($index + 3)->toDateString(),
                    'status_produksi' => $statuses[$index % count($statuses)],
                    'catatan' => 'Jadwal produksi dari seeder',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        } else {
            // Jika pesanan sudah cukup, buat 5 jadwal
            DB::table('produksis')->insertOrIgnore([
                [
                    'pesanan_id' => $pesanans[0],
                    'tanggal_mulai' => now()->toDateString(),
                    'tanggal_selesai' => now()->addDays(3)->toDateString(),
                    'status_produksi' => 'pending',
                    'catatan' => 'Tunggu approval desain dari klien',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'pesanan_id' => $pesanans[1],
                    'tanggal_mulai' => now()->addDays(1)->toDateString(),
                    'tanggal_selesai' => now()->addDays(4)->toDateString(),
                    'status_produksi' => 'berjalan',
                    'catatan' => 'Sedang dalam tahap printing',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'pesanan_id' => $pesanans[2],
                    'tanggal_mulai' => now()->addDays(2)->toDateString(),
                    'tanggal_selesai' => now()->addDays(7)->toDateString(),
                    'status_produksi' => 'pending',
                    'catatan' => 'Menunggu konfirmasi material dari PIC',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'pesanan_id' => $pesanans[3] ?? $pesanans[0],
                    'tanggal_mulai' => now()->addDays(3)->toDateString(),
                    'tanggal_selesai' => now()->addDays(6)->toDateString(),
                    'status_produksi' => 'selesai',
                    'catatan' => 'Sudah selesai dan siap dikirim',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'pesanan_id' => $pesanans[4] ?? $pesanans[1],
                    'tanggal_mulai' => now()->addDays(5)->toDateString(),
                    'tanggal_selesai' => now()->addDays(8)->toDateString(),
                    'status_produksi' => 'tertunda',
                    'catatan' => 'Terpaksa tertunda karena ketersediaan material terbatas',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }
    }
}
