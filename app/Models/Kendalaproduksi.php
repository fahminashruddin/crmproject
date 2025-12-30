<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Produksi; // Tambahkan ini agar editor mengenali Class Produksi

class KendalaProduksi extends Model
{
    use HasFactory;

    // Pastikan nama tabel di database benar-benar 'kendala_produksis'
    // Jika di database namanya 'kendala_produksi' (tanpa s), hapus huruf s di bawah.
    protected $table = 'kendala_produksis'; 
    
    protected $guarded = ['id'];

    // Relasi balik ke Produksi
    public function produksi()
    {
        return $this->belongsTo(Produksi::class, 'produksi_id');
    }
}