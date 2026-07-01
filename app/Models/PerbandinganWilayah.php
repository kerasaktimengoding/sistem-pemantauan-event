<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerbandinganWilayah extends Model
{
    //
     use HasFactory;

    protected $fillable = [
        'kode_perbandingan', 'komoditas_id', 'input_harga_id', 'wilayah_1_id', 'wilayah_2_id', 'harga_wilayah_1', 'harga_wilayah_2', 'pasar_id', 'pasar_2_id', 'selisih_harga', 'keterangan'
    ];

    // Relasi dengan komoditas
    public function komoditas()
    {
        return $this->belongsTo(Komoditas::class, 'komoditas_id');
    }

    // Relasi dengan wilayah 1

    public function desa1()
    {
        return $this->belongsTo(desa::class, 'desa_id');
    }

    public function desa2()
    {
        return $this->belongsTo(desa::class, 'desa_2_id');
    }
    public function pasar1()
    {
        return $this->belongsTo(Pasar::class, 'pasar_id');
    }

    public function pasar2()
    {
        return $this->belongsTo(Pasar::class, 'pasar_2_id');
    }
    // public function wilayah1()
    // {
    //     return $this->belongsTo(Wilayah::class, 'wilayah_1_id');
    // }

    // // Relasi dengan wilayah 2
    // public function wilayah2()
    // {
    //     return $this->belongsTo(Wilayah::class, 'wilayah_2_id');
    // }
}
