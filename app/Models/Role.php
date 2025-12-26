<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $table = 'roles';

    protected $fillable = ['nama_role'];

    // Relasi balik ke Pengguna (One to Many)
    public function penggunas()
    {
        return $this->hasMany(Pengguna::class, 'role_id');
    }
}
