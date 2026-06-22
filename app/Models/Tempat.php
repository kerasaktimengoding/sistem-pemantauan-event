<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tempat extends Model
{
    //
      use HasFactory;
    protected $table = 'tempats';

    protected $fillable = [
        'kode_tempat_usaha',
        'nomor_tempat',
        'luas_tempat',
        'status_tempat',
        'jenis_tempat',
        'keterangan',
        'pasar_id',
        'pedagang_id',
        'kecamatan_id',
        'desa_id',
    ];

    // Relasi balik ke Pasar
    public function pasar(): BelongsTo
    {
        return $this->belongsTo(Pasar::class);
    }

    // Relasi balik ke Pedagang
    public function pedagang(): BelongsTo
    {
        return $this->belongsTo(Pedagang::class);
    }

    // Relasi balik ke Wilayah
    public function kecamatan()
    {
        return $this->belongsTo(kecamatan::class, 'kecamatan_id');
    }

    public function desa()
    {
        return $this->belongsTo(desa::class, 'desa_id');
    }
}
