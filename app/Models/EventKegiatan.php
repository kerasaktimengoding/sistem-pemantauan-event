<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventKegiatan extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'kode_event', 'nama_event', 'jenis_event', 'wilayah_id', 'tanggal_mulai', 'tanggal_selesai', 'lokasi_event', 'status_event'
    ];

    // Relasi dengan wilayah
    public function wilayah()
    {
        return $this->belongsTo(Wilayah::class, 'wilayah_id');
    }

    // Relasi dengan detail event
    public function detailEvent()
    {
        return $this->hasMany(DetailEvent::class);
    }   
}
