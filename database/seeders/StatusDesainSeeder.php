<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusDesainSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            [
                'id' => 1,
                'nama_status' => 'Menunggu'
            ],
            [
                'id' => 2,
                'nama_status' => 'Dalam Proses'
            ],
            [
                'id' => 3,
                'nama_status' => 'Revisi'
            ],
            [
                'id' => 4,
                'nama_status' => 'Disetujui'
            ],
        ];

        foreach ($statuses as $status) {
            // Menggunakan updateOrInsert agar tidak duplikat jika dijalankan berulang
            DB::table('status_desains')->updateOrInsert(
                ['id' => $status['id']], // Cek berdasarkan ID
                [
                    'nama_status' => $status['nama_status'],
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]
            );
        }
    }
}
