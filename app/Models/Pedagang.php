<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedagang extends Model
{
    //
     use HasFactory;

    protected $fillable = [
        'nik', 'kode_pedagang', 'nama_pedagang', 'jenis_tempat', 'no_hp', 'alamat', 'wilayah_id', 'status_pedagang'
    ];

    // Relasi dengan wilayah
    public function wilayah()
    {
        return $this->belongsTo(Wilayah::class, 'wilayah_id');
    }
}
