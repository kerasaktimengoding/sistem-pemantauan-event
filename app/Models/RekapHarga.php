<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Desa;
class RekapHarga extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'kode_rekap_harga',
        'komoditas_id',
        'wilayah_id',
        'kecamatan_id',
        'desa_id',
        'periode_rekap',
        'harga_rata_rata',
        'harga_maksimum',
        'harga_minimum'
    ];

    // Relasi dengan komoditas
    public function komoditas()
    {
        return $this->belongsTo(Komoditas::class, 'komoditas_id');
    }

    // Relasi dengan wilayah
    public function wilayah()
    {
        return $this->belongsTo(Wilayah::class, 'wilayah_id');
    }

    public function desa()
    {
        return $this->belongsTo(Desa::class, 'desa_id');
    }

    public function kecamatan()
    {
        return $this->belongsTo(kecamatan::class, 'kecamatan_id');
    }
}
