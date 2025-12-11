<?php

namespace App\Models\Pesanans;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use app\Models\Pesanans\Pesanan;

class StatusPesanan extends Model
{
    use HasFactory;

    protected $table = 'status_pesanans';

    protected $fillable = [
        'nama_status',
    ];

    /**
     * Relasi ke Pesanan (One to Many)
     * Satu status bisa digunakan oleh banyak pesanan
     */
    public function pesanans()
    {
        return $this->hasMany(Pesanan::class);
    }
}
