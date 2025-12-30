<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use app\Models\Pesanan;

class StatusPesanan extends Model
{
    use HasFactory;

    protected $table = 'status_pesanans';

    protected $fillable = [
        'nama_status',
    ];

    /**
     * Satu status pesanan bisa dimiliki banyak pesanan
     */
    public function pesanans()
    {
        return $this->hasMany(Pesanan::class, 'status_pesanan_id');
    }
}
