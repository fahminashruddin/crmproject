<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class KendalaProduksi extends Model
{
    protected $table = 'kendala_produksis';
    protected $guarded = ['id'];

    // Relasi balik ke Produksi
    public function produksi()
    {
        return $this->belongsTo(Produksi::class, 'produksi_id');
    }
}