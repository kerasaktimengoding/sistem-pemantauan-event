<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PesertaEvent extends Model
{
    //
use HasFactory;

    protected $fillable = [
        'kode_peserta_event', 'event_id', 'nik', 'nama_peserta', 'nama_usaha', 'jenis_produk', 'jenis_kelamin', 'no_hp', 'alamat', 'wilayah_id', 'tanggal_registrasi', 'status_partisipasi'
    ];

    // Relasi dengan event
    public function event()
    {
        return $this->belongsTo(EventKegiatan::class, 'event_id');
    }

    // Relasi dengan wilayah
    public function wilayah()
    {
        return $this->belongsTo(Wilayah::class, 'wilayah_id');
    }
}
