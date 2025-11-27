<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DesainController extends Controller
{
    /**
     * Redirect ke halaman utama Kelola Desain
     */
    public function index()
    {
        return $this->kelolaDesain(); 
    }

    /**
     * Menampilkan Dashboard Desain.
     * Data dummy disesuaikan agar properti 'status_desain' dan 'created_at' selalu tersedia.
     */
    public function dashboard()
    {
        // Data dummy untuk dashboard, ditambahkan properti status_desain dan created_at (untuk mengatasi error di Blade)
        $antrian = [
            (object)[
                'id' => 1,
                'nama_pelanggan' => 'Budi Santoso',
                'status' => 'Menunggu',
                'status_desain' => 'Menunggu Desain', 
                'deadline' => '2025-12-01',
                'created_at' => '2025-11-25 10:30:00' // FIX: Ditambahkan properti created_at
            ],
            (object)[
                'id' => 2,
                'nama_pelanggan' => 'Siti Aminah',
                'status' => 'Proses',
                'status_desain' => 'Perlu Revisi', 
                'deadline' => '2025-12-02',
                'created_at' => '2025-11-26 14:00:00' // FIX: Ditambahkan properti created_at
            ],
            (object)[
                'id' => 3,
                'nama_pelanggan' => 'Joko Pratama',
                'status' => 'Review',
                'status_desain' => 'Menunggu Persetujuan', 
                'deadline' => '2025-12-05',
                'created_at' => '2025-11-27 09:15:00' // FIX: Ditambahkan properti created_at
            ],
        ];

        return view('desain.dashboard', compact('antrian'));
    }

    /**
     * Menampilkan halaman Kelola Desain (Tabel Utama).
     * Mengirimkan data dummy pesanan desain.
     */
    public function kelolaDesain()
    {
        // Data Dummy Pesanan Desain yang akan digunakan oleh designs.blade.php
        $designs = [
            (object)[
                'id_pesanan' => 'ORD-101',
                'pelanggan' => 'PT. Sinar Abadi',
                'produk' => 'Spanduk 3x1m (Vinyl)',
                'tgl_dipesan' => '2025-11-20',
                'prioritas' => 'Tinggi',
                'status_desain' => 'Perlu Revisi',
            ],
            (object)[
                'id_pesanan' => 'ORD-102',
                'pelanggan' => 'Jaya Makmur Corp',
                'produk' => 'Brosur A5 (Art Paper 150)',
                'tgl_dipesan' => '2025-11-25',
                'prioritas' => 'Normal',
                'status_desain' => 'Menunggu Desain',
            ],
            (object)[
                'id_pesanan' => 'ORD-103',
                'pelanggan' => 'Digital Kreasindo',
                'produk' => 'Kartu Nama (Doft)',
                'tgl_dipesan' => '2025-11-26',
                'prioritas' => 'Normal',
                'status_desain' => 'Menunggu Persetujuan',
            ],
            (object)[
                'id_pesanan' => 'ORD-104',
                'pelanggan' => 'Solusi Cetak Cepat',
                'produk' => 'Flyer Promosi',
                'tgl_dipesan' => '2025-11-27',
                'prioritas' => 'Rendah',
                'status_desain' => 'Disetujui',
            ],
            (object)[
                'id_pesanan' => 'ORD-105',
                'pelanggan' => 'Mandiri Printing',
                'produk' => 'Roll Banner 80x200',
                'tgl_dipesan' => '2025-11-18',
                'prioritas' => 'Tinggi',
                'status_desain' => 'Menunggu Desain',
            ],
        ];

        return view('desain.designs', compact('designs'));
    }

    /**
     * Menampilkan Daftar Pesanan Revisi.
     * (Ditambahkan untuk menyediakan action bagi Route [desain.revisions])
     */
    public function revisions()
    {
        // Anda dapat menambahkan logika untuk mengambil data revisi di sini.
        // Untuk saat ini, hanya mengembalikan view placeholder.
        return view('desain.revisions');
    }

    /**
     * Menampilkan Riwayat Desain
     */
    public function riwayat()
    {
        return view('desain.riwayat');
    }

    /**
     * Menampilkan Pengaturan Desain/Template
     */
    public function pengaturan()
    {
        return view('desain.template');
    }
}