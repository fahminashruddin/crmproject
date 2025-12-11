<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    use HasFactory;

    // Tabel yang digunakan
    protected $table = 'jenis_layanans';

    // Kolom yang boleh diisi
    protected $fillable = [
        'nama_layanan',
        'deskripsi',
        'harga_satuan',
    ];
}
