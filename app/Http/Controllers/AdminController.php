<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\Pembayaran;
use App\Models\Pengguna;
use Illuminate\Support\Facades\Auth;

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

        // Ambil user yang sedang login
        $user = Auth::user();

        // Ambil semua notifikasi (Laravel otomatis mengurutkan created_at desc)
        if ($user) {
            $notifications = $user->notifications;
        } else {
            $notifications = collect(); // Koleksi kosong jika tidak ada user (safety)
        }

        return view('admin.notifications', compact('notifications'));
    }

    public function markNotificationsAsRead()
    {
        $user = Auth::user();

        if ($user) {
            $user->unreadNotifications->markAsRead();
        }

        return redirect()->back();
    }
}
