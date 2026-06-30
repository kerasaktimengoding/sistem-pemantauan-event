<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JadwalMonitoring extends Model
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
}
