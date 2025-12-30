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
        
        if (count($pesanans) > 0) {
            // Buat jadwal untuk semua pesanan yang ada
            foreach ($pesanans as $index => $pesananId) {
                DB::table('produksis')->insertOrIgnore([
                    'pesanan_id' => $pesananId,
                    'tanggal_mulai' => now()->addDays($index)->toDateString(),
                    'tanggal_selesai' => now()->addDays($index + 3)->toDateString(),
                    'catatan' => 'Jadwal produksi dari seeder',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
