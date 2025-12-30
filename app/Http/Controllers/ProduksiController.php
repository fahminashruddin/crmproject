<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

// --- IMPORT MODEL BARU ---
use App\Models\Produksi;
use App\Models\KendalaProduksi;
use App\Models\Pesanan; // Pastikan Anda sudah membuat Model Pesanan

class ProduksiController extends Controller
{
    // === MIDDLEWARE ===
    protected function ensureProduksi()
    {
        $roleId = DB::table('roles')->whereRaw('LOWER(nama_role) = ?', ['produksi'])->value('id');
        if (Auth::check() && $roleId && Auth::user()->role_id != $roleId) {
             abort(403, 'Unauthorized. Hanya tim Produksi yang boleh masuk sini.');
        }
    }

    // === 1. DASHBOARD UTAMA ===
    public function dashboard()
    {
        $this->ensureProduksi();

        // A. Hitung Statistik (Tetap menggunakan DB Query agar cepat)
        $stats = DB::table('pesanans')
            ->join('status_pesanans', 'pesanans.status_pesanan_id', '=', 'status_pesanans.id')
            ->select('status_pesanans.nama_status', DB::raw('count(*) as total'))
            ->groupBy('status_pesanans.nama_status')
            ->pluck('total', 'nama_status')
            ->toArray();

        $menunggu = ($stats['Pending'] ?? 0) + ($stats['Menunggu'] ?? 0) + ($stats['Desain Disetujui'] ?? 0); 
        $sedangProses = ($stats['Diproses'] ?? 0) + ($stats['Produksi'] ?? 0) + ($stats['Sedang Diproduksi'] ?? 0);
        $selesai = ($stats['Selesai'] ?? 0);

        // B. Ambil 5 Antrian Teratas
        $antrian = DB::table('pesanans')
            ->join('pelanggans', 'pesanans.pelanggan_id', '=', 'pelanggans.id')
            ->join('status_pesanans', 'pesanans.status_pesanan_id', '=', 'status_pesanans.id')
            ->select(
                'pesanans.id',
                'pesanans.tanggal_pesanan',
                'pelanggans.nama as nama_pelanggan',
                'status_pesanans.nama_status as status_produksi'
            )
            ->whereIn('status_pesanans.nama_status', ['Pending', 'Diproses', 'Produksi', 'Desain Disetujui', 'Menunggu'])
            ->orderBy('pesanans.updated_at', 'desc')
            ->limit(5)
            ->get();

        foreach ($antrian as $item) {
            $detail = DB::table('detail_pesanans')
                ->join('jenis_layanans', 'detail_pesanans.jenis_layanan_id', '=', 'jenis_layanans.id')
                ->where('detail_pesanans.pesanan_id', $item->id)
                ->select('detail_pesanans.jumlah', 'detail_pesanans.spesifikasi', 'jenis_layanans.nama_layanan')
                ->first();

            $item->jumlah = $detail ? $detail->jumlah : 0;
            $item->jenis_layanan = $detail ? $detail->nama_layanan : '-';
            $item->spesifikasi = $detail ? $detail->spesifikasi : null;
        }

        return view('produksi.dashboard', compact('menunggu', 'sedangProses', 'selesai', 'antrian'));
    }

