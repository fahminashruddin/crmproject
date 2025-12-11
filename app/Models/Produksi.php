<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use app\Models\Pesanans\Pesanan;

class Produksi extends Model
{
    use HasFactory;

    protected $table = 'produksis';

    protected $fillable = [
        'tanggal_mulai',
        'tanggal_selesai',
        'catatan',
        'pesanan_id',
    ];

    /**
     * Relasi ke tabel Pesanan
     * produksis.pesanan_id -> pesanans.id
     */
    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'pesanan_id');
    }
}
