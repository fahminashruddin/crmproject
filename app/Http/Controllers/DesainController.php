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
        return $this->kelolaDesain();
    }

    /**
     * Dashboard Desainer
     * (Menggunakan data dummy agar tidak error)
     */
    public function dashboard()
    {
        $antrian = [
            (object)[
                'id' => 1,
                'nama_pelanggan' => 'Budi Santoso',
                'status' => 'Menunggu',
                'status_desain' => 'Menunggu Desain',
                'deadline' => '2025-12-01',
                'created_at' => '2025-11-25 10:30:00'
            ],
            (object)[
                'id' => 2,
                'nama_pelanggan' => 'Siti Aminah',
                'status' => 'Proses',
                'status_desain' => 'Perlu Revisi',
                'deadline' => '2025-12-02',
                'created_at' => '2025-11-26 14:00:00'
            ],
            (object)[
                'id' => 3,
                'nama_pelanggan' => 'Joko Pratama',
                'status' => 'Review',
                'status_desain' => 'Menunggu Persetujuan',
                'deadline' => '2025-12-05',
                'created_at' => '2025-11-27 09:15:00'
            ],
        ];

        return view('desain.dashboard', compact('antrian'));
    }

    /**
     * Halaman utama kelola desain
     * (Data dummy untuk tabel)
     */
    public function kelolaDesain()
    {
        $designs = [
            (object)[
                'id_pesanan' => 'ORD-101',
                'pelanggan' => 'PT. Sinar Abadi',
                'produk' => 'Spanduk 3x1m (Vinyl)',
                'tgl_dipesan' => '2025-11-20',
                'prioritas' => 'Tinggi',
                'status_desain' => 'Perlu Revisi',
            ],
            (object)[
                'id_pesanan' => 'ORD-102',
                'pelanggan' => 'Jaya Makmur Corp',
                'produk' => 'Brosur A5 (Art Paper 150)',
                'tgl_dipesan' => '2025-11-25',
                'prioritas' => 'Normal',
                'status_desain' => 'Menunggu Desain',
            ],
            (object)[
                'id_pesanan' => 'ORD-103',
                'pelanggan' => 'Digital Kreasindo',
                'produk' => 'Kartu Nama (Doft)',
                'tgl_dipesan' => '2025-11-26',
                'prioritas' => 'Normal',
                'status_desain' => 'Menunggu Persetujuan',
            ],
            (object)[
                'id_pesanan' => 'ORD-104',
                'pelanggan' => 'Solusi Cetak Cepat',
                'produk' => 'Flyer Promosi',
                'tgl_dipesan' => '2025-11-27',
                'prioritas' => 'Rendah',
                'status_desain' => 'Disetujui',
            ],
            (object)[
                'id_pesanan' => 'ORD-105',
                'pelanggan' => 'Mandiri Printing',
                'produk' => 'Roll Banner 80x200',
                'tgl_dipesan' => '2025-11-18',
                'prioritas' => 'Tinggi',
                'status_desain' => 'Menunggu Desain',
            ],
        ];

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

}
