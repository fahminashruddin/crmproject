<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Desains\JenisLayanan;
class DesainController extends Controller
{
    /**
     * Halaman utama daftar pesanan yang perlu didesain
     */
    public function designs(Request $request)
    {
        // Cari ID status pesanan 'desain'
        $statusDesainId = DB::table('status_pesanans')
            ->whereRaw('LOWER(nama_status) = ?', ['desain'])
            ->value('id');

        // Ambil semua pesanan yang statusnya 'desain'
        $designs = DB::table('pesanans')
            ->where('status_pesanan_id', $statusDesainId)
            ->leftJoin('pelanggans', 'pesanans.pelanggan_id', '=', 'pelanggans.id')
            ->leftJoin('status_pesanans', 'pesanans.status_pesanan_id', '=', 'status_pesanans.id')
            ->select(
                'pesanans.*',
                'pelanggans.nama as pelanggan_nama',
                'status_pesanans.nama_status'
            )
            ->orderBy('pesanans.tanggal_pesanan', 'asc')
            ->paginate(10);

        return view('desain.designs', compact('designs'));
    }

    /**
     * Redirect default ke kelola desain
     */
    public function index()
    {
        return $this->kelolaDesain();
    }

    /**
     * Dashboard Desainer
     * (Menggunakan data dummy agar tidak error)
     */
    public function dashboard()
    {
        $antrian = [
            (object)[
                'id' => 1,
                'nama_pelanggan' => 'Budi Santoso',
                'status' => 'Menunggu',
                'status_desain' => 'Menunggu Desain',
                'deadline' => '2025-12-01',
                'created_at' => '2025-11-25 10:30:00'
            ],
            (object)[
                'id' => 2,
                'nama_pelanggan' => 'Siti Aminah',
                'status' => 'Proses',
                'status_desain' => 'Perlu Revisi',
                'deadline' => '2025-12-02',
                'created_at' => '2025-11-26 14:00:00'
            ],
            (object)[
                'id' => 3,
                'nama_pelanggan' => 'Joko Pratama',
                'status' => 'Review',
                'status_desain' => 'Menunggu Persetujuan',
                'deadline' => '2025-12-05',
                'created_at' => '2025-11-27 09:15:00'
            ],
        ];

        return view('desain.dashboard', compact('antrian'));
    }

    /**
     * Halaman utama kelola desain
     * (Data dummy untuk tabel)
     */
    public function kelolaDesain()
    {
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
     * Halaman revisi desain
     */
    public function revisions()
    {
        return view('desain.revisions');
    }

    /**
     * Halaman riwayat desain
     */
    public function riwayat()
    {
        return view('desain.riwayat');
    }

    /**
     * Halaman pengaturan / template desain
     */
  public function pengaturan()
    {
        // --- PERBAIKAN: Menggunakan Model JenisLayanan yang sudah di-import ---
        try {
            // Mengambil semua data Jenis Layanan sebagai template
            $templates = JenisLayanan::all(); 
        } catch (\Exception $e) {
            // Fallback jika Model/Tabel belum siap (untuk menghindari error crash)
            $templates = collect([]); 
        }

        // Perhatian: Pastikan nama view yang dipanggil sesuai dengan yang ada di routes!
        // Jika rute Anda mengarah ke desain.template, maka viewnya harus 'desain.template'
        // Namun, jika Anda menggunakan view 'desain.desain' dari diskusi sebelumnya, ganti 'desain.template' menjadi 'desain.desain'.
        return view('desain.template', compact('templates'));
    }

}
