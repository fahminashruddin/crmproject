<?php

namespace App\Models\Pembayarans;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MetodePembayaran extends Model
{
    use HasFactory;

    protected $table = 'metode_pembayarans';

    protected $fillable = [
        'nama_metode',
    ];

    /**
     * Relasi ke Pembayaran (One to Many)
     */
    public function pembayarans()
    {
        return $this->hasMany(Pembayaran::class);
    }
}
