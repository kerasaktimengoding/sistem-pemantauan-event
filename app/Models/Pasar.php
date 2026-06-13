<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pasar extends Model
{
    //
        use HasFactory;

    protected $fillable = [
        'kode_pasar', 'nama_pasar', 'kecamatan_id', 'desa_id', 'alamat_pasar', 'status_pasar'
    ];

    // Relasi dengan wilayah
   
    // masukan dulu input desa nya

        public function kecamatan()
        {
            return $this->belongsTo(kecamatan::class, 'kecamatan_id');
        }
        public function desa()
        {
            return $this->belongsTo(desa::class, 'desa_id');
        }
}
