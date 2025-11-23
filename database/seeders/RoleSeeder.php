<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         DB::table('roles')->insert([
             [
                'nama_role' => 'admin',
                'deskripsi' => 'Bertanggung jawab terhadap pencatatan, dokumen, dan pengarsipan data pesanan.',
            ],
            [
                'nama_role' => 'Desain',
                'deskripsi' => 'Mengatur dan membuat desain produk sesuai permintaan pelanggan.',
            ],
            [
                'nama_role' => 'Produksi',
                'deskripsi' => 'Menangani proses pembuatan produk berdasarkan desain dan spesifikasi yang telah ditentukan.',
            ],
            [
                'nama_role' => 'Manajemen',
                'deskripsi' => 'Mengelola dan memantau seluruh proses kerja, memastikan target dan kualitas tercapai.',
            ],
        ]);
    }
}
