<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // Hanya dipakai untuk Transaction
use App\Models\Pesanan;
use App\Models\Pelanggan;
use App\Models\DetailPesanan;
use App\Models\StatusPesanan;
use App\Models\JenisLayanan;

class PesananController extends Controller
{

    // 1. Method: +updateStatusPesanan
    public function updateStatusPesanan(Request $request, $id)
    {
        $request->validate([
            'status_id' => 'required|exists:status_pesanans,id'
        ]);

        // Cari Pesanan
        $pesanan = Pesanan::findOrFail($id);

        // Update Status
        $pesanan->update([
            'status_pesanan_id' => $request->status_id,
        ]);

        // Cek nama status via relasi untuk logic Bonobo
        // Kita refresh() untuk memastikan data status terbaru terambil
        $namaStatus = $pesanan->refresh()->statusPesanan->nama_status;

        if (strtolower($namaStatus) == 'selesai') {
            $this->syncToBonobo($id);
        }

        return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui!');
    }

    // 2. Method: +calculateTotal
    // Menghitung total harga dari koleksi detail pesanan
    public function calculateTotal($pesananId)
    {
        $pesanan = Pesanan::with('detailPesanans')->find($pesananId);

        if (!$pesanan) return 0;

        // Hitung manual collection: sum(jumlah * harga)
        $total = $pesanan->detailPesanans->sum(function ($detail) {
            return $detail->jumlah * $detail->harga_satuan;
        });

        // Opsi: Jika ingin menyimpan total ke tabel database
        // $pesanan->update(['total_bayar' => $total]);

        return $total;
    }

    // 3. Method: +addDetailItem
    public function addDetailItem(Request $request, $pesananId)
    {
        // Validasi input...
        // Pesanan::findOrFail($pesananId)->detailPesanans()->create([...]);

        // Hitung ulang
        $this->calculateTotal($pesananId);

        return back()->with('success', 'Item berhasil ditambahkan');
    }

    // 4. Method: +syncToBonobo
    public function syncToBonobo($pesananId)
    {
        $pesanan = Pesanan::find($pesananId);

        if ($pesanan) {
            // Asumsi kolom is_synced_bonobo ada di tabel pesanans
            // Jika belum ada, pastikan ditambahkan di migration atau hapus bagian ini
            $pesanan->forceFill([
               'is_synced_bonobo' => true,
               'synced_at' => now()
            ])->save();
        }

        return true;
    }

    // MENAMPILKAN HALAMAN ORDERS (INDEX)
    public function index(Request $request)
    {
        // 1. Query Builder pakai Model & Eager Loading
        // 'with' mengambil data relasi agar tidak N+1 Query
        $query = Pesanan::with(['pelanggan', 'statusPesanan', 'detailPesanans.jenisLayanan']);

        // 2. Logic Pencarian
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                // Cari berdasarkan Nama Pelanggan (via relasi)
                $q->whereHas('pelanggan', function($subQ) use ($search) {
                    $subQ->where('nama', 'like', "%{$search}%");
                })
                // Atau cari ID Pesanan
                ->orWhere('id', 'like', "%{$search}%");
            });
        }

        // 3. Logic Filter Status (Via Relasi)
        if ($request->filled('status')) {
            $query->whereHas('statusPesanan', function($q) use ($request) {
                $q->where('nama_status', $request->status);
            });
        }

        // 4. Pagination
        $orders = $query->orderBy('id', 'asc')->paginate(10);

        // Ambil data untuk dropdown filter
        $allStatuses = StatusPesanan::all();
        $services = JenisLayanan::all();

        return view('admin.orders', compact('orders', 'allStatuses', 'services'));
    }

    // MENYIMPAN PESANAN BARU (STORE)
    public function store(Request $request)
    {
        $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'no_telepon'     => 'required|string|max:20',
            'email'          => 'required|email|max:255',
            'jenis_layanan'  => 'required|exists:jenis_layanans,id',
            'jumlah'         => 'required|integer|min:1',
            'total_harga'    => 'required|numeric|min:0',
            'spesifikasi'    => 'required|string',
        ]);

        // Gunakan Transaction agar data atomik (semua tersimpan atau gagal semua)
        DB::transaction(function () use ($request) {

            // A. Handle Pelanggan (Update jika ada, Create jika baru)
            $pelanggan = Pelanggan::updateOrCreate(
                ['email' => $request->email], // Kunci pencarian
                [
                    'nama' => $request->nama_pelanggan,
                    'telepon' => $request->no_telepon,
                    'alamat' => $request->alamat ?? '-', // Default strip jika kosong
                ]
            );

            // B. Cari Status Default 'pending'
            $statusPending = StatusPesanan::where('nama_status', 'like', 'pending')->first();
            $statusId = $statusPending ? $statusPending->id : 1;

            // C. Create Pesanan Header
            $pesanan = Pesanan::create([
                'tanggal_pesanan'   => now(),
                'pelanggan_id'      => $pelanggan->id,
                'pengguna_id'       => Auth::id(),
                'status_pesanan_id' => $statusId,
                'catatan'           => $request->spesifikasi,
            ]);

            // D. Create Detail Item
            $hargaSatuan = $request->total_harga / $request->jumlah;

            // Menggunakan relasi hasMany untuk create detail
            $pesanan->detailPesanans()->create([
                'jenis_layanan_id' => $request->jenis_layanan,
                'spesifikasi'      => $request->spesifikasi,
                'jumlah'           => $request->jumlah,
                'harga_satuan'     => $hargaSatuan,
            ]);

            // (Opsional) Hitung total jika mau disimpan di log atau field lain
            // $this->calculateTotal($pesanan->id);
        });

        return redirect()->back()->with('success', 'Pesanan baru berhasil ditambahkan!');
    }
}
