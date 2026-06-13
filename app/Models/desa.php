<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class desa extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'kode_desa', 'nama_desa', 'kecamatan_id', 'nama_pembakal', 'alamat_kantor_desa', 'kode_pos', 'latitude', 'longitude', 'no_hp_pembakal', 'is_active'
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
