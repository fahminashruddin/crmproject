<?php

namespace App\Http\Controllers;

use App\Exports\LaporanExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    /**
     * EXPORT LAPORAN
     */
   public function export(Request $request)
{
    $format = $request->format;

    // EXPORT EXCEL
    if ($format === 'excel') {
        return Excel::download(
            new LaporanExport($request->start_date, $request->end_date),
            'laporan.xlsx'
        );
    }

    // EXPORT PDF (PAKAI DATA YANG SAMA DENGAN INDEX)
    if ($format === 'pdf') {

        // Ambil data laporan lengkap (tidak pakai dummy)
        $data = $this->index($request)->getData();

        // Pakai view PDF khusus
        $pdf = Pdf::loadView('manajemen.laporan.pdf', (array)$data)
                  ->setPaper('A4', 'portrait');

        return $pdf->download('laporan.pdf');
    }

    return back()->with('error', 'Format tidak valid');
}

/**
 * GET LAPORAN RINGKASAN PER LAYANAN DAN AKTIVITAS TERBARU
 */
private function getLaporanRingkasan($start, $end)
{
    // Performa per layanan
    $perLayanan = DB::table('detail_pesanans')
        ->join('pesanans', 'detail_pesanans.pesanan_id', '=', 'pesanans.id')
        ->join('jenis_layanans', 'detail_pesanans.jenis_layanan_id', '=', 'jenis_layanans.id')
        ->leftJoin('pembayarans', 'pembayarans.pesanan_id', '=', 'pesanans.id')
        ->select(
            'jenis_layanans.nama_layanan',
            DB::raw('COUNT(detail_pesanans.id) as jumlah_pesanan'),
            DB::raw('COALESCE(SUM(pembayarans.nominal),0) as total_nominal'),
            DB::raw('COALESCE(SUM(pembayarans.nominal)/COUNT(detail_pesanans.id),0) as rata_rata')
        )
        ->whereBetween('pesanans.tanggal_pesanan', [$start, $end])
        ->groupBy('jenis_layanans.nama_layanan')
        ->get();

    // Aktivitas terbaru (5 pesanan terakhir)
    $aktivitasTerbaru = DB::table('pesanans')
        ->join('pelanggans', 'pesanans.pelanggan_id', '=', 'pelanggans.id')
        ->join('detail_pesanans', 'detail_pesanans.pesanan_id', '=', 'pesanans.id')
        ->join('jenis_layanans', 'detail_pesanans.jenis_layanan_id', '=', 'jenis_layanans.id')
        ->leftJoin('status_pesanans', 'pesanans.status_pesanan_id', '=', 'status_pesanans.id')
        ->leftJoin('pembayarans', 'pembayarans.pesanan_id', '=', 'pesanans.id')
        ->select(
            'pesanans.id',
            'pelanggans.nama as pelanggan',
            'jenis_layanans.nama_layanan',
            'pesanans.tanggal_pesanan',
            'status_pesanans.nama_status',
            'pembayarans.nominal'
        )
        ->whereBetween('pesanans.tanggal_pesanan', [$start, $end])
        ->orderBy('pesanans.tanggal_pesanan', 'desc')
        ->limit(5)
        ->get();

    return [
        'perLayanan' => $perLayanan,
        'aktivitasTerbaru' => $aktivitasTerbaru,
    ];
}


    /**
     * HALAMAN UTAMA LAPORAN
     */
    public function index(Request $request)
    {
        $start = $request->start_date;
        $end   = $request->end_date;

        // Query dasar
        $baseQuery = DB::table('pesanans')
            ->leftJoin('pelanggans', 'pesanans.pelanggan_id', '=', 'pelanggans.id')
            ->leftJoin('status_pesanans', 'pesanans.status_pesanan_id', '=', 'status_pesanans.id');

        if ($start && $end) {
            $baseQuery->whereBetween('pesanans.tanggal_pesanan', [$start, $end]);
        }

        // 1. Total pesanan
        $totalPesanan = (clone $baseQuery)->count();

        // 2. Pesanan per status
        $statusCounts = (clone $baseQuery)
            ->select('status_pesanans.nama_status', DB::raw('COUNT(*) as jumlah'))
            ->groupBy('status_pesanans.nama_status')
            ->get();

        // 3. Pendapatan
        $pendapatanQuery = DB::table('pembayarans')->where('status', 'verifikasi');

        if ($start && $end) {
            $pendapatanQuery->whereBetween('created_at', [$start, $end]);
        }

        $totalPendapatan = $pendapatanQuery->sum('nominal');

        // 4. Rata-rata nilai per pesanan
        $rataRataNilai = $totalPesanan > 0
            ? $totalPendapatan / $totalPesanan
            : 0;

        // 5. Distribusi layanan
        $distribusiQuery = DB::table('pesanans')
            ->leftJoin('detail_pesanans', 'detail_pesanans.pesanan_id', '=', 'pesanans.id')
            ->leftJoin('jenis_layanans', 'detail_pesanans.jenis_layanan_id', '=', 'jenis_layanans.id')
            ->select('jenis_layanans.nama_layanan', DB::raw('COUNT(*) as total'))
            ->groupBy('jenis_layanans.nama_layanan');

        if ($start && $end) {
            $distribusiQuery->whereBetween('pesanans.tanggal_pesanan', [$start, $end]);
        }

        // Hitung pesanan selesai & pending
        $selesai = $statusCounts->where('nama_status', 'Selesai')->first()->jumlah ?? 0;
        $pending = $statusCounts->where('nama_status', 'Pending')->first()->jumlah ?? 0;

        $completionRate = $totalPesanan > 0 ? ($selesai / $totalPesanan) * 100 : 0;

        $distribusiLayanan = $distribusiQuery->get();
        // Pesanan selesai & pending + completion rate
        $selesai = $statusCounts->where('nama_status', 'Selesai')->first()->jumlah ?? 0;
        $pending = $statusCounts->where('nama_status', 'Pending')->first()->jumlah ?? 0;
        $completionRate = $totalPesanan > 0 ? ($selesai / $totalPesanan) * 100 : 0;

        // 6. Detail pesanan
        $detailPesanan = (clone $baseQuery)
            ->leftJoin('detail_pesanans', 'detail_pesanans.pesanan_id', '=', 'pesanans.id')
            ->leftJoin('jenis_layanans', 'detail_pesanans.jenis_layanan_id', '=', 'jenis_layanans.id')
            ->select(
                'pesanans.id',
                'pelanggans.nama as pelanggan',
                'jenis_layanans.nama_layanan',
                'pesanans.tanggal_pesanan',
                'status_pesanans.nama_status'
            )
            ->orderBy('pesanans.tanggal_pesanan', 'desc')
            ->get();

            $start = $start ?? DB::table('pesanans')->min('tanggal_pesanan');
            $end   = $end ?? now()->toDateString();

            $ringkasan = $this->getLaporanRingkasan($start, $end);

        return view('manajemen.laporan.index', compact(
            'totalPesanan',
             'pending',
             'selesai',
             'completionRate',
            'statusCounts',
            'totalPendapatan',
            'rataRataNilai',
            'distribusiLayanan',
            'detailPesanan',
            'start',
            'end',
            'ringkasan'
        ));
    }
}
