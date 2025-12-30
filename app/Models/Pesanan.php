<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Pesanan extends Model
{
    use HasFactory;

    protected $table = 'pesanans';
    protected $guarded = ['id'];

    // === INI YANG MENYEBABKAN ERROR 'undefined relationship [status]' ===
    public function status()
    {
        return $this->belongsTo(StatusPesanan::class, 'status_pesanan_id');
    }

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'pelanggan_id');
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