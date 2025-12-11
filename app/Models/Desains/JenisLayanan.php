<?php

namespace App\Models\Desains;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisLayanan extends Model
{
    use HasFactory;

    protected $table = 'jenis_layanans';

    protected $fillable = [
        'nama_layanan',
        'deskripsi',
        'harga_dasar',
    ];
}