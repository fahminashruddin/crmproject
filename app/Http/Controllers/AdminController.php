<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    // Helper: Memastikan hanya Admin yang bisa akses
    protected function ensureAdmin()
    {
        // Tips Expert: Sebaiknya logic ini dipindah ke Middleware di masa depan
        $roleId = DB::table('roles')->whereRaw('LOWER(nama_role) = ?', ['admin'])->value('id');

        if (! Auth::check() || Auth::user()->role_id != $roleId) {
            abort(403, 'Akses Ditolak: Halaman ini khusus Administrator.');
        }
    }

    public function dashboard()
    {
        $this->ensureAdmin();

        // 1. Statistik Utama
        $totalPesanan = DB::table('pesanans')->count();

        // Cari ID status 'selesai' (case insensitive)
        $selesaiStatusId = DB::table('status_pesanans')
            ->whereRaw('LOWER(nama_status) = ?', ['selesai'])
            ->value('id');

        $pesananSelesai = $selesaiStatusId
            ? DB::table('pesanans')->where('status_pesanan_id', $selesaiStatusId)->count()
            : 0;

        $pembayaranPending = DB::table('pembayarans')->where('status', 'pending')->count();

        // Asumsi status valid adalah 'verifikasi' atau 'lunas'
        $totalPendapatan = DB::table('pembayarans')
            ->whereIn('status', ['verifikasi', 'lunas'])
            ->sum('nominal');

        // 2. Pesanan Terbaru (Limit 5)
        $pesananTerbaru = $this->getOrdersQuery()
            ->orderBy('pesanans.tanggal_pesanan', 'desc')
            ->limit(5)
            ->get();

        // 3. Aktivitas User (Opsional, jika dipakai di view)
        $aktivitasUser = DB::table('penggunas')
            ->leftJoin('roles', 'penggunas.role_id', '=', 'roles.id')
            ->select('penggunas.*', 'roles.nama_role')
            ->orderBy('penggunas.name')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalPesanan',
            'pesananSelesai',
            'pembayaranPending',
            'totalPendapatan',
            'pesananTerbaru', // <--- Penting: Nama variabel disesuaikan
            'aktivitasUser'
        ));
    }

    //halaman Kelola Pesanan
    public function orders( Request $request)
    {
       $this->ensureAdmin();

       $query = $this->getOrdersQuery();
        // Logic Pencarian
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('pelanggans.nama', 'like', "%{$search}%")
                  ->orWhere('pesanans.id', 'like', "%{$search}%");
            });
        }

        // LOGIC FILTER STATUS
        if ($request->filled('status')) {
            $statusName = $request->status;
            $query->where('status_pesanans.nama_status', $statusName);
        }

        // Pagination
        $orders = $query->orderBy('pesanans.id', 'asc')->paginate(10);

        // Data Status untuk Dropdown
        $allStatuses = DB::table('status_pesanans')->get();


        $services = DB::table('jenis_layanans')->get();

        return view('admin.orders', compact('orders', 'allStatuses', 'services'));
    }

    //function untuk handle udpate status di halaman Kelola Pesanan
    public function updateOrderStatus(Request $request, $id)
    {
        $this->ensureAdmin();

        $request->validate([
            'status_id' => 'required|exists:status_pesanans,id'
        ]);

        DB::table('pesanans')
            ->where('id', $id)
            ->update([
                'status_pesanan_id' => $request->status_id,
                'updated_at' => now()
            ]);

        return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui!');
    }

    private function getOrdersQuery()
    {
        return DB::table('pesanans')
            ->leftJoin('pelanggans', 'pesanans.pelanggan_id', '=', 'pelanggans.id')
            ->leftJoin('status_pesanans', 'pesanans.status_pesanan_id', '=', 'status_pesanans.id')
            ->select([
                'pesanans.*',
                'pelanggans.nama as pelanggan_nama',
                'status_pesanans.nama_status',
                // 1. SUBQUERY: Hitung Total Item (SUM jumlah di detail_pesanans)
                'jumlah_pesanan' => DB::table('detail_pesanans')
                    ->selectRaw('COALESCE(SUM(jumlah), 0)')
                    ->whereColumn('pesanan_id', 'pesanans.id'),

                // 2. SUBQUERY: Hitung Total Harga (SUM jumlah * harga_satuan)
                'total_harga' => DB::table('detail_pesanans')
                    ->selectRaw('COALESCE(SUM(jumlah * harga_satuan), 0)')
                    ->whereColumn('pesanan_id', 'pesanans.id'),

                // 3. SUBQUERY: Ambil Nama Layanan (Ambil 1 layanan pertama sebagai perwakilan)
                'jenis_layanan' => DB::table('detail_pesanans')
                    ->join('jenis_layanans', 'detail_pesanans.jenis_layanan_id', '=', 'jenis_layanans.id')
                    ->select('jenis_layanans.nama_layanan') // Pastikan nama kolom di tabel jenis_layanans benar
                    ->whereColumn('detail_pesanans.pesanan_id', 'pesanans.id')
                    ->limit(1)
            ]);
    }

    // ---SIMPAN PESANAN ---
    public function storeOrder(Request $request)
    {
        $this->ensureAdmin();

        // 1. Validasi Input
        $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'no_telepon'     => 'required|string|max:20',
            'email'          => 'required|email|max:255',
            'jenis_layanan'  => 'required|exists:jenis_layanans,id',
            'jumlah'         => 'required|integer|min:1',
            'total_harga'    => 'required|numeric|min:0',
            'spesifikasi'    => 'required|string',
        ]);

        // Gunakan Transaction agar data konsisten (semua tersimpan atau gagal semua)
        DB::transaction(function () use ($request) {

            // A. Simpan/Cari Pelanggan
            // Kita pakai updateOrCreate: jika email sama, update datanya. Jika belum ada, buat baru.
            $pelangganId = DB::table('pelanggans')->updateOrInsert(
                ['email' => $request->email],
                [
                    'nama' => $request->nama_pelanggan,
                    'email' => $request->email,
                    'telepon' => $request->no_telepon,
                    'alamat' => '-', //di form tidak ada, tapi di database ada
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );

            // Karena updateOrInsert tidak mengembalikan ID, kita ambil ID manual
            $pelanggan = DB::table('pelanggans')->where('email', $request->email)->first();

            // Ambil ID status "Pending"
            $statusPending = DB::table('status_pesanans')
                ->whereRaw('LOWER(nama_status) = ?', ['pending'])
                ->value('id') ?? 1;

            // B. Simpan Pesanan (Header)
            $pesananId = DB::table('pesanans')->insertGetId([
                'tanggal_pesanan'   => now(),
                'pelanggan_id'      => $pelanggan->id,
                'pengguna_id'       => Auth::id(), // Admin yang input
                'status_pesanan_id' => $statusPending,
                'catatan'           => $request->spesifikasi,
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);

            // C. Simpan Detail Pesanan (Item)
            // Hitung harga satuan otomatis: Total / Jumlah
            $hargaSatuan = $request->total_harga / $request->jumlah;

            DB::table('detail_pesanans')->insert([
                'pesanan_id'       => $pesananId,
                'jenis_layanan_id' => $request->jenis_layanan,
                'spesifikasi'      => $request->spesifikasi,
                'jumlah'           => $request->jumlah,
                'harga_satuan'     => $hargaSatuan,
                'created_at'       => now(),
                'updated_at'       => now(),
            ]);
        });

        return redirect()->back()->with('success', 'Pesanan baru berhasil ditambahkan!');
    }

    public function payments()
    {
        $this->ensureAdmin();

        $payments = DB::table('pembayarans')
            ->leftJoin('pesanans', 'pembayarans.pesanan_id', '=', 'pesanans.id')
            ->select('pembayarans.*', 'pesanans.id as pesanan_id', 'pesanans.pelanggan_id')
            ->orderBy('pembayarans.created_at', 'desc')
            ->paginate(10); // Gunakan pagination juga disini

        return view('admin.payments', compact('payments'));
    }

    public function users()
    {
        $this->ensureAdmin();

        $users = DB::table('penggunas')
            ->leftJoin('roles', 'penggunas.role_id', '=', 'roles.id')
            ->select('penggunas.*', 'roles.nama_role')
            ->orderBy('penggunas.name')
            ->get(); // User biasanya tidak sebanyak pesanan, get() masih aman untuk skala kecil

        return view('admin.users', compact('users'));
    }

    public function settings()
    {
        $this->ensureAdmin();
        return view('admin.settings');
    }

    public function notifications()
    {
        $this->ensureAdmin();
        return view('admin.notifications');
    }
}
