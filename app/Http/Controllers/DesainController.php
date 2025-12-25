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
        $designs = Desain::with([
            'pesanan.pelanggan',
            'statusDesain'
        ])->latest()->get();

        return view('desain.designs', compact('designs'));
    }
    public function dashboard()
    {
         $antrian = DB::table('desains')
        ->leftJoin('pesanans', 'desains.pesanan_id', '=', 'pesanans.id')
        ->leftJoin('pelanggans', 'pesanans.pelanggan_id', '=', 'pelanggans.id')
        ->leftJoin('status_desains', 'desains.status_desain_id', '=', 'status_desains.id')
        ->select(
            'pesanans.id',
            'pelanggans.nama as nama_pelanggan',
            'status_desains.nama_status as status_desain',
            'desains.created_at'
        )
        ->orderBy('desains.created_at', 'desc')
        ->get();

    // ===============================
    // STATISTIK DASHBOARD
    // ===============================
    $menunggu = DB::table('desains')
        ->join('status_desains', 'desains.status_desain_id', '=', 'status_desains.id')
        ->whereRaw('LOWER(status_desains.nama_status) LIKE ?', ['%menunggu%'])
        ->count();

    $sedangProses = DB::table('desains')
        ->join('status_desains', 'desains.status_desain_id', '=', 'status_desains.id')
        ->whereRaw('LOWER(status_desains.nama_status) LIKE ?', ['%setuju%'])
        ->count();

    $selesai = DB::table('desains')
        ->join('status_desains', 'desains.status_desain_id', '=', 'status_desains.id')
        ->whereRaw('LOWER(status_desains.nama_status) LIKE ?', ['%revisi%'])
        ->count();

    return view('desain.dashboard', compact(
        'antrian',
        'menunggu',
        'sedangProses',
        'selesai'
    ));

        return view('desain.dashboard', compact('antrian'));
    }

    /**
     * Halaman utama kelola desain
     * (Data dummy untuk tabel)
     */
  public function kelolaDesain()
{
    $designs = DB::table('desains')
        ->leftJoin('pesanans', 'desains.pesanan_id', '=', 'pesanans.id')
        ->leftJoin('pelanggans', 'pesanans.pelanggan_id', '=', 'pelanggans.id')
        ->leftJoin('status_desains', 'desains.status_desain_id', '=', 'status_desains.id')
        ->leftJoin('detail_pesanans', 'pesanans.id', '=', 'detail_pesanans.pesanan_id')
        ->select(
            DB::raw("CONCAT('ORD-', LPAD(pesanans.id, 3, '0')) as nomor_order"),
            'pelanggans.nama as pelanggan',
            'pesanans.tanggal_pesanan as tanggal_order',
            'status_desains.nama_status as status_desain',

            // dari detail_pesanans (yang PASTI ADA)
            'detail_pesanans.jenis_layanan_id',
            'detail_pesanans.jumlah',
            'detail_pesanans.harga_satuan',
            'detail_pesanans.subtotal',

            'desains.catatan_revisi as catatan_desain',
            'desains.created_at'
        )
        ->orderBy('desains.created_at', 'desc')
        ->get();

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
    public function setujui(Request $request)
{
    $request->validate([
        'pesanan_id' => 'required|exists:pesanans,id'
    ]);

    // Ambil ID status "disetujui" (aman dari perbedaan huruf)
    $statusDisetujui = DB::table('status_desains')
        ->whereRaw('LOWER(nama_status) = ?', ['disetujui'])
        ->value('id');

    if (!$statusDisetujui) {
        return redirect()->back()
            ->with('error', 'Status Disetujui tidak ditemukan di tabel status_desains');
    }

    DB::table('desains')
        ->where('pesanan_id', $request->pesanan_id)
        ->update([
            'status_desain_id' => $statusDisetujui,
            'updated_at'       => now(),
        ]);

    return redirect()->back()->with('success', 'Desain berhasil disetujui');
}



public function revisi(Request $request)
{
    $request->validate([
        'nomor_order'    => 'required',
        'catatan_revisi' => 'required|string'
    ]);

    // Ambil ID status "Revisi"
    $statusRevisi = DB::table('status_desains')
        ->where('nama_status', 'Revisi')
        ->value('id');

    DB::table('desains')
        ->join('pesanans', 'desains.pesanan_id', '=', 'pesanans.id')
        ->whereRaw("CONCAT('ORD-', LPAD(pesanans.id, 3, '0')) = ?", [$request->nomor_order])
        ->update([
            'desains.status_desain_id' => $statusRevisi,
            'desains.catatan_revisi'   => $request->catatan_revisi,
            'desains.updated_at'       => now(), // ⬅️ FIX DI SINI
        ]);

    return redirect()->back()->with('success', 'Desain berhasil dikirim untuk revisi');
}


}

