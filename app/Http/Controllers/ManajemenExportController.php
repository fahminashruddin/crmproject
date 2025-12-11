<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ManajemenExportController extends Controller
{
    /** 
     * Halaman utama Export Data 
     */
    public function index()
    {
        return view('manajemen.export.index');
    }


    /**
     * Export Semua Pesanan
     */
    public function exportPesanan()
    {
        $data = DB::table('pesanans')->get();

        return $this->exportCSV($data, 'export_pesanan.csv');
    }


    /**
     * Export Data Pelanggan
     */
    public function exportPelanggan()
    {
        $data = DB::table('pelanggans')->get();

        return $this->exportCSV($data, 'export_pelanggan.csv');
    }


    /**
     * Export Laporan Keuangan
     */
    public function exportKeuangan()
    {
        $data = DB::table('pembayarans')->get();

        return $this->exportCSV($data, 'laporan_pembayarans.csv');
    }


    /**
     * Export Laporan Produksi
     */
    public function exportProduksi()
    {
        $data = DB::table('produksis')->get();

        return $this->exportCSV($data, 'laporan_produksi.csv');
    }


    /**
     * Fungsi umum export CSV
     */
    private function exportCSV($data, $filename)
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\""
        ];

        $callback = function () use ($data) {
            $file = fopen('php://output', 'w');

            if (count($data) > 0) {
                // Tulis header kolom
                fputcsv($file, array_keys((array) $data[0]));
            }

            // Tulis semua baris
            foreach ($data as $row) {
                fputcsv($file, (array) $row);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
