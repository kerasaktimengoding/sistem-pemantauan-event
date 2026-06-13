<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wilayah extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'kode_wilayah', 'kecamatan_id', 'desa_id', 'luas_wilayah', 'batas_utara', 'batas_selatan', 'jumlah_penduduk', 'potensi_ekonomi', 'keterangan_geografis', 'kode_pos'
    ];

    public function kecamatan()
    {
        return $this->belongsTo(kecamatan::class, 'kecamatan_id');
    }

    public function desa()
    {
        return $this->belongsTo(desa::class, 'desa_id');
    }
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
