<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Desain; // Pastikan Model ini ada
use App\Models\JenisLayanan;
use Illuminate\Support\Facades\Auth;

class DesainController extends Controller
{
    // ==========================================
    // 1. METHOD DARI CLASS DIAGRAM
    // ==========================================

    /**
     * +getLatestVersion()
     * Digunakan secara internal untuk mendapatkan data revisi terakhir
     */
    private function getLatestVersion($pesananId)
    {
        return DB::table('desains')->where('pesanan_id', $pesananId)->first();
    }

    /**
     * +approve()
     * Menggantikan fungsi setujui() agar sesuai Class Diagram
     */
    public function approve(Request $request)
    {
       $request->validate([
        'nomor_order' => 'required',
    ]);

    $pesananId = DB::table('pesanans')
        ->whereRaw(
            "CONCAT('ORD-', LPAD(id, 3, '0')) = ?",
            [$request->nomor_order]
        )
        ->value('id');

    if (!$pesananId) {
        return back()->with('error', 'Pesanan tidak ditemukan');
    }

    DB::table('desains')
        ->where('pesanan_id', $pesananId)
        ->update([
            'status_desain_id' => 4, // ID Disetujui
            'updated_at' => now(),
        ]);

    return back()->with('success', 'Desain berhasil disetujui');
    }

    /**
     * +requestRevision(string feedback)
     * Menggantikan fungsi revisi() agar sesuai Class Diagram
     */
    public function requestRevision(Request $request)
    {
        $request->validate([
            'nomor_order'    => 'required',
            'catatan_revisi' => 'required|string' // feedback parameter
        ]);

        $pesananId = $this->getPesananIdFromOrderNumber($request->nomor_order);

        // Ambil ID status "Revisi" secara dinamis
        $statusRevisi = DB::table('status_desains')->where('nama_status', 'Revisi')->value('id') ?? 3;

        DB::table('desains')->where('pesanan_id', $pesananId)->update([
            'status_desain_id' => $statusRevisi,
            'catatan_revisi'   => $request->catatan_revisi, // atribut catatan
            'updated_at'       => now(),
        ]);

        return back()->with('success', 'Permintaan revisi berhasil dikirim');
    }

    // ==========================================
    // 2. FUNGSI CORE & UPLOAD (Sesuai Kode Anda)
    // ==========================================

    public function upload(Request $request)
{
    // 1. Validasi Input
    $request->validate([
        'nomor_order' => 'required',
        'file_desain' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
    ]);

    // 2. Ambil ID pesanan berdasarkan format ORD-xxx
    // Pastikan ID yang diambil benar-benar integer
    $pesananId = DB::table('pesanans')
        ->whereRaw("CONCAT('ORD-', LPAD(id, 3, '0')) = ?", [$request->nomor_order])
        ->value('id');

    if (!$pesananId) {
        return back()->with('error', 'ID Pesanan ' . $request->nomor_order . ' tidak ditemukan!');
    }

    // 3. Handle File Upload
    if ($request->hasFile('file_desain')) {
        $file = $request->file('file_desain');
        $fileName = 'desain_' . $pesananId . '_' . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('desain', $fileName, 'public');

        // 4. Ambil Status "Menunggu" dari tabel status_desains
        $statusMenunggu = DB::table('status_desains')
            ->whereRaw('LOWER(nama_status) = ?', ['menunggu'])
            ->value('id') ?? 1;

        // 5. UPDATE atau INSERT ke tabel desains
        // Kita gunakan updateOrInsert agar jika data belum ada, dia akan CREATE.
        DB::table('desains')->updateOrInsert(
            ['pesanan_id' => $pesananId], // Kunci pencarian
            [
                'status_desain_id' => $statusMenunggu,
                'file_desain_path' => $path, // <--- Ini yang krusial
                'updated_at'       => now(),
                'created_at'       => DB::raw('IFNULL(created_at, NOW())') 
            ]
        );

        return back()->with('success', 'File desain berhasil diupload untuk ' . $request->nomor_order);
    }

    return back()->with('error', 'Gagal memproses file.');
}

    /**
     * Helper untuk konversi ORD-001 ke ID integer
     */
    private function getPesananIdFromOrderNumber($orderNumber)
    {
        return DB::table('pesanans')
            ->whereRaw("CONCAT('ORD-', LPAD(id, 3, '0')) = ?", [$orderNumber])
            ->value('id');
    }

    // ==========================================
    // 3. DASHBOARD & VIEW (Sesuai Kode Anda)
    // ==========================================