    // === 2. HALAMAN DAFTAR SEMUA PRODUKSI (MERGED) ===
    public function productions(Request $request)
    {
        $this->ensureProduksi();

        // 1. Hitung Statistik
        $stats = DB::table('pesanans')
            ->join('status_pesanans', 'pesanans.status_pesanan_id', '=', 'status_pesanans.id')
            ->select('status_pesanans.nama_status', DB::raw('count(*) as total'))
            ->groupBy('status_pesanans.nama_status')
            ->pluck('total', 'nama_status')
            ->toArray();

        $menunggu = ($stats['Pending'] ?? 0) + ($stats['Menunggu'] ?? 0) + ($stats['Desain Disetujui'] ?? 0); 
        $sedangProses = ($stats['Diproses'] ?? 0) + ($stats['Produksi'] ?? 0) + ($stats['Sedang Diproduksi'] ?? 0);
        $selesai = ($stats['Selesai'] ?? 0);

        // 2. Query Utama (MENGGUNAKAN ELOQUENT SESUAI REQUEST ANDA)
        // Pastikan Model Pesanan memiliki relasi: pelanggan(), status(), desain(), detail()
        $productions = Pesanan::with(['pelanggan', 'status', 'desain', 'detail.jenisLayanan'])
            ->when($request->search, function($q) use ($request) {
                $q->whereHas('pelanggan', function($sub) use ($request) {
                    $sub->where('nama', 'like', "%{$request->search}%");
                })->orWhere('id', 'like', "%{$request->search}%");
            })
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        // 3. Inject Detail & Logika Tampilan
        // Kita sesuaikan data object Eloquent agar tetap terbaca oleh View lama Anda
        foreach ($productions as $item) {
            // Mapping Data Relasi ke properti flat (agar view tidak error)
            $item->nama_pelanggan = $item->pelanggan->nama ?? '-';
            $item->status_produksi = $item->status->nama_status ?? '-';
            $item->file_desain_path = $item->desain->file_desain_path ?? null;
            
            // Ambil detail pertama (asumsi satu pesanan satu detail utama)
            $detail = $item->detail->first(); 
            
            $item->jumlah = $detail ? $detail->jumlah : 0;
            $item->layanan = ($detail && $detail->jenisLayanan) ? $detail->jenisLayanan->nama_layanan : '-';
            $item->spesifikasi = $detail ? $detail->spesifikasi : '-';
            $item->nama_file_desain = $item->file_desain_path ? basename($item->file_desain_path) : 'Tidak ada file';

            // --- LOGIKA TAMPILAN (Label & Warna) ---
            $item->label_status = match($item->status_produksi) {
                'Diproses', 'Produksi', 'Sedang Diproduksi' => 'Sedang Diproduksi',
                'Pending', 'Menunggu', 'Desain Disetujui' => 'Menunggu Produksi',
                'Selesai' => 'Selesai',
                'Dibatalkan' => 'Dibatalkan',
                default => $item->status_produksi
            };

            $item->warna_badge = match($item->status_produksi) {
                'Selesai' => 'bg-green-600',
                'Dibatalkan' => 'bg-red-600',
                default => 'bg-black'
            };
        }

        return view('produksi.productions', compact('menunggu', 'sedangProses', 'selesai', 'productions'));
    }

    // === 3. AKSI: MULAI PRODUKSI ===
    public function startProduction($id)
    {
        $this->ensureProduksi();
        $statusId = DB::table('status_pesanans')->whereIn('nama_status', ['Diproses', 'Produksi'])->value('id');
        
        if ($statusId) {
            DB::table('pesanans')->where('id', $id)->update(['status_pesanan_id' => $statusId, 'updated_at' => now()]);
            return redirect()->back()->with('success', 'Status berhasil diubah menjadi Sedang Diproduksi.');
        }
        return redirect()->back()->with('error', 'Status "Diproses" tidak ditemukan di database.');
    }

    // === 4. AKSI: SELESAI PRODUKSI ===
    public function completeProduction($id)
    {
        $this->ensureProduksi();
        $statusId = DB::table('status_pesanans')->where('nama_status', 'Selesai')->value('id');
        
        if ($statusId) {
            DB::table('pesanans')->where('id', $id)->update(['status_pesanan_id' => $statusId, 'updated_at' => now()]);
            return redirect()->back()->with('success', 'Pesanan selesai diproduksi.');
        }
        return redirect()->back()->with('error', 'Status "Selesai" tidak ditemukan di database.');
    }

