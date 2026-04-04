<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pasar extends Model
{
    //
        use HasFactory;

    protected $fillable = [
        'kode_pasar', 'nama_pasar', 'wilayah_id', 'alamat_pasar', 'status_pasar'
    ];

    // Relasi dengan wilayah
    public function wilayah()
    {
        return $this->belongsTo(Wilayah::class, 'wilayah_id');
    }
}
