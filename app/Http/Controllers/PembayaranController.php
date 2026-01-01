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
    public function verifyPayment(Request $request, $id)
    {
        // 1. Validasi Input
        $namaMetode = $request->input('payment_method');

        // Cari Metode (Pakai Model)
        $metode = MetodePembayaran::where('nama_metode', $namaMetode)->first();

        if (!$metode) {
            return redirect()->back()->withErrors(['error' => 'Metode pembayaran tidak valid.']);
        }

        $pembayaran = Pembayaran::findOrFail($id);


        $pembayaran->update([
            'status' => 'verifikasi',
            'metode_pembayaran_id' => $metode->id,
        ]);

        $admins = Pengguna::whereHas('role', fn($q) => $q->where('nama_role', 'admin'))->get();
        Notification::send($admins, new PembayaranTerverifikasiNotification($pembayaran));

        if ($pembayaran->pesanan) {

            $statusConfirmId = DB::table('status_pesanans')
                ->whereRaw('LOWER(nama_status) like ?', ['%konfirmasi%'])
                ->value('id');

            if ($statusConfirmId) {
                $pembayaran->pesanan->update(['status_pesanan_id' => $statusConfirmId]);
            }
        }

        return redirect()->route('admin.payments')->with('success', 'Pembayaran berhasil diverifikasi.');
    }

    public function getPaymentStatus($id)
    {
        $pembayaran = Pembayaran::find($id);

        if (!$pembayaran) {
            return response()->json(['status' => 'not found'], 404);
        }

        return response()->json(['status' => $pembayaran->status]);
    }

    public function index()
    {
        $paymentsData = Pembayaran::with(['pesanan.pelanggan', 'metodePembayaran'])
            ->orderBy('created_at', 'desc')
            ->get();

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

    public function rejectPayment($id)
    {
        $pembayaran = Pembayaran::findOrFail($id);

        $pembayaran->update([
            'status' => 'failed',
        ]);

        return redirect()->route('admin.payments')->with('error', 'Pembayaran ditolak.');
    }
}
