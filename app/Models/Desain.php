<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Desain extends Model
{
    // Pastikan designer_id ada di tabel desains sesuai Class Diagram
    protected $fillable = [
        'pesanan_id', 
        'id_status_desain', 
        'tanggal_mulai', 
        'tanggal_selesai', 
        'catatan', 
        'designer_id', // Ini foreign key ke tabel users/penggunas
        'revisi_ke', 
        'file_desain'
    ];

    /**
     * Relasi ke User (Designer) sesuai Class Diagram
     */
    public function designer(): BelongsTo
    {
        // Jika tabel user Anda bernama 'penggunas' (berdasarkan Database Queries Anda)
        return $this->belongsTo(User::class, 'designer_id');
    }

    /**
     * Relasi ke StatusDesain
     */
    public function statusDesain(): BelongsTo
    {
        return $this->belongsTo(StatusDesain::class, 'id_status_desain');
    }

    /**
     * Relasi ke Pesanan
     */
    public function pesanan(): BelongsTo
    {
        return $this->belongsTo(Pesanan::class, 'pesanan_id');
    }
}