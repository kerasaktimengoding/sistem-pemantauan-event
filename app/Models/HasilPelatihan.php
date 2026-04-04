<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HasilPelatihan extends Model
{
    //
     use HasFactory;

    protected $fillable = [
        'kode_hasil_pelatihan', 'peserta_event_id', 'nilai_pretest', 'nilai_posttest', 'nilai_akhir', 'status_kelulusan', 'catatan'
    ];

    // Relasi dengan peserta event
    public function pesertaEvent()
    {
        return $this->belongsTo(PesertaEvent::class, 'peserta_event_id');
    }
}
