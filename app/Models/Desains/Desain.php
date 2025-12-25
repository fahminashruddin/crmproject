<?php

namespace App\Models\Desains;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Desain extends Model
{
    use HasFactory;

    // Menghubungkan ke tabel yang sudah ada
    protected $table = 'desains';

    // Kolom yang boleh diisi
   protected $fillable = [
    'pesanan_id',
    'status_desain_id',
    'file_desain_path',
    'catatan_revisi',
    ];

    // Relasi ke Pesanan (Sesuai Foreign Key di SQL)
    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'pesanan_id');
    }

    // Relasi ke Status Desain (Sesuai Foreign Key di SQL)
    public function statusDesain()
    {
        return $this->belongsTo(StatusDesain::class, 'status_desain_id');
    }
}