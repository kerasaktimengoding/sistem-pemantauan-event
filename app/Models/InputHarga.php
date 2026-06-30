<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InputHarga extends Model
{
    //
     use HasFactory;

        public function rekapHarga()
    {
        return $this->hasMany(RekapHarga::class);
    }

    protected $fillable = [
        'kode_input_harga', 'komoditas_id', 'kecamatan_id', 'desa_id', 'pasar_id', 'pedagang_id', 'pegawai_id', 'harga', 'tanggal_input', 'sumber_data', 'keterangan'
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

    public function kecamatan()
    {
        return $this->belongsTo(kecamatan::class, 'kecamatan_id');
    }

    public function desa()
    {
        return $this->belongsTo(desa::class, 'desa_id');
    }

    // Relasi dengan pasar
    public function pasar()
    {
        return $this->belongsTo(Pasar::class, 'pasar_id');
    }

    // Relasi dengan pedagang
    public function pedagang()
    {
        return $this->belongsTo(Pedagang::class, 'pedagang_id');
    }

    // Relasi dengan pegawai
    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id');
    }
}
