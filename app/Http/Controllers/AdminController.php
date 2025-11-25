<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    // Remove __construct and use middleware in routes/web.php or via protected $middleware property if needed

    protected function ensureAdmin()
    {
        $roleId = DB::table('roles')->whereRaw('LOWER(nama_role) = ?', ['admin'])->value('id');
        if (! Auth::check() || Auth::user()->role_id != $roleId) {
            abort(403);
        }
    }

    public function dashboard()
    {
        $this->ensureAdmin();

        $totalPesanan = DB::table('pesanans')->count();

        $selesaiStatus = DB::table('status_pesanans')
            ->whereRaw('LOWER(nama_status) = ?', ['selesai'])
            ->value('id') ?: 0;

        $pesananSelesai = $selesaiStatus ? DB::table('pesanans')->where('status_pesanan_id', $selesaiStatus)->count() : 0;

        $pembayaranPending = DB::table('pembayarans')->where('status', 'pending')->count();

        $totalPendapatan = DB::table('pembayarans')->where('status', 'verifikasi')->sum('nominal');

        $pesananTerbaru = DB::table('pesanans')
            ->leftJoin('pelanggans', 'pesanans.pelanggan_id', '=', 'pelanggans.id')
            ->leftJoin('status_pesanans', 'pesanans.status_pesanan_id', '=', 'status_pesanans.id')
            ->select('pesanans.*', 'pelanggans.nama as pelanggan_nama', 'status_pesanans.nama_status')
            ->orderBy('pesanans.tanggal_pesanan', 'desc')
            ->limit(5)
            ->get();

        $aktivitasUser = DB::table('penggunas')
            ->leftJoin('roles', 'penggunas.role_id', '=', 'roles.id')
            ->select('penggunas.*', 'roles.nama_role')
            ->orderBy('penggunas.name')
            ->get();

        return view('admin.dashboard', compact(
            'totalPesanan', 'pesananSelesai', 'pembayaranPending', 'totalPendapatan', 'pesananTerbaru', 'aktivitasUser'
        ));
    }

    public function orders()
    {
        $this->ensureAdmin();

        $orders = DB::table('pesanans')
            ->leftJoin('pelanggans', 'pesanans.pelanggan_id', '=', 'pelanggans.id')
            ->leftJoin('status_pesanans', 'pesanans.status_pesanan_id', '=', 'status_pesanans.id')
            ->select('pesanans.*', 'pelanggans.nama as pelanggan_nama', 'status_pesanans.nama_status')
            ->orderBy('pesanans.tanggal_pesanan', 'desc')
            ->get();

        return view('admin.orders', compact('orders'));
    }

    public function payments()
    {
        $this->ensureAdmin();

        $payments = DB::table('pembayarans')
            ->leftJoin('pesanans', 'pembayarans.pesanan_id', '=', 'pesanans.id')
            ->select('pembayarans.*', 'pesanans.id as pesanan_id', 'pesanans.pelanggan_id')
            ->orderBy('pembayarans.created_at', 'desc')
            ->get();

        return view('admin.payments', compact('payments'));
    }

    public function users()
    {
        $this->ensureAdmin();

        $users = DB::table('penggunas')
            ->leftJoin('roles', 'penggunas.role_id', '=', 'roles.id')
            ->select('penggunas.*', 'roles.nama_role')
            ->orderBy('penggunas.name')
            ->get();

        return view('admin.users', compact('users'));
    }

    public function settings()
    {
        $this->ensureAdmin();
        return view('admin.settings');
    }

    public function notifications()
    {
        $this->ensureAdmin();
        $notifications = [];
        return view('admin.notifications', compact('notifications'));
    }
}
