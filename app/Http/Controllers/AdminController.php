<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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

    // --- FITUR TAMBAH USER ---
    public function storeUser(Request $request)
    {
        $this->ensureAdmin();

        // 1. Validasi
        $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'required|string|max:50|unique:penggunas,username', // Validasi Username
            'email'    => 'required|email|unique:penggunas,email',
            'password' => 'required|string|min:6',
            'role_id'  => 'required|exists:roles,id',
        ]);

        // 2. Insert ke Database
        DB::table('penggunas')->insert([
            'name'       => $request->name,
            'username'   => $request->username, // Simpan Username
            'email'      => $request->email,
            'password'   => Hash::make($request->password),
            'role_id'    => $request->role_id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'User baru berhasil ditambahkan!');
    }

    // --- FITUR HAPUS USER ---
    public function destroyUser($id)
    {
        $this->ensureAdmin();

        if ($id == Auth::id()) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun sendiri!');
        }

        DB::table('penggunas')->where('id', $id)->delete();

        return redirect()->back()->with('success', 'User berhasil dihapus.');
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
        // Menggunakan base query builder
        $baseQuery = DB::table('pesanans')
            ->leftJoin('pelanggans', 'pesanans.pelanggan_id', '=', 'pelanggans.id')
            ->leftJoin('status_pesanans', 'pesanans.status_pesanan_id', '=', 'status_pesanans.id')
            ->select('pesanans.*', 'pelanggans.nama as pelanggan_nama', 'status_pesanans.nama_status');

        // 1. SUBQUERY: Hitung Total Item (Jumlah)
        $baseQuery->addSelect([
            'jumlah_pesanan' => DB::table('detail_pesanans')
                ->selectRaw('COALESCE(SUM(jumlah), 0)')
                ->whereColumn('pesanan_id', 'pesanans.id')
        ]);

        // 2. SUBQUERY: Hitung Total Harga
        $baseQuery->addSelect([
            'total_harga' => DB::table('detail_pesanans')
                // Gunakan COALESCE untuk memastikan nilai 0 jika tidak ada detail
                ->selectRaw('COALESCE(SUM(jumlah * harga_satuan), 0)')
                ->whereColumn('pesanan_id', 'pesanans.id')
        ]);

        // 3. SUBQUERY: Ambil Nama Layanan (Ambil 1 layanan perwakilan)
        $baseQuery->addSelect([
            'jenis_layanan' => DB::table('detail_pesanans')
                ->join('jenis_layanans', 'detail_pesanans.jenis_layanan_id', '=', 'jenis_layanans.id')
                ->select('jenis_layanans.nama_layanan')
                ->whereColumn('detail_pesanans.pesanan_id', 'pesanans.id')
                ->limit(1) // Ambil yang pertama sebagai perwakilan
        ]);

        return $baseQuery;
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

        // 1. QUERY DATA PEMBAYARAN (LENGKAP)
        $paymentsData = DB::table('pembayarans')
            ->leftJoin('pesanans', 'pembayarans.pesanan_id', '=', 'pesanans.id')
            ->leftJoin('pelanggans', 'pesanans.pelanggan_id', '=', 'pelanggans.id')
            // TAMBAHAN: Join ke metode pembayaran untuk mengambil namanya
            ->leftJoin('metode_pembayarans', 'pembayarans.metode_pembayaran_id', '=', 'metode_pembayarans.id')
            ->select([
                'pembayarans.*',
                'pesanans.id as order_id',
                'pelanggans.nama as customer_name',
                'metode_pembayarans.nama_metode' // Ambil nama metode
            ])
            ->orderBy('pembayarans.created_at', 'desc')
            ->get();

        // 2. FILTER DATA UNTUK KARTU ATAS (STATS)
        $pendingPayments = $paymentsData->where('status', 'pending');
        $verifiedPayments = $paymentsData->where('status', 'verifikasi'); // Sesuaikan string di DB ('verifikasi' atau 'verified')

        $totalRevenue = $verifiedPayments->sum('nominal');

        // Data untuk dropdown di modal/form (tetap string array atau ambil dari DB)
        $paymentMethods = DB::table('metode_pembayarans')->pluck('nama_metode');

        // 3. KIRIM KE VIEW
        // Kita kirim $paymentsData sebagai $allPayments untuk riwayat
        return view('admin.payments', compact(
            'pendingPayments',
            'verifiedPayments',
            'totalRevenue',
            'paymentMethods',
            'paymentsData' // <--- INI KUNCINYA (Semua Data)
        ));
    }
    public function verifyPayment(Request $request, $id)
    {
        $this->ensureAdmin();

        // 1. Ambil nama metode yang dipilih dari form
        $namaMetode = $request->input('payment_method');

        // 2. Cari ID metode tersebut di tabel 'metode_pembayarans'
        $metode = DB::table('metode_pembayarans')
            ->where('nama_metode', $namaMetode)
            ->first();

        // Validasi: Jika metode tidak ditemukan di database
        if (!$metode) {
            return redirect()->back()->withErrors(['error' => 'Metode pembayaran tidak valid.']);
        }

        // 3. Update tabel pembayarans dengan ID yang benar
        DB::table('pembayarans')
            ->where('id', $id)
            ->update([
                'status' => 'verifikasi', // Gunakan 'verifikasi' sesuai data seeder Anda
                'metode_pembayaran_id' => $metode->id, // <--- INI PERBAIKAN UTAMANYA (Pakai ID, bukan String)
                'updated_at' => now()
            ]);

        // Opsional: Jika pembayaran terverifikasi, update status pesanan juga
        $pembayaran = DB::table('pembayarans')->where('id', $id)->first();
        if ($pembayaran) {
             // Cari ID status 'Dikonfirmasi' atau 'Proses'
             $statusConfirmId = DB::table('status_pesanans')->whereRaw('LOWER(nama_status) like ?', ['%konfirmasi%'])->value('id');

             if ($statusConfirmId) {
                 DB::table('pesanans')
                    ->where('id', $pembayaran->pesanan_id)
                    ->update(['status_pesanan_id' => $statusConfirmId]);
             }
        }

        return redirect()->route('admin.payments')->with('success', 'Pembayaran berhasil diverifikasi.');
    }

    public function rejectPayment($id)
    {
        $this->ensureAdmin();

        DB::table('pembayarans')->where('id', $id)->update([
            'status' => 'failed',
        ]);

        return redirect()->route('admin.payments')->with('error', 'Pembayaran ditolak.');
    }

    public function users()
    {
        $this->ensureAdmin();

        $users = DB::table('penggunas')
            ->leftJoin('roles', 'penggunas.role_id', '=', 'roles.id')
            ->select('penggunas.*', 'roles.nama_role')
            ->orderBy('penggunas.id', 'asc')
            ->get();

            // 2. Ambil data Role untuk Dropdown
        $roles = DB::table('roles')->get();

        return view('admin.users', compact('users', 'roles'));
    }

    public function settings()
    {
        $this->ensureAdmin();

        // Jika nanti ada data pengaturan dari database, ambil di sini.
        // Contoh: $settings = DB::table('settings')->first();

        return view('admin.settings');
    }

    public function notifications()
    {
        $this->ensureAdmin();

        // Data Dummy Notifikasi (Sesuai gambar)
        // Nanti bisa diganti dengan database query: DB::table('notifications')->get();
        $notifications = [
            (object)[
                'id' => 1,
                'type' => 'order', // Tipe untuk menentukan icon/warna
                'title' => 'Pesanan baru masuk',
                'message' => 'ORD-003 dari CV. Sukses Mandiri',
                'is_read' => false,
                'created_at' => now()->subMinutes(5),
            ],
            (object)[
                'id' => 2,
                'type' => 'payment',
                'title' => 'Pembayaran terverifikasi',
                'message' => 'ORD-001 pembayaran Rp 750.000',
                'is_read' => true,
                'created_at' => now()->subHour(),
            ],
        ];

        return view('admin.notifications', compact('notifications'));
    }
}
