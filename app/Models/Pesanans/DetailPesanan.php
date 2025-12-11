<?php

namespace App\Models\Pesanans;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use app\Models\Layanans\JenisLayanan;
class DetailPesanan extends Model
{
    use HasFactory;

    protected $table = 'detail_pesanans';

    protected $fillable = [
        'spesifikasi',
        'jumlah',
        'harga_satuan',
        'pesanan_id',
        'jenis_layanan_id',
    ];

    /**
     * Relasi ke Pesanan (Many to One)
     */
    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class);
    }

    /**
     * Relasi ke Jenis Layanan
     */
    public function jenisLayanan()
    {
        return $this->belongsTo(JenisLayanan::class);
    }

    /**
     * Accessor total harga per item
     */
    public function getTotalAttribute()
    {
        return $this->jumlah * $this->harga_satuan;
    }
}
