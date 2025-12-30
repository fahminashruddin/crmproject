<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Pesanan extends Model
{
    use HasFactory;

    protected $table = 'pesanans';
    protected $guarded = ['id'];

     protected $fillable = [
        'tanggal_pesanan',
        'catatan',
        'pelanggan_id',
        'pengguna_id',
        'status_pesanan_id',
    ];

    public function detailPesanans()
    {
        return $this->hasMany(DetailPesanan::class, 'pesanan_id');
    }

    /**
     * Relasi ke Pembayaran (One to Many)
     */
    public function pembayarans()
    {
        return $this->hasMany(Pembayaran::class, 'pesanan_id');
    }

    /**
     * Relasi ke Pelanggan
     */
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'pelanggan_id');
    }

    /**
     * Relasi ke User/Pengguna
     */
    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'pengguna_id');
    }

    /**
     * Relasi ke Status Pesanan
     */
    public function statusPesanan()
    {
        return $this->belongsTo(StatusPesanan::class, 'status_pesanan_id');
    }

    // === INI YANG MENYEBABKAN ERROR 'undefined relationship [status]' ===
    public function status()
    {
        return $this->belongsTo(StatusPesanan::class, 'status_pesanan_id');
    }

    public function desain()
    {
        return $this->hasOne(Desain::class, 'pesanan_id');
    }

    public function detail()
    {
        // One to Many (Satu pesanan bisa punya banyak detail item)
        return $this->hasMany(DetailPesanan::class, 'pesanan_id');
    }
}
