<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wilayah extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'kode_wilayah', 'nama_wilayah', 'tipe_wilayah', 'kode_pos'
    ];

    // Relasi dengan komoditas
    public function komoditas()
    {
        return $this->hasMany(Komoditas::class);
    }

    // Relasi dengan pedagang
    public function pedagang()
    {
        return $this->hasMany(Pedagang::class);
    }

    // Relasi dengan pasar
    public function pasar()
    {
        return $this->hasMany(Pasar::class);
    }
}
