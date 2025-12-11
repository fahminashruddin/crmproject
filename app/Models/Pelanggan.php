<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use app\Models\Pesanans\Pesanan;

class Pelanggan extends Model
{
    use HasFactory;

    protected $table = 'pelanggans';

    protected $fillable = [
        'nama',
        'email',
        'telepon',
        'alamat',
    ];

    /**
     * Relasi ke Pesanan (One to Many)
     * Satu pelanggan bisa punya banyak pesanan
     */
    public function pesanans()
    {
        return $this->hasMany(Pesanan::class);
    }
}
