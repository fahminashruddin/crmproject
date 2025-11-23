<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class StatusPesananSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         DB::table('status_pesanans')->insert([
            ['nama_status' => 'Pending', 'created_at' => now(), 'updated_at' => now()],
            ['nama_status' => 'Diproses', 'created_at' => now(), 'updated_at' => now()],
            ['nama_status' => 'Selesai', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
