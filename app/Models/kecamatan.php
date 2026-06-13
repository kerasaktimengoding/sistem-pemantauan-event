<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class kecamatan extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'kode_kecamatan', 'nama_kecamatan', 'nama_camat', 'nip_camat', 'alamat_kantor', 'no_telp', 'email_kecamatan', 'luas_wilayah', 'jumlah_penduduk', 'keterangan'
    ];

    public function desas()
{
    return $this->hasMany(desa::class, 'kecamatan_id');
}

public function wilayahs()
{
    return $this->hasMany(wilayah::class, 'kecamatan_id');
}
}
