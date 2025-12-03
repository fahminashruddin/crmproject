<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PembayaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Ambil semua ID pesanan dan hitung total harganya
        $orders = DB::table('pesanans')
            ->select('id', 'tanggal_pesanan',
                DB::raw('(SELECT COALESCE(SUM(jumlah * harga_satuan), 0) FROM detail_pesanans WHERE pesanan_id = pesanans.id) as nominal_order')
            )
            ->get();

        // 2. Ambil semua ID metode pembayaran (Dependency Baru)
        $methods = DB::table('metode_pembayarans')->pluck('id')->toArray();
        $statuses = ['verifikasi', 'pending', 'gagal'];

        if ($orders->isEmpty() || empty($methods)) {
            $this->command->warn('Tabel pesanans atau metode_pembayarans kosong. Tidak dapat membuat data pembayaran.');
            return;
        }

        foreach ($orders as $order) {
            // Tentukan status acak (70% verified)
            $randomStatus = (rand(1, 10) < 7) ? 'verifikasi' : $statuses[rand(0, 2)];

            // Tentukan tanggal bayar (Jika verified, tanggalnya sudah lewat. Jika pending, tanggalnya hari ini/dulu)
            $tanggalBayar = ($randomStatus == 'verifikasi')
                            ? now()->subDays(rand(1, 15))->toDateString()
                            : $order->tanggal_pesanan; // Gunakan tanggal pesanan sebagai tanggal 'pending'

            // Tentukan metode ID (Hanya jika statusnya verified)
            $metodeId = $methods[array_rand($methods)];

            // Masukkan data pembayaran
            DB::table('pembayarans')->insert([
                // Kolom Lama
                'pesanan_id'        => $order->id,
                'nominal'           => $order->nominal_order,
                'status'            => $randomStatus,

                // Kolom Baru
                'metode_pembayaran_id' => $metodeId, // FK
                'tanggal_bayar'     => $tanggalBayar, // Date
                'bukti_bayar_path'  => $randomStatus == 'verifikasi' ? 'storage/bukti_bayar/' . $order->id . '.jpg' : null,

                'created_at'        => now(),
                'updated_at'        => now(),
            ]);

            // Opsional: Update status pesanan di tabel induk jika pembayaran sudah terverifikasi
            if ($randomStatus == 'verifikasi') {
                 // Asumsi ID 2 adalah 'Dikonfirmasi'
                 DB::table('pesanans')->where('id', $order->id)->update(['status_pesanan_id' => 2]);
            }
        }
    }
}
