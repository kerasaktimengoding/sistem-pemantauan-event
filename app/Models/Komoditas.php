<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Komoditas extends Model
{
    //
     use HasFactory;

    protected $fillable = [
        'kode_komoditas', 'nama_komoditas', 'kategori', 'satuan', 'deskripsi', 'status_komoditas'
    ];

    // Relasi dengan kategori
   
    // Relasi dengan input harga
    public function inputHarga()
    {
        return $this->hasMany(InputHarga::class);
    }
}