    // === 5. SIMPAN KENDALA (MERGED - MENGGUNAKAN MODEL) ===
    public function storeIssue(Request $request)
    {
        $this->ensureProduksi();

        $request->validate([
            'pesanan_id' => 'required|exists:pesanans,id',
            'deskripsi' => 'required|string',
        ]);

        // 1. Cek atau Buat data di tabel 'Produksi' (Sesuai ERD)
        // Ini otomatis mengecek: jika pesanan_id ini belum ada di tabel produksi, dia buat baru.
        // Jika sudah ada, dia ambil datanya.
        $produksi = Produksi::firstOrCreate(
            ['pesanan_id' => $request->pesanan_id],
            ['tanggal_mulai' => now()] // Data tambahan jika baru dibuat
        );

        // 2. Simpan Kendala lewat Relasi (Sesuai garis hubungan di ERD)
        // Laravel otomatis mengisi 'produksi_id' berdasarkan relasi
        $produksi->kendala()->create([
            'deskripsi_kendala' => $request->deskripsi,
            'waktu_terjadi' => now(),
        ]);

        return redirect()->back()->with('success', 'Laporan kendala berhasil dikirim.');
    }

    // === 6. PRINT JOB SHEET ===
    public function printJobSheet($id)
    {
        $this->ensureProduksi();
        $item = DB::table('pesanans')
            ->join('pelanggans', 'pesanans.pelanggan_id', '=', 'pelanggans.id')
            ->join('status_pesanans', 'pesanans.status_pesanan_id', '=', 'status_pesanans.id')
            ->leftJoin('desains', 'pesanans.id', '=', 'desains.pesanan_id')
            ->select(
                'pesanans.*', 
                'pelanggans.nama as nama_pelanggan', 
                'pelanggans.telepon', 
                'pelanggans.alamat', 
                'status_pesanans.nama_status as status_produksi', 
                'desains.file_desain_path'
            )
            ->where('pesanans.id', $id)->first();

        if (!$item) return redirect()->back()->with('error', 'Data pesanan tidak ditemukan.');

        $detail = DB::table('detail_pesanans')
            ->join('jenis_layanans', 'detail_pesanans.jenis_layanan_id', '=', 'jenis_layanans.id')
            ->where('detail_pesanans.pesanan_id', $item->id)
            ->select('detail_pesanans.jumlah', 'detail_pesanans.spesifikasi', 'jenis_layanans.nama_layanan')->first();

        $item->jumlah = $detail ? $detail->jumlah : 0;
        $item->layanan = $detail ? $detail->nama_layanan : '-';
        $item->spesifikasi = $detail ? $detail->spesifikasi : '-';
        $item->file_desain = $item->file_desain_path ? basename($item->file_desain_path) : '-';

        return view('produksi.print_job_sheet', compact('item'));
    }
    
    // === 7. HALAMAN JADWAL PRODUKSI ===
    public function jadwalProduksi(Request $request)
    {
        $this->ensureProduksi();
        
        $jadwals = DB::table('produksis')
            ->leftJoin('pesanans', 'produksis.pesanan_id', '=', 'pesanans.id')
            ->leftJoin('pelanggans', 'pesanans.pelanggan_id', '=', 'pelanggans.id')
            ->leftJoin('status_pesanans', 'pesanans.status_pesanan_id', '=', 'status_pesanans.id')
            ->select(
                'produksis.*',
                'pesanans.id as pesanan_id_val',
                'pelanggans.nama as pelanggan_nama',
                'status_pesanans.nama_status'
            )
            ->orderBy('produksis.tanggal_mulai', 'asc')
            ->paginate(10);

        return view('produksi.jadwal-produksi', compact('jadwals'));
    }

    // === 8. HALAMAN INVENTORY ===
    public function inventory(Request $request)
    {
        $this->ensureProduksi();
        
        try {
            $inventorys = DB::table('inventorys')
                ->orderBy('inventorys.created_at', 'desc')
                ->paginate(10);
        } catch (\Exception $e) {
            $inventorys = new \Illuminate\Pagination\Paginator([], 10, 1, []);
        }

        return view('produksi.inventory', compact('inventorys'));
    }
    
    // === 9. HALAMAN ISSUES ===
    public function issues() 
    { 
        $this->ensureProduksi();
        $issues = DB::table('kendala_produksis')->orderBy('created_at', 'desc')->paginate(10);
        return view('produksi.issues', compact('issues')); 
    }
}