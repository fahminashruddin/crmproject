<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PesananSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [];

        for ($i = 1; $i <= 10; $i++) {
            $data[] = [
                'tanggal_pesanan' => Carbon::now()->subDays(rand(0, 30)),
                'catatan' => 'Pesanan ke-' . $i . ' dengan catatan tambahan.',
                'pelanggan_id' => rand(1, 3), // pastikan id pelanggan 1-5 ada
                'pengguna_id' => rand(1, 4),  // pastikan id pengguna 1-4 ada
                'status_pesanan_id' => rand(1, 3), // pastikan id status 1-3 ada
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('pesanans')->insert($data);
    }
}
