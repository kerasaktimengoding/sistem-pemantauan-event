<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrenHarga extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'kode_tren_harga', 'komoditas_id', 'wilayah_id', 'periode_tren', 'harga_awal', 'harga_akhir', 'arah_tren', 'persentase_perubahan'
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
}
