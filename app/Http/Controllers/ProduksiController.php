<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ProduksiController extends Controller
{
    // === MIDDLEWARE MANUaAL ===
    protected function ensureProduksi()
    {
        // Asumsi role 'produksi' ada di tabel roles
        $roleId = DB::table('roles')->whereRaw('LOWER(nama_role) = ?', ['produksi'])->value('id');
        
        // Bypass check jika role tidak ditemukan (untuk dev) atau sesuaikan logic auth Anda
        if (Auth::check() && $roleId && Auth::user()->role_id != $roleId) {
             abort(403, 'Unauthorized. Hanya tim Produksi yang boleh masuk sini.');
        }
    }

    // === 1. DASHBOARD UTAMA ===
    public function dashboard()
    {
        $this->ensureProduksi();

        // --- 1. Hitung Statistik ---
        $stats = DB::table('pesanans')
            ->join('status_pesanans', 'pesanans.status_pesanan_id', '=', 'status_pesanans.id')
            ->select('status_pesanans.nama_status', DB::raw('count(*) as total'))
            ->groupBy('status_pesanans.nama_status')
            ->pluck('total', 'nama_status')
            ->toArray();

        $menunggu = ($stats['Menunggu'] ?? 0) + ($stats['Desain Disetujui'] ?? 0); 
        $sedangProses = $stats['Produksi'] ?? 0;
        $selesai = $stats['Selesai'] ?? 0;

        // --- 2. Ambil Antrian Produksi (Untuk List Card) ---
        $antrian = DB::table('pesanans')
            ->join('pelanggans', 'pesanans.pelanggan_id', '=', 'pelanggans.id')
            ->join('status_pesanans', 'pesanans.status_pesanan_id', '=', 'status_pesanans.id')
            ->leftJoin('desains', 'pesanans.id', '=', 'desains.pesanan_id')
            ->select(
                'pesanans.id',
                'pesanans.tanggal_pesanan',
                'pesanans.catatan',
                'pelanggans.nama as nama_pelanggan',
                'status_pesanans.nama_status as status_produksi',
                'desains.file_desain_path'
            )
            ->whereIn('status_pesanans.nama_status', ['Desain Disetujui', 'Menunggu', 'Produksi'])
            ->orderBy('pesanans.updated_at', 'desc')
            ->limit(10) // Limit antrian di dashboard agar tidak terlalu panjang
            ->get();

        // Inject detail item
        foreach ($antrian as $item) {
            $this->injectOrderDetails($item);
        }

        return view('produksi.dashboard', compact('menunggu', 'sedangProses', 'selesai', 'antrian'));
    }

    // === 2. HALAMAN MENU PRODUKSI (LIST LENGKAP) ===
    public function productions(Request $request)
    {
        $this->ensureProduksi();

        // --- HITUNG STATISTIK (PENTING: Agar tidak error di view productions) ---
        $stats = DB::table('pesanans')
            ->join('status_pesanans', 'pesanans.status_pesanan_id', '=', 'status_pesanans.id')
            ->select('status_pesanans.nama_status', DB::raw('count(*) as total'))
            ->groupBy('status_pesanans.nama_status')
            ->pluck('total', 'nama_status')
            ->toArray();

        $menunggu = ($stats['Menunggu'] ?? 0) + ($stats['Desain Disetujui'] ?? 0); 
        $sedangProses = $stats['Produksi'] ?? 0;
        $selesai = $stats['Selesai'] ?? 0;

        // --- QUERY UTAMA LIST PESANAN ---
        $query = DB::table('pesanans')
            ->join('pelanggans', 'pesanans.pelanggan_id', '=', 'pelanggans.id')
            ->join('status_pesanans', 'pesanans.status_pesanan_id', '=', 'status_pesanans.id')
            ->leftJoin('desains', 'pesanans.id', '=', 'desains.pesanan_id')
            ->select(
                'pesanans.id',
                'pesanans.tanggal_pesanan',
                'pesanans.catatan',
                'pelanggans.nama as nama_pelanggan',
                'pelanggans.no_hp',
                'status_pesanans.nama_status as status_produksi',
                'desains.file_desain_path'
            )
            ->whereIn('status_pesanans.nama_status', ['Desain Disetujui', 'Menunggu', 'Produksi', 'Selesai']);

        // Filter Pencarian
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('pelanggans.nama', 'like', "%{$search}%")
                  ->orWhere('pesanans.id', 'like', "%{$search}%");
            });
        }

        // Filter Tab Status
        if ($request->has('status') && $request->status != '') {
            $query->where('status_pesanans.nama_status', $request->status);
        }

        $productions = $query->orderBy('pesanans.updated_at', 'desc')->paginate(10);

        // Inject detail item untuk setiap baris
        foreach ($productions as $item) {
            $this->injectOrderDetails($item);
        }

        // Return view dengan membawa variabel statistik juga
        return view('produksi.productions', compact('productions', 'menunggu', 'sedangProses', 'selesai'));
    }

    // === HELPER: Mengambil Detail Pesanan (Refactored) ===
    // Digunakan oleh dashboard() dan productions() agar kodenya tidak berulang
    private function injectOrderDetails($item)
    {
        $detail = DB::table('detail_pesanans')
            ->join('jenis_layanans', 'detail_pesanans.jenis_layanan_id', '=', 'jenis_layanans.id')
            ->where('detail_pesanans.pesanan_id', $item->id)
            ->select(
                'detail_pesanans.jumlah',
                'detail_pesanans.spesifikasi',
                'jenis_layanans.nama_layanan'
            )
            ->first();

        $item->jumlah = $detail ? $detail->jumlah : 0;
        $item->layanan = $detail ? $detail->nama_layanan : '-';
        $item->spesifikasi = $detail ? $detail->spesifikasi : '-';
        
        // Bersihkan nama file agar terlihat rapi
        $item->nama_file_desain = $item->file_desain_path ? basename($item->file_desain_path) : 'Belum ada file';
    }

    // === 3. AKSI: MULAI PRODUKSI ===
    public function startProduction($id)
    {
        $this->ensureProduksi();

        // Cari ID status 'Produksi'
        $statusId = DB::table('status_pesanans')->where('nama_status', 'Produksi')->value('id');

        if ($statusId) {
            DB::table('pesanans')->where('id', $id)->update([
                'status_pesanan_id' => $statusId,
                'updated_at' => now()
            ]);
            return redirect()->back()->with('success', 'Status pesanan berhasil diubah menjadi Sedang Diproduksi.');
        }

        return redirect()->back()->with('error', 'Gagal: Status "Produksi" tidak ditemukan di database.');
    }

    // === 4. AKSI: SELESAI PRODUKSI ===
    public function completeProduction($id)
    {
        $this->ensureProduksi();

        // Cari ID status 'Selesai'
        $statusId = DB::table('status_pesanans')->where('nama_status', 'Selesai')->value('id');

        if ($statusId) {
            DB::table('pesanans')->where('id', $id)->update([
                'status_pesanan_id' => $statusId,
                'updated_at' => now()
            ]);
            return redirect()->back()->with('success', 'Pesanan telah selesai diproduksi! Kerja bagus.');
        }

        return redirect()->back()->with('error', 'Gagal: Status "Selesai" tidak ditemukan di database.');
    }

    // === 5. HALAMAN DAFTAR KENDALA ===
    public function issues()
    {
        $this->ensureProduksi();
        
        // Ambil data kendala
        $issues = DB::table('kendala_produksis')
            ->join('pesanans', 'kendala_produksis.pesanan_id', '=', 'pesanans.id')
            ->join('pelanggans', 'pesanans.pelanggan_id', '=', 'pelanggans.id')
            ->select(
                'kendala_produksis.*',
                'pesanans.id as order_id',
                'pelanggans.nama as nama_pelanggan'
            )
            ->orderBy('kendala_produksis.created_at', 'desc')
            ->paginate(10);

        return view('produksi.issues', compact('issues'));
    }

    // === 6. AKSI: SIMPAN LAPORAN KENDALA (DARI MODAL) ===
    public function storeIssue(Request $request)
    {
        $this->ensureProduksi();

        $request->validate([
            'pesanan_id' => 'required|exists:pesanans,id',
            'deskripsi' => 'required|string',
        ]);

        DB::table('kendala_produksis')->insert([
            'pesanan_id' => $request->pesanan_id,
            'deskripsi' => $request->deskripsi,
            'waktu_terjadi' => now(), // Pastikan kolom ini ada di DB atau sesuaikan
            'produksi_id' => 1, // PERHATIAN: Sesuaikan logika ini. Biasanya perlu cari ID produksi aktif dari pesanan tsb.
            // Jika tabel kendala butuh user penanggung jawab dan kolomnya ada:
            // 'user_id' => Auth::id(), 
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Laporan kendala berhasil dikirim ke manajemen.');
    }

    // === 7. CETAK JOB SHEET (SPK) ===
    public function printJobSheet($id)
    {
        $this->ensureProduksi();

        // 1. Ambil data pesanan lengkap
        $item = DB::table('pesanans')
            ->join('pelanggans', 'pesanans.pelanggan_id', '=', 'pelanggans.id')
            ->join('status_pesanans', 'pesanans.status_pesanan_id', '=', 'status_pesanans.id')
            ->leftJoin('desains', 'pesanans.id', '=', 'desains.pesanan_id')
            ->select(
                'pesanans.id',
                'pesanans.tanggal_pesanan',
                'pesanans.catatan',
                'pelanggans.nama as nama_pelanggan',
                'pelanggans.no_hp',
                'pelanggans.alamat', 
                'status_pesanans.nama_status as status_produksi',
                'desains.file_desain_path'
            )
            ->where('pesanans.id', $id)
            ->first();

        if (!$item) {
            return redirect()->back()->with('error', 'Data pesanan tidak ditemukan.');
        }

        // 2. Inject detail barang menggunakan helper yang sama
        $this->injectOrderDetails($item);

        // 3. Tampilkan View Khusus Print
        return view('produksi.print_job_sheet', compact('item'));
    }
}