<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusPesananSeeder extends Seeder
{
    public function run(): void
    {
        // Kita kosongkan dulu biar tidak duplikat saat seeding ulang
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('status_pesanans')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('status_pesanans')->insert([
            ['id' => 1, 'nama_status' => 'Pending', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'nama_status' => 'Proses Desain', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'nama_status' => 'Desain Disetujui', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'nama_status' => 'Diproses', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'nama_status' => 'Produksi', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 6, 'nama_status' => 'Selesai', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}