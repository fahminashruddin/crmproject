<?php

namespace App\Models\Desains;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class StatusDesain extends Model
{
    use HasFactory;

    protected $table = 'status_desains';

    protected $fillable = [
        'nama_status',
    ];

    /**
     * Satu status desain dimiliki banyak desain
     */
    public function desains()
    {
        return $this->hasMany(Desain::class, 'status_desain_id');
    }
}
