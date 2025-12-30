<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produksi extends Model
{
    use HasFactory;

    protected $table = 'produksis';

    /**
     * $guarded = ['id'] berarti semua kolom BOLEH diisi kecuali 'id'.
     * Ini lebih fleksibel daripada $fillable untuk tahap pengembangan.
     */
    protected $guarded = ['id'];

    /**
     * Relasi ke tabel Pesanan
     * (Child ke Parent: Produksi milik satu Pesanan)
     */
    public function pesanan()
    {
        // Pastikan class Pesanan ada di namespace App\Models
        return $this->belongsTo(Pesanan::class, 'pesanan_id');
    }

    /**
     * Relasi ke tabel Kendala Produksi (Sesuai ERD)
     * (Parent ke Child: Satu Produksi bisa punya banyak Kendala)
     */
    public function kendala()
    {
        // Pastikan class KendalaProduksi ada di namespace App\Models
        return $this->hasMany(KendalaProduksi::class, 'produksi_id');
    }
}