<?php

namespace App\Models\Layanans;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use app\Models\Pesanans\DetailPesanan;
class JenisLayanan extends Model
{
    use HasFactory;

    protected $table = 'jenis_layanans';

    protected $fillable = [
        'nama_layanan',
        'deskripsi',
        'harga_dasar',
    ];

    /**
     * Relasi ke Detail Pesanan (One to Many)
     */
    public function detailPesanans()
    {
        return $this->hasMany(DetailPesanan::class);
    }
}
