<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Komoditas extends Model
{
    //
     use HasFactory;

    protected $fillable = [
        'kode_komoditas', 'nama_komoditas', 'kategori_id', 'satuan_id', 'deskripsi', 'status_komoditas'
    ];

    // Relasi dengan kategori
    public function kategori()
    {
        return $this->belongsTo(KategoriKomoditas::class, 'kategori_id');
    }

    // Relasi dengan satuan
    public function satuan()
    {
        return $this->belongsTo(Satuan::class, 'satuan_id');
    }

    // Relasi dengan input harga
    public function inputHarga()
    {
        return $this->hasMany(InputHarga::class);
    }
}
