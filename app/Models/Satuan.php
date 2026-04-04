<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Satuan extends Model
{
    //
     use HasFactory;

    protected $fillable = [
        'kode_satuan', 'nama_satuan'
    ];

    // Relasi dengan komoditas
    public function komoditas()
    {
        return $this->hasMany(Komoditas::class);
    }
}
