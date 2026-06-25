<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kelurahan extends Model
{
    //
    use HasFactory;
    protected $fillable = [
        'kode_kelurahan','nama_kelurahan', 'kecamatan_id', 'nama_lurah', 'alamat_kantor_kelurahan', 'kode_pos', 'luas_wilayah', 'no_hp_lurah', 'is_active'
    ];

    public function kecamatan()
    {
        return $this->belongsTo(kecamatan::class, 'kecamatan_id');
    }

    public function rekapHargas()
    {
        return $this->hasMany(RekapHarga::class);
    }

    public function perbandinganWilayahs()
    {
        return $this->hasMany(PerbandinganWilayah::class, 'desa_id');
    }
}
