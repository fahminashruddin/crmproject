<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ManajemenController extends Controller
{
    // Validasi role Manajemen
    protected function ensureManajemen()
    {
        $roleId = DB::table('roles')
            ->whereRaw('LOWER(nama_role) = ?', ['manajemen'])
            ->value('id');

        if (! Auth::check() || Auth::user()->role_id != $roleId) {
            abort(403, 'Unauthorized. Only Manajemen role can access this.');
        }
    }

    /* =========================================================
     * DASHBOARD
     * ========================================================= */
    public function dashboard()
    {
        $this->ensureManajemen();

        /* 1. TOTAL PESANAN */
        $totalPesanan = DB::table('pesanans')->count();

        /* 2. TINGKAT PENYELESAIAN */
        $selesaiStatusId = DB::table('status_pesanans')
            ->whereRaw('LOWER(nama_status) = ?', ['selesai'])
            ->value('id');

        $jumlahSelesai = $selesaiStatusId
            ? DB::table('pesanans')->where('status_pesanan_id', $selesaiStatusId)->count()
            : 0;

        $persentaseSelesai = $totalPesanan > 0
            ? round(($jumlahSelesai / $totalPesanan) * 100)
            : 0;

        /* 3. TOTAL PENDAPATAN */
        $totalPendapatan = DB::table('pembayarans')
            ->where('status', 'verifikasi')
            ->sum('nominal');

        /* 4. RATA-RATA NILAI PESANAN */
        $rataRataPesanan = $jumlahSelesai > 0
            ? round($totalPendapatan / $jumlahSelesai)
            : 0;

        /* 5. DISTRIBUSI LAYANAN */
        $distribusiLayanan = DB::table('detail_pesanans')
            ->join('jenis_layanans', 'detail_pesanans.jenis_layanan_id', '=', 'jenis_layanans.id')
            ->select('jenis_layanans.nama_layanan', DB::raw('COUNT(detail_pesanans.id) as total'))
            ->groupBy('jenis_layanans.nama_layanan')
            ->get();

        $distribusiLayananArray = [];
        foreach ($distribusiLayanan as $item) {
            $distribusiLayananArray[$item->nama_layanan] = $item->total;
        }

        /* 6. STATUS PESANAN */
        $statusPesanan = DB::table('pesanans')
            ->join('status_pesanans', 'pesanans.status_pesanan_id', '=', 'status_pesanans.id')
            ->select('status_pesanans.nama_status', DB::raw('COUNT(pesanans.id) as total'))
            ->groupBy('status_pesanans.nama_status')
            ->get();

        $statusCounts = [];
        foreach ($statusPesanan as $item) {
            $statusCounts[$item->nama_status] = $item->total;
        }

        /* 7. PESANAN TERBARU */
        $pesananTerbaru = DB::table('pesanans')
            ->join('pelanggans', 'pesanans.pelanggan_id', '=', 'pelanggans.id')
            ->join('status_pesanans', 'pesanans.status_pesanan_id', '=', 'status_pesanans.id')
            ->select(
                'pesanans.*',
                'pelanggans.nama as pelanggan_nama',
                'status_pesanans.nama_status'
            )
            ->orderBy('pesanans.created_at', 'desc')
            ->limit(5)
            ->get();

        /* 8. AKTIVITAS USER */
        $aktivitasUser = DB::table('penggunas')
            ->leftJoin('roles', 'penggunas.role_id', '=', 'roles.id')
            ->select('penggunas.name', 'penggunas.email', 'roles.nama_role')
            ->orderBy('penggunas.name')
            ->get();

        /* RETURN VIEW */
        return view('manajemen.dashboard', [
            'totalPesanan'      => $totalPesanan,
            'jumlahSelesai'     => $jumlahSelesai,
            'pesananSelesai'    => $jumlahSelesai,
            'persentaseSelesai' => $persentaseSelesai,
            'totalPendapatan'   => $totalPendapatan,
            'rataRataPesanan'   => $rataRataPesanan,
            'distribusiLayanan' => $distribusiLayananArray,
            'statusCounts'      => $statusCounts,
            'pesananTerbaru'    => $pesananTerbaru,
            'aktivitasUser'     => $aktivitasUser,
        ]);
    }

    /* =========================================================
     * REPORTS
     * ========================================================= */
    public function reports()
    {
        $this->ensureManajemen();

        return view('manajemen.reports', [
            'reports' => [],
        ]);
    }

    /* =========================================================
     * ANALYTICS
     * ========================================================= */
    public function analytics()
    {
        $this->ensureManajemen();

        return view('manajemen.analytics', [
            'analytics' => [],
        ]);
    }
}
