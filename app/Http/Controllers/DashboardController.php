<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Tampilkan halaman dashboard (placeholder).
     */
    public function index()
    {
        // Ambil data statistik untuk empat card
        $totalPesanan = DB::table('pesanans')->count();

        // cari id status 'Selesai' (case-insensitive), jika tidak ada gunakan 0 sehingga count = 0
        $selesaiStatus = DB::table('status_pesanans')
            ->whereRaw('LOWER(nama_status) = ?', ['selesai'])
            ->value('id') ?: 0;

        $pesananSelesai = $selesaiStatus ? DB::table('pesanans')->where('status_pesanan_id', $selesaiStatus)->count() : 0;

        $pembayaranPending = DB::table('pembayarans')->where('status', 'pending')->count();

        // total pendapatan dari pembayaran dengan status 'verifikasi'
        $totalPendapatan = DB::table('pembayarans')->where('status', 'verifikasi')->sum('nominal');

        // Pesanan terbaru (ambil 5 terakhir)
        $pesananTerbaru = DB::table('pesanans')
            ->leftJoin('pelanggans', 'pesanans.pelanggan_id', '=', 'pelanggans.id')
            ->leftJoin('status_pesanans', 'pesanans.status_pesanan_id', '=', 'status_pesanans.id')
            ->select('pesanans.*', 'pelanggans.nama as pelanggan_nama', 'status_pesanans.nama_status')
            ->orderBy('pesanans.tanggal_pesanan', 'desc')
            ->limit(5)
            ->get();

        // Aktivitas user (list pengguna dengan role)
        $aktivitasUser = DB::table('penggunas')
            ->leftJoin('roles', 'penggunas.role_id', '=', 'roles.id')
            ->select('penggunas.name', 'penggunas.email', 'roles.nama_role')
            ->orderBy('penggunas.name')
            ->get();

        return view('dashboard', compact(
            'totalPesanan', 'pesananSelesai', 'pembayaranPending', 'totalPendapatan', 'pesananTerbaru', 'aktivitasUser'
        ));
    }
}