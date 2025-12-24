<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PelangganSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('pelanggans')->insert([
            [
                'nama' => 'PT. Maju Jaya',
                'email' => 'info@majujaya.com',
                'telepon' => '6281234567890',
                'alamat' => 'Jl. Sudirman No. 10 Jakarta',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Toko Berkah',
                'email' => 'owner@tokoberkah.com',
                'telepon' => '6289876543210',
                'alamat' => 'Jl. Ahmad Yani No. 5 Bandung',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'CV. Kreatif Mandiri',
                'email' => 'hello@kreatif.id',
                'telepon' => '628555666777',
                'alamat' => 'Jl. Dago No. 100 Bandung',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
