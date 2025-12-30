<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\JenisLayanan; // Pastikan model ini ada

class DetailPesanan extends Model
{
    protected $table = 'detail_pesanans';
    protected $guarded = ['id'];

    // Relasi agar 'detail.jenisLayanan' di controller berfungsi
    public function jenisLayanan()
    {
        return $this->belongsTo(JenisLayanan::class, 'jenis_layanan_id');
    }
}