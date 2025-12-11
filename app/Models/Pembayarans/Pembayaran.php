<?php

namespace App\Models\Pembayarans;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use app\Models\Pesanans\Pesanan;

class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'pembayarans';

    protected $fillable = [
        'nominal',
        'bukti_bayar_path',
        'tanggal_bayar',
        'status',
        'pesanan_id',
        'metode_pembayaran_id',
    ];

    /**
     * Relasi ke Pesanan (Many to One)
     */
    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class);
    }

    /**
     * Relasi ke Metode Pembayaran
     */
    public function metodePembayaran()
    {
        return $this->belongsTo(MetodePembayaran::class);
    }
}
