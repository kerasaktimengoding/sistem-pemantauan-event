<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedagang extends Model
{
    //
     use HasFactory;

    protected $fillable = [
        'nik', 'kode_pedagang', 'nama_pedagang', 'no_hp', 'alamat', 'kecamatan_id', 'desa_id','status_pedagang'
    ];

    // Relasi dengan wilayah
    public function wilayah()
    {
        return $this->belongsTo(Wilayah::class, 'wilayah_id');
    }

    // public function kecamatan()
    // {
    //     return $this->belongsTo(Kecamatan::class, 'kecamatan_id');
    // }
     public function kecamatan()
    {
        return $this->belongsTo(kecamatan::class, 'kecamatan_id');
    }

    public function desa()
    {
        return $this->belongsTo(desa::class, 'desa_id');
    }

}
