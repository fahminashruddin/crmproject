<?php

namespace App\Models\Pesanans;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use app\Models\Pesanans\DetailPesanan;
use app\Models\Pembayarans\Pembayaran;
use app\Models\Pelanggan;
use app\Models\Pengguna;
use app\Models\Pesanans\StatusPesanan;

class Pesanan extends Model
{
    use HasFactory;

    protected $table = 'pesanans';

    protected $fillable = [
        'tanggal_pesanan',
        'catatan',
        'pelanggan_id',
        'pengguna_id',
        'status_pesanan_id',
    ];

    /**
     * Relasi ke Detail Pesanan (One to Many)
     */
    public function detailPesanans()
    {
        return $this->hasMany(DetailPesanan::class);
    }

    /**
     * Relasi ke Pembayaran (One to Many)
     */
    public function pembayarans()
    {
        return $this->hasMany(Pembayaran::class);
    }

    /**
     * Relasi ke Pelanggan
     */
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }

    /**
     * Relasi ke User/Pengguna
     */
    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class);
    }

    /**
     * Relasi ke Status Pesanan
     */
    public function statusPesanan()
    {
        return $this->belongsTo(StatusPesanan::class);
    }
}
