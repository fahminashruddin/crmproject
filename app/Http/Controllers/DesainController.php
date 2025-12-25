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
     * Halaman Jadwal Produksi
     */
    public function jadwalProduksi(Request $request)
    {
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

        // Ambil data pesanan untuk dropdown
        $pesanans = DB::table('pesanans')
            ->leftJoin('pelanggans', 'pesanans.pelanggan_id', '=', 'pelanggans.id')
            ->select('pesanans.id', 'pelanggans.nama as pelanggan_nama')
            ->get();

        return view('desain.jadwal-produksi', compact('jadwals', 'pesanans'));
    }

    /**
     * Store Jadwal Produksi
     */
    public function storeJadwalProduksi(Request $request)
    {
        $validated = $request->validate([
            'pesanan_id' => 'required|exists:pesanans,id',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'status_produksi' => 'required|in:pending,berjalan,selesai,tertunda',
            'catatan' => 'nullable|string',
        ]);

        DB::table('produksis')->insert([
            'pesanan_id' => $validated['pesanan_id'],
            'tanggal_mulai' => $validated['tanggal_mulai'],
            'tanggal_selesai' => $validated['tanggal_selesai'],
            'status_produksi' => $validated['status_produksi'],
            'catatan' => $validated['catatan'] ?? null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('desain.jadwal-produksi')->with('success', 'Jadwal produksi berhasil ditambahkan');
    }

    /**
     * Halaman Inventory
     */
    public function inventory(Request $request)
    {
        try {
            // Ambil data inventory/stok dari database
            $inventorys = DB::table('inventorys')
                ->leftJoin('produksis', 'inventorys.produksi_id', '=', 'produksis.id')
                ->select(
                    'inventorys.*',
                    'produksis.id as produksi_id_join'
                )
                ->orderBy('inventorys.created_at', 'desc')
                ->paginate(10);
        } catch (\Exception $e) {
            // Fallback jika tabel belum ada
            $inventorys = collect([])->paginate(10);
        }

        return view('desain.inventory', compact('inventorys'));
    }

    /**
     * Store Inventory
     */
    public function storeInventory(Request $request)
    {
        $validated = $request->validate([
            'nama_produk' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:0',
            'satuan' => 'required|string|max:50',
            'lokasi' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        try {
            DB::table('inventorys')->insert([
                'nama_produk' => $validated['nama_produk'],
                'jumlah' => $validated['jumlah'],
                'satuan' => $validated['satuan'],
                'lokasi' => $validated['lokasi'] ?? null,
                'keterangan' => $validated['keterangan'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return redirect()->route('desain.inventory')->with('success', 'Data inventory berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menambahkan data inventory: ' . $e->getMessage());
        }
    }

    /**
     * Update Inventory
     */
    public function updateInventory(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_produk' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:0',
            'satuan' => 'required|string|max:50',
            'lokasi' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        try {
            DB::table('inventorys')->where('id', $id)->update([
                'nama_produk' => $validated['nama_produk'],
                'jumlah' => $validated['jumlah'],
                'satuan' => $validated['satuan'],
                'lokasi' => $validated['lokasi'] ?? null,
                'keterangan' => $validated['keterangan'] ?? null,
                'updated_at' => now(),
            ]);

            return redirect()->route('desain.inventory')->with('success', 'Data inventory berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui data inventory: ' . $e->getMessage());
        }
    }

    /**
     * Delete Inventory
     */
    public function deleteInventory($id)
    {
        try {
            DB::table('inventorys')->where('id', $id)->delete();
            return redirect()->route('desain.inventory')->with('success', 'Data inventory berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus data inventory: ' . $e->getMessage());
        }
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

