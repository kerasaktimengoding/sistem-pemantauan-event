<?php

namespace App\Models;

use Guava\Calendar\Contracts\Eventable;
use Guava\Calendar\ValueObjects\CalendarEvent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JadwalMonitoring extends Model implements Eventable
{
    //
    use HasFactory;

    protected $table = 'jadwal_monitorings';

    protected $fillable = [
        'kode_jadwal',
        'pasar_id',
        'desa_id',
        'kecamatan_id',
        'pegawai_id',
        'tanggal_rencana',
        'nomor_surat_tugas',
        'status_monitoring',
        'catatan_petugas',
    ];

    protected $casts = [
        'tanggal_rencana' => 'date',
    ];

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class);
    }

    public function desa()
    {
        return $this->belongsTo(Desa::class);
    }

    public function pasar(): BelongsTo
    {
        return $this->belongsTo(Pasar::class, 'pasar_id');
    }

    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id');
    }

    public function toCalendarEvent(): CalendarEvent
    {
        $colors = [
            'Pending' => '#f59e0b', // Amber
            'Proses' => '#3b82f6',  // Blue
            'Selesai' => '#10b981', // Green
            'Batal' => '#ef4444',   // Red
        ];
        
        $color = $colors[$this->status_monitoring] ?? '#f59e0b';
        
        // E.g. "Monitoring: Pasar Martapura (Selesai)"
        $pasarNama = $this->pasar?->nama_pasar ?? 'Pasar/Lokasi';
        $title = "Monitoring: " . $pasarNama;

        return CalendarEvent::make($this)
            ->title($title)
            ->start($this->tanggal_rencana)
            ->end($this->tanggal_rencana)
            ->backgroundColor($color);
    }
}
