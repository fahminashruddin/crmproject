<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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

    // === 1. DASHBOARD UTAMA (Full Statistik & Antrian) ===
    public function dashboard()
    {
        $this->ensureProduksi();

        // A. Hitung Statistik
        $stats = DB::table('pesanans')
            ->join('status_pesanans', 'pesanans.status_pesanan_id', '=', 'status_pesanans.id')
            ->select('status_pesanans.nama_status', DB::raw('count(*) as total'))
            ->groupBy('status_pesanans.nama_status')
            ->pluck('total', 'nama_status')
            ->toArray();

        // Mapping Status
        $menunggu = ($stats['Pending'] ?? 0) + ($stats['Menunggu'] ?? 0) + ($stats['Desain Disetujui'] ?? 0); 
        $sedangProses = ($stats['Diproses'] ?? 0) + ($stats['Produksi'] ?? 0) + ($stats['Sedang Diproduksi'] ?? 0);
        $selesai = ($stats['Selesai'] ?? 0);

        // B. Ambil 5 Antrian Teratas (Untuk Widget Dashboard)
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

        // Inject detail (Layanan/Spesifikasi)
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

    // === 2. HALAMAN DAFTAR SEMUA PRODUKSI (Clean UI) ===
  // === 2. HALAMAN DAFTAR SEMUA PRODUKSI ===
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

        // 2. Query Utama
        $query = DB::table('pesanans')
            ->join('pelanggans', 'pesanans.pelanggan_id', '=', 'pelanggans.id')
            ->join('status_pesanans', 'pesanans.status_pesanan_id', '=', 'status_pesanans.id')
            ->leftJoin('desains', 'pesanans.id', '=', 'desains.pesanan_id') 
            ->select(
                'pesanans.id',
                'pesanans.tanggal_pesanan',
                'pelanggans.nama as nama_pelanggan',
                'status_pesanans.nama_status as status_produksi',
                'desains.file_desain_path'
            );

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('pelanggans.nama', 'like', "%{$search}%")
                  ->orWhere('pesanans.id', 'like', "%{$search}%");
            });
        }

        $productions = $query->orderBy('pesanans.updated_at', 'desc')->paginate(10);

        // 3. Inject Detail & Logika Tampilan (PINDAH KE SINI AGAR BLADE BERSIH)
        foreach ($productions as $item) {
            $detail = DB::table('detail_pesanans')
                ->join('jenis_layanans', 'detail_pesanans.jenis_layanan_id', '=', 'jenis_layanans.id')
                ->where('detail_pesanans.pesanan_id', $item->id)
                ->select('detail_pesanans.jumlah', 'detail_pesanans.spesifikasi', 'jenis_layanans.nama_layanan')
                ->first();

            $item->jumlah = $detail ? $detail->jumlah : 0;
            $item->layanan = $detail ? $detail->nama_layanan : '-';
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

    // === 5. SIMPAN KENDALA (Fix Produksi ID) ===
    public function storeIssue(Request $request)
    {
        $this->ensureProduksi();

        $request->validate([
            'pesanan_id' => 'required|exists:pesanans,id',
            'deskripsi' => 'required|string',
        ]);

        // Cari ID Produksi
        $produksi = DB::table('produksis')->where('pesanan_id', $request->pesanan_id)->first();

        if (!$produksi) {
            $produksiId = DB::table('produksis')->insertGetId([
                'pesanan_id' => $request->pesanan_id,
                'tanggal_mulai' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $produksiId = $produksi->id;
        }

        DB::table('kendala_produksis')->insert([
            'produksi_id' => $produksiId, 
            'deskripsi_kendala' => $request->deskripsi, // Sesuai DB
            'waktu_terjadi' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Laporan kendala berhasil dikirim.');
    }

    // === 6. PRINT JOB SHEET (Fix Telepon) ===
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
                'pelanggans.telepon', // Menggunakan kolom 'telepon' sesuai DB
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
    
    // === 7. HALAMAN JADWAL PRODUKSI (VIEW ONLY) ===
    public function jadwalProduksi(Request $request)
    {
        $this->ensureProduksi();
        
        // Ambil data jadwal produksi dari tabel produksis
        $jadwals = DB::table('produksis')
            ->leftJoin('pesanans', 'produksis.pesanan_id', '=', 'pesanans.id')
            ->leftJoin('pelanggans', 'pesanans.pelanggan_id', '=', 'pelanggans.id')
            ->select(
                'produksis.*',
                'pesanans.id as pesanan_id_val',
                'pelanggans.nama as pelanggan_nama'
            )
            ->orderBy('produksis.tanggal_mulai', 'asc')
            ->paginate(10);

        return view('produksi.jadwal-produksi', compact('jadwals'));
    }

    // === 8. HALAMAN INVENTORY (VIEW ONLY) ===
    public function inventory(Request $request)
    {
        $this->ensureProduksi();
        
        try {
            // Ambil data inventory/stok dari database
            $inventorys = DB::table('inventorys')
                ->orderBy('inventorys.created_at', 'desc')
                ->paginate(10);
        } catch (\Exception $e) {
            // Fallback jika tabel belum ada
            $inventorys = collect([])->paginate(10);
        }

        return view('produksi.inventory', compact('inventorys'));
    }
    
    // === 9. HALAMAN ISSUES ===
    public function issues() 
    { 
        $this->ensureProduksi();
        // Placeholder query, sesuaikan jika ingin menampilkan list kendala
        $issues = DB::table('kendala_produksis')->orderBy('created_at', 'desc')->paginate(10);
        return view('produksi.issues', compact('issues')); 
    }
}