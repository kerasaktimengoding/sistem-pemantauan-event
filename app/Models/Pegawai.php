<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    //
     use HasFactory;

    protected $fillable = [
        'nip', 'nik', 'nama_pegawai', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir', 'alamat', 'no_hp', 'email', 'jabatan_id', 'kecamatan_id', 'desa_id', 'status_pegawai', 'tanggal_masuk'
    ];

    // Relasi dengan jabatan
    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class, 'jabatan_id');
    }

    // Relasi dengan wilayah
    public function kecamatan()
    {
        return $this->belongsTo(kecamatan::class, 'kecamatan_id');
    }

    public function desa()
    {
        return $this->belongsTo(desa::class, 'desa_id');
    }
}
