<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PelangganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('pelanggans')->insert([
            ['nama' => 'Fahmi Nashruddin', 'email' => 'fahmi@example.com', 'telepon' => '081234567890'],
            ['nama' => 'Reva Alifia', 'email' => 'reva@example.com', 'telepon' => '081298765432'],
            ['nama' => 'Fairuz Hanif', 'email' => 'fairuz@example.com', 'telepon' => '081277788899'],
        ]);
    }
}
