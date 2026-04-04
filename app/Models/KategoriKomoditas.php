<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriKomoditas extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'kode_kategori', 'nama_kategori'
    ];

    // Relasi dengan komoditas
    public function komoditas()
    {
        return $this->hasMany(Komoditas::class);
    }
}
