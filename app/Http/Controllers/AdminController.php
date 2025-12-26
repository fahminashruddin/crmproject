<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;      
use App\Models\Pembayaran;
use App\Models\Pengguna;

class AdminController extends Controller
{
    public function index()
    {

        $totalPesanan = Pesanan::count();

        // Menghitung pesanan selesai menggunakan "whereHas" (Filter via relasi)
        // Ini mencari Pesanan yang punya StatusPesanan bernama 'selesai'
        $pesananSelesai = Pesanan::whereHas('statusPesanan', function ($query) {
            $query->where('nama_status', 'like', '%selesai%');
        })->count();

        $pembayaranPending = Pembayaran::where('status', 'pending')->count();

        $totalPendapatan = Pembayaran::whereIn('status', ['verifikasi', 'lunas', 'verified'])
            ->sum('nominal');

        // Kita gunakan 'with' untuk menarik data Pelanggan, Status, dan Detail Layanan sekaligus.
        // Asumsi: Di model DetailPesanan ada relasi ke JenisLayanan
        $pesananTerbaru = Pesanan::with([
                'pelanggan',
                'statusPesanan',
                'detailPesanans.jenisLayanan' // Nested relationship
            ])
            ->orderBy('tanggal_pesanan', 'desc')
            ->take(5) // Sama dengan limit(5)
            ->get();

        // Pastikan model Pengguna punya relasi ke Role: public function role() { ... }
        $aktivitasUser = Pengguna::with('role')
            ->orderBy('name', 'asc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalPesanan',
            'pesananSelesai',
            'pembayaranPending',
            'totalPendapatan',
            'pesananTerbaru',
            'aktivitasUser'
        ));
    }

    public function settings()
    {
        return view('admin.settings');
    }

    public function notifications()
    {
        // Jika nanti mau ambil dari DB: $notifications = auth()->user()->notifications;
        $notifications = [
            (object)[
                'id' => 1,
                'type' => 'order',
                'title' => 'Pesanan baru masuk',
                'message' => 'ORD-003 dari CV. Sukses Mandiri',
                'is_read' => false,
                'created_at' => now()->subMinutes(5),
            ],
            (object)[
                'id' => 2,
                'type' => 'payment',
                'title' => 'Pembayaran terverifikasi',
                'message' => 'ORD-001 pembayaran Rp 750.000',
                'is_read' => true,
                'created_at' => now()->subHour(),
            ],
        ];

        return view('admin.notifications', compact('notifications'));
    }
}
