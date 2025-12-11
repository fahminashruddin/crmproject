<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PesananSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ambil data ID dari tabel-tabel relasi (Foreign Keys)
        $pelanggans = DB::table('pelanggans')->pluck('id');
        $users      = DB::table('penggunas')->pluck('id');
        $statuses   = DB::table('status_pesanans')->pluck('id');

        // Safety Check: Pastikan data induk ada
        if ($pelanggans->isEmpty() || $users->isEmpty() || $statuses->isEmpty()) {
            $this->command->warn('Data Pelanggan, User, atau Status kosong. Harap seed tabel tersebut dulu.');
            return;
        }

        // 2. Buat Dummy Data Pesanan (Header)
        // Kita buat misal 15 pesanan dummy
        for ($i = 0; $i < 15; $i++) {

            DB::table('pesanans')->insert([
                'tanggal_pesanan'   => now()->subDays(rand(0, 30)), // Random tanggal sebulan terakhir
                'catatan'           => 'Pesanan dummy otomatis dari seeder.',

                // Ambil ID acak dari tabel relasi
                'pelanggan_id'      => $pelanggans->random(),
                'pengguna_id'       => $users->random(),
                'status_pesanan_id' => $statuses->random(),

                'created_at'        => now(),
                'updated_at'        => now(),
            ]);
        }
    }
}
