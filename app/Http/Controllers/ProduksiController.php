<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ProduksiController extends Controller
{
    // Middleware manual: Pastikan hanya role 'produksi' yang masuk
    protected function ensureProduksi()
    {
        $roleId = DB::table('roles')->whereRaw('LOWER(nama_role) = ?', ['produksi'])->value('id');
        
        if (! Auth::check() || Auth::user()->role_id != $roleId) {
            abort(403, 'Unauthorized. Hanya tim Produksi yang boleh masuk sini.');
        }
    }

    // 1. Halaman Dashboard Utama
    public function dashboard()
    {
        $this->ensureProduksi();

        // Ambil Statistik
        $stats = DB::table('pesanans')
            ->join('status_pesanans', 'pesanans.status_pesanan_id', '=', 'status_pesanans.id')
            ->select('status_pesanans.nama_status', DB::raw('count(*) as total'))
            ->groupBy('status_pesanans.nama_status')
            ->pluck('total', 'nama_status')
            ->toArray();

        $menunggu = ($stats['Desain Disetujui'] ?? 0) + ($stats['Menunggu'] ?? 0); 
        $sedangProses = $stats['Produksi'] ?? 0;
        $selesai = $stats['Selesai'] ?? 0;

        // Ambil Antrian (Limit 5)
        $antrian = DB::table('pesanans')
            ->join('pelanggans', 'pesanans.pelanggan_id', '=', 'pelanggans.id')
            ->join('status_pesanans', 'pesanans.status_pesanan_id', '=', 'status_pesanans.id')
            ->select('pesanans.id', 'pesanans.tanggal_pesanan as created_at', 'pesanans.catatan', 'pelanggans.nama as nama_pelanggan', 'status_pesanans.nama_status as status_produksi')
            ->whereIn('status_pesanans.nama_status', ['Desain Disetujui', 'Menunggu', 'Produksi', 'Selesai'])
            ->orderBy('pesanans.tanggal_pesanan', 'desc')
            ->limit(5)
            ->get();

        foreach ($antrian as $item) {
            $detail = DB::table('detail_pesanans')
                ->join('jenis_layanans', 'detail_pesanans.jenis_layanan_id', '=', 'jenis_layanans.id')
                ->where('detail_pesanans.pesanan_id', $item->id)
                ->select(DB::raw('SUM(detail_pesanans.jumlah) as total_qty'), 'jenis_layanans.nama_layanan')
                ->groupBy('jenis_layanans.nama_layanan')
                ->first();

            $item->jumlah = $detail ? $detail->total_qty : 0;
            $item->jenis_layanan = $detail ? $detail->nama_layanan : '-';
        }

        return view('produksi.dashboard', compact('menunggu', 'sedangProses', 'selesai', 'antrian'));
    }

    // 2. Halaman List Produksi (Antrian Lengkap)
    public function productions(Request $request)
    {
        $this->ensureProduksi();

        $query = DB::table('pesanans')
            ->join('pelanggans', 'pesanans.pelanggan_id', '=', 'pelanggans.id')
            ->join('status_pesanans', 'pesanans.status_pesanan_id', '=', 'status_pesanans.id')
            ->select(
                'pesanans.id',
                'pesanans.tanggal_pesanan',
                'pesanans.catatan',
                'pelanggans.nama as nama_pelanggan',
                'status_pesanans.nama_status as status_produksi'
            )
            ->whereIn('status_pesanans.nama_status', ['Desain Disetujui', 'Menunggu', 'Produksi', 'Selesai']);

        // Filter Pencarian
        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) use ($request) {
                $q->where('pelanggans.nama', 'like', "%{$request->search}%")
                  ->orWhere('pesanans.id', 'like', "%{$request->search}%");
            });
        }

        $productions = $query->orderBy('pesanans.tanggal_pesanan', 'desc')->paginate(10);

        foreach ($productions as $item) {
            $detail = DB::table('detail_pesanans')
                ->join('jenis_layanans', 'detail_pesanans.jenis_layanan_id', '=', 'jenis_layanans.id')
                ->where('detail_pesanans.pesanan_id', $item->id)
                ->select(DB::raw('SUM(detail_pesanans.jumlah) as total_qty'), 'jenis_layanans.nama_layanan')
                ->groupBy('jenis_layanans.nama_layanan')
                ->first();

            $item->jumlah = $detail ? $detail->total_qty : 0;
            $item->jenis_layanan = $detail ? $detail->nama_layanan : '-';
            $item->file_desain = 'file_desain_' . $item->id . '.pdf'; 
        }

        return view('produksi.productions', compact('productions'));
    }

    // 3. AKSI: Mulai Produksi
    public function startProduction($id)
    {
        $this->ensureProduksi();

        $statusId = DB::table('status_pesanans')->where('nama_status', 'Produksi')->value('id');

        if ($statusId) {
            DB::table('pesanans')->where('id', $id)->update([
                'status_pesanan_id' => $statusId,
                'updated_at' => now()
            ]);
        }

        return redirect()->back()->with('success', 'Status berhasil diubah menjadi Sedang Diproduksi.');
    }

    // 4. AKSI: Selesai Produksi
    public function completeProduction($id)
    {
        $this->ensureProduksi();

        $statusId = DB::table('status_pesanans')->where('nama_status', 'Selesai')->value('id');

        if ($statusId) {
            DB::table('pesanans')->where('id', $id)->update([
                'status_pesanan_id' => $statusId,
                'updated_at' => now()
            ]);
        }

        return redirect()->back()->with('success', 'Pesanan telah selesai diproduksi!');
    }

    // 5. Halaman Kendala
    public function issues()
    {
        $this->ensureProduksi();
        return view('produksi.issues');
    }
}