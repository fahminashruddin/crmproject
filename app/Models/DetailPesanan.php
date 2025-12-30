<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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


    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class);
    }


    public function jenisLayanan()
    {
        return $this->belongsTo(JenisLayanan::class);
    }


    public function getTotalAttribute()
    {
        return $this->jumlah * $this->harga_satuan;
    }
}
