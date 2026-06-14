<?php

namespace App\Http\Controllers;

use App\Models\Kecamatan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class KecamatanPDFController extends Controller
{
    public function downloadKecamatanPDF(Request $request): mixed
    {
        $query = Kecamatan::query();

        /*
        |--------------------------------------------------------------------------
        | Pencarian
        |--------------------------------------------------------------------------
        | Mengambil parameter pencarian yang dikirim dari tabel Filament.
        */
        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('kode_kecamatan', 'like', "%{$search}%")
                    ->orWhere('nama_kecamatan', 'like', "%{$search}%")
                    ->orWhere('nama_camat', 'like', "%{$search}%")
                    ->orWhere('nip_camat', 'like', "%{$search}%")
                    ->orWhere('no_telp', 'like', "%{$search}%")
                    ->orWhere('email_kecamatan', 'like', "%{$search}%")
                    ->orWhere('alamat_kantor', 'like', "%{$search}%")
                    ->orWhere('luas_wilayah', 'like', "%{$search}%")
                    ->orWhere('jumlah_penduduk', 'like', "%{$search}%")
                    ->orWhere('keterangan', 'like', "%{$search}%");
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Filter
        |--------------------------------------------------------------------------
        | Filament dapat mengirim filter dalam bentuk array atau JSON.
        */
        $filters = $request->query('filters', []);

        if (is_string($filters)) {
            $filters = json_decode($filters, true) ?: [];
        }

        foreach ($filters as $name => $value) {
            /*
             * Pada beberapa versi Filament, nilai filter dapat berada
             * di dalam array dengan key "value".
             */
            if (is_array($value) && array_key_exists('value', $value)) {
                $value = $value['value'];
            }

            if ($value === null || $value === '') {
                continue;
            }

            switch ($name) {
                case 'kode_kecamatan':
                    $query->where('kode_kecamatan', $value);
                    break;

                case 'nama_kecamatan':
                    $query->where(
                        'nama_kecamatan',
                        'like',
                        "%{$value}%"
                    );
                    break;

                case 'nama_camat':
                    $query->where(
                        'nama_camat',
                        'like',
                        "%{$value}%"
                    );
                    break;

                case 'jumlah_penduduk':
                    $query->where('jumlah_penduduk', $value);
                    break;

                case 'kepadatan_penduduk':
                    if ($value === 'padat') {
                        $query->where('jumlah_penduduk', '>', 5000);
                    }

                    if ($value === 'normal') {
                        $query->where('jumlah_penduduk', '<=', 5000);
                    }
                    break;

                /*
                 * Tambahkan filter lain sesuai filter yang terdapat
                 * pada tabel Kecamatan Filament.
                 */
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Pengurutan
        |--------------------------------------------------------------------------
        */
        $sortColumn = $request->query('sort', 'nama_kecamatan');
        $sortDirection = strtolower(
            $request->query('direction', 'asc')
        );

        $allowedSortColumns = [
            'kode_kecamatan',
            'nama_kecamatan',
            'nama_camat',
            'luas_wilayah',
            'jumlah_penduduk',
            'created_at',
        ];

        if (! in_array($sortColumn, $allowedSortColumns, true)) {
            $sortColumn = 'nama_kecamatan';
        }

        if (! in_array($sortDirection, ['asc', 'desc'], true)) {
            $sortDirection = 'asc';
        }

        $kecamatans = $query
            ->orderBy($sortColumn, $sortDirection)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Data Blade
        |--------------------------------------------------------------------------
        */
        $data = [
            'date'       => now()->format('d-m-Y'),
            'kecamatans' => $kecamatans,
        ];

        /*
        |--------------------------------------------------------------------------
        | Membuat PDF
        |--------------------------------------------------------------------------
        | Sesuaikan dengan lokasi:
        | resources/views/filament/pdf/KecamatanPDF.blade.php
        */
        $pdf = Pdf::loadView(
            'filament.KecamatanPDF',
            $data
        )->setPaper('a4', 'landscape');

        $fileName = now()->format('d-m-Y')
            . ' - Laporan Kecamatan.pdf';

        return $pdf->download($fileName);
    }
}

