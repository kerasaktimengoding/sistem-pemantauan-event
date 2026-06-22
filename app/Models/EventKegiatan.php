<?php

namespace App\Models;

use Guava\Calendar\Contracts\Eventable;
use Guava\Calendar\ValueObjects\CalendarEvent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventKegiatan extends Model implements Eventable
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

    public function toCalendarEvent(): CalendarEvent
    {
        $colors = [
            'Direncanakan' => '#3b82f6', // blue
            'Berjalan' => '#f59e0b',     // amber
            'Selesai' => '#10b981',      // green
            'Dibatalkan' => '#ef4444',   // red
        ];
        
        $color = $colors[$this->status_event] ?? '#3b82f6';

        return CalendarEvent::make($this)
            ->title($this->nama_event)
            ->start($this->tanggal_mulai)
            ->end($this->tanggal_selesai ?? $this->tanggal_mulai)
            ->backgroundColor($color);
    }
}
