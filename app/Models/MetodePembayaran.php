<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MetodePembayaran extends Model
{
    use HasFactory;

    protected $table = 'metode_pembayarans';

    protected $fillable = [
        'nama_metode',
    ];


    public function pembayarans()
    {
        return $this->hasMany(Pembayaran::class, 'metode_pembayaran_id');
}
}
