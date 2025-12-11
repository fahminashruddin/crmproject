<?php

namespace App\Models\Desains;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use app\Models\Pesanans\Pesanan;

class Desain extends Model
{
    use HasFactory;

    protected $table = 'desains';

    protected $fillable = [
        'pesanan_id',
        'status_desain_id',
        'file_desain',
        'catatan',
    ];

    /**
     * Relasi ke tabel Pesanan
     * desains.pesanan_id -> pesanans.id
     */
    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'pesanan_id');
    }

    /**
     * Relasi ke tabel StatusDesain
     * desains.status_desain_id -> status_desains.id
     */
    public function statusDesain()
    {
        return $this->belongsTo(StatusDesain::class, 'status_desain_id');
    }
}
