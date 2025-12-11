<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalitikController extends Controller
{
    /**
     * Halaman Analytics
     */
    public function index(Request $request)
    {
        $start = $request->start_date;
        $end   = $request->end_date;

        // ===========================
        // 1. TREN PESANAN (Line Chart)
        // ===========================
        $trenPesananQuery = DB::table('pesanans')
            ->select(
                DB::raw('DATE(tanggal_pesanan) as tanggal'),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'asc');

        if ($start && $end) {
            $trenPesananQuery->whereBetween('tanggal_pesanan', [$start, $end]);
        }

        $trenPesanan = $trenPesananQuery->get();


        // ================================
        // 2. DISTRIBUSI PENDAPATAN (Pie Chart)
        // ================================
        $pendapatanQuery = DB::table('pembayarans')
            ->join('pesanans', 'pembayarans.pesanan_id', '=', 'pesanans.id')
            ->join('detail_pesanans', 'detail_pesanans.pesanan_id', '=', 'pesanans.id')
            ->join('jenis_layanans', 'detail_pesanans.jenis_layanan_id', '=', 'jenis_layanans.id')
            ->select(
                'jenis_layanans.nama_layanan',
                DB::raw('SUM(pembayarans.nominal) as total')
            )
            ->groupBy('jenis_layanans.nama_layanan');

        if ($start && $end) {
            $pendapatanQuery->whereBetween('pembayarans.created_at', [$start, $end]);
        }

        $distribusiPendapatan = $pendapatanQuery->get();


        return view('manajemen.analytics', compact(
            'trenPesanan',
            'distribusiPendapatan',
            'start',
            'end'
        ));
    }
}
