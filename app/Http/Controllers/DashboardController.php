<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil semua desain + relasi penting
        $desains = Desain::with([
            'pesanan.pelanggan',
            'statusDesain'
        ])->get();

        // ======================
        // STATISTIK
        // ======================
        $menunggu = $desains->where('statusDesain.nama_status', 'Menunggu Desain')->count();

        $sedangProses = $desains->where('statusDesain.nama_status', 'Disetujui')->count();

        $selesai = $desains->where('statusDesain.nama_status', 'Perlu Revisi')->count();

        // ======================
        // ANTRIAN DESAIN
        // ======================
        $antrian = $desains->map(function ($desain) {
            return (object) [
                'id' => $desain->pesanan->id ?? null,
                'nama_pelanggan' => $desain->pesanan->pelanggan->nama_perusahaan ?? '-',
                'status_desain' => $desain->statusDesain->nama_status ?? '-',
                'catatan' => $desain->pesanan->catatan ?? null,
                'created_at' => $desain->pesanan->tanggal_pesanan ?? now(),
            ];
        });

        return view('desain.dashboard', compact(
            'menunggu',
            'sedangProses',
            'selesai',
            'antrian'
        ));
    }
}