    public function dashboard()
    {
        $antrian = DB::table('desains')
            ->leftJoin('pesanans', 'desains.pesanan_id', '=', 'pesanans.id')
            ->leftJoin('pelanggans', 'pesanans.pelanggan_id', '=', 'pelanggans.id')
            ->leftJoin('status_desains', 'desains.status_desain_id', '=', 'status_desains.id')
            ->select('pesanans.id', 'pelanggans.nama as nama_pelanggan', 'status_desains.nama_status as status_desain', 'desains.created_at')
            ->orderBy('desains.created_at', 'desc')->get();

        $menunggu = DB::table('desains')->join('status_desains', 'desains.status_desain_id', '=', 'status_desains.id')->whereRaw('LOWER(status_desains.nama_status) LIKE ?', ['%menunggu%'])->count();
        $sedangProses = DB::table('desains')->join('status_desains', 'desains.status_desain_id', '=', 'status_desains.id')->whereRaw('LOWER(status_desains.nama_status) LIKE ?', ['%setuju%'])->count();
        $selesai = DB::table('desains')->join('status_desains', 'desains.status_desain_id', '=', 'status_desains.id')->whereRaw('LOWER(status_desains.nama_status) LIKE ?', ['%revisi%'])->count();

        return view('desain.dashboard', compact('antrian', 'menunggu', 'sedangProses', 'selesai'));
    }

    public function kelolaDesain()
{
    // 1. Ambil SEMUA data desain (Tanpa filter != 4) untuk hitungan Card Statistik
    $allDesigns = DB::table('desains')
        ->leftJoin('status_desains', 'desains.status_desain_id', '=', 'status_desains.id')
        ->select('status_desains.nama_status as status_desain')
        ->get();

    // 2. Ambil data untuk LIST ANTRIAN (Hanya yang belum disetujui/selesai)
    $designs = DB::table('desains')
        ->leftJoin('pesanans', 'desains.pesanan_id', '=', 'pesanans.id')
        ->leftJoin('pelanggans', 'pesanans.pelanggan_id', '=', 'pelanggans.id')
        ->leftJoin('status_desains', 'desains.status_desain_id', '=', 'status_desains.id')
        ->leftJoin('detail_pesanans', 'pesanans.id', '=', 'detail_pesanans.pesanan_id')
        ->where('desains.status_desain_id', '!=', 4) // Antrian tidak menampilkan ID 4
        ->select(
            DB::raw("CONCAT('ORD-', LPAD(pesanans.id, 3, '0')) as nomor_order"),
            'pelanggans.nama as pelanggan',
            'pesanans.tanggal_pesanan as tanggal_order',
            'status_desains.nama_status as status_desain',
            'detail_pesanans.jenis_layanan_id',
            'detail_pesanans.jumlah',
            'desains.catatan_revisi as catatan_desain',
            'desains.created_at'
        )
        ->orderBy('desains.created_at', 'desc')
        ->get();

    // Kirim kedua variabel ke View
    return view('desain.designs', compact('designs', 'allDesigns'));
}

 public function riwayat()
{
    $riwayat = DB::table('desains')
        ->join('pesanans', 'desains.pesanan_id', '=', 'pesanans.id')
        ->join('pelanggans', 'pesanans.pelanggan_id', '=', 'pelanggans.id')
        ->join('status_desains', 'desains.status_desain_id', '=', 'status_desains.id')
        ->where('desains.status_desain_id', 4) // Disetujui
        ->select(
            DB::raw("CONCAT('ORD-', LPAD(pesanans.id, 3, '0')) as nomor_order"),
            'pelanggans.nama as nama_pelanggan',
            'desains.file_desain_path',
            'status_desains.nama_status',
            'desains.updated_at'
        )
        ->orderBy('desains.updated_at', 'desc')
        ->get();

    return view('desain.riwayat', compact('riwayat'));
}

    // Fungsi pendukung lainnya (Inventory, Jadwal, Pengaturan) tetap dipertahankan...
   
    public function pengaturan() {
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
public function revisions()
    {
        // Kita ambil data desain dengan status_desain_id = 3 (Revisi)
        // Dan pastikan catatan_revisi tidak kosong
        $revisions = DB::table('desains')
            ->join('pesanans', 'desains.pesanan_id', '=', 'pesanans.id')
            ->join('pelanggans', 'pesanans.pelanggan_id', '=', 'pelanggans.id')
            ->join('status_desains', 'desains.status_desain_id', '=', 'status_desains.id')
            ->where('desains.status_desain_id', 3) // Angka 3 biasanya untuk status 'Revisi'
            ->select(
                DB::raw("CONCAT('ORD-', LPAD(pesanans.id, 3, '0')) as nomor_order"),
                'pelanggans.nama as pelanggan',
                'desains.file_desain_path',
                'desains.catatan_revisi',
                'desains.updated_at as tanggal_revisi'
            )
            ->orderBy('desains.updated_at', 'desc')
            ->get();

        return view('desain.revisions', compact('revisions'));
    }

}