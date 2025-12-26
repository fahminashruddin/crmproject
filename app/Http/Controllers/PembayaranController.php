<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembayaran;
use App\Models\MetodePembayaran;
use App\Models\StatusPesanan;
use Illuminate\Support\Facades\DB;
use App\Notifications\PembayaranTerverifikasiNotification;
use App\Models\Pengguna;
use Illuminate\Support\Facades\Notification;

class PembayaranController extends Controller
{
    /**
     * Method: verifyPayment
     * Menggunakan Model untuk update dan relasi
     */
    public function verifyPayment(Request $request, $id)
    {
        // 1. Validasi Input
        $namaMetode = $request->input('payment_method');

        // Cari Metode (Pakai Model)
        $metode = MetodePembayaran::where('nama_metode', $namaMetode)->first();

        if (!$metode) {
            return redirect()->back()->withErrors(['error' => 'Metode pembayaran tidak valid.']);
        }

        // 2. Cari Pembayaran (Pakai findOrFail agar otomatis 404 jika tidak ketemu)
        $pembayaran = Pembayaran::findOrFail($id);

        // 3. Update Pembayaran
        // 'updated_at' otomatis diurus oleh Laravel
        $pembayaran->update([
            'status' => 'verifikasi',
            'metode_pembayaran_id' => $metode->id,
        ]);

        $admins = Pengguna::whereHas('role', fn($q) => $q->where('nama_role', 'admin'))->get();
        Notification::send($admins, new PembayaranTerverifikasiNotification($pembayaran));

        // 4. Update Status Pesanan (Menggunakan Relasi)
        // Cek apakah pembayaran punya pesanan
        if ($pembayaran->pesanan) {

            // OPSI A: Jika kamu tahu ID status konfirmasi (misal ID 2)
            // $pembayaran->pesanan->update(['status_pesanan_id' => 2]);

            // OPSI B: Mencari ID Status secara dinamis (seperti kodemu sebelumnya)
            // Menggunakan DB Facade untuk lookup cepat jika Model StatusPesanan belum ready
            $statusConfirmId = DB::table('status_pesanans')
                ->whereRaw('LOWER(nama_status) like ?', ['%konfirmasi%'])
                ->value('id');

            if ($statusConfirmId) {
                // Update via relasi
                $pembayaran->pesanan->update(['status_pesanan_id' => $statusConfirmId]);
            }
        }

        return redirect()->route('admin.payments')->with('success', 'Pembayaran berhasil diverifikasi.');
    }

    /**
     * Method: getPaymentStatus
     */
    public function getPaymentStatus($id)
    {
        $pembayaran = Pembayaran::find($id);

        if (!$pembayaran) {
            return response()->json(['status' => 'not found'], 404);
        }

        return response()->json(['status' => $pembayaran->status]);
    }

    /**
     * Method: index
     * Refactor besar-besaran: Ganti leftJoin manual dengan Eager Loading (with)
     */
    public function index()
    {
        // 'with' akan otomatis mengambil data pesanan, pelanggan, dan metode
        // Pastikan Model Pesanan punya relasi ke Pelanggan: public function pelanggan() { ... }
        $paymentsData = Pembayaran::with(['pesanan.pelanggan', 'metodePembayaran'])
            ->orderBy('created_at', 'desc')
            ->get();

        // 2. FILTER DATA (Menggunakan Collection Laravel)
        // Tidak perlu query ulang ke database, cukup filter hasil di atas
        $pendingPayments = $paymentsData->where('status', 'pending');

        $verifiedPayments = $paymentsData->whereIn('status', ['verifikasi', 'verified', 'lunas']);

        $totalRevenue = $verifiedPayments->sum('nominal');

        // 3. DATA DROPDOWN
        $paymentMethods = MetodePembayaran::pluck('nama_metode');

        // 4. KIRIM KE VIEW
        return view('admin.payments', compact(
            'pendingPayments',
            'verifiedPayments',
            'totalRevenue',
            'paymentMethods',
            'paymentsData'
        ));
    }

    /**
     * Method: rejectPayment
     */
    public function rejectPayment($id)
    {
        $pembayaran = Pembayaran::findOrFail($id);

        $pembayaran->update([
            'status' => 'failed',
        ]);

        return redirect()->route('admin.payments')->with('error', 'Pembayaran ditolak.');
    }
}
