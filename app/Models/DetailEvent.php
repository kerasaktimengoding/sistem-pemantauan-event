<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailEvent extends Model
{
    //
       use HasFactory;

    protected $fillable = [
        'event_id', 'kode_detail_event', 'deskripsi_event', 'anggaran_event', 'penyelenggara', 'narasumber', 'kuota_peserta'
    ];

    // Relasi dengan event
    public function event()
    {
        return $this->belongsTo(EventKegiatan::class, 'event_id');
    }
}
