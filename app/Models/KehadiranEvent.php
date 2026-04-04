<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KehadiranEvent extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'kode_kehadiran', 'peserta_event_id', 'status_kehadiran', 'waktu_kehadiran', 'catatan'
    ];

    // Relasi dengan peserta event
    public function pesertaEvent()
    {
        return $this->belongsTo(PesertaEvent::class, 'peserta_event_id');
    }
}
