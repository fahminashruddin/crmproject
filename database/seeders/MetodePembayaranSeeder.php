<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MetodePembayaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Daftar metode pembayaran yang umum digunakan
        $metode = [
            'Transfer Bank (BCA)',
            'Transfer Bank (Mandiri)',
            'QRIS (Gopay/Dana/OVO)',
            'Tunai / Cash',
            'Tempo / Hutang', // Untuk pelanggan korporat
        ];

        foreach ($metode as $name) {
             // updateOrInsert akan memastikan tidak ada duplikasi jika seeder dijalankan berkali-kali
             DB::table('metode_pembayarans')->updateOrInsert(
                ['nama_metode' => $name], // Kunci pencarian
                [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
