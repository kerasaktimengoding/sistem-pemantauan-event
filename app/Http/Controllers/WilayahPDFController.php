<?php

namespace App\Http\Controllers;

use App\Models\Wilayah;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;


class WilayahPDFController extends Controller
{
    public function downloadWilayahPDF(Request $request): mixed
    {
        $query = Wilayah::query();

        // 1. Menerapkan fitur Pencarian (Search)
        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('kode_wilayah', 'like', "%{$search}%")
                  ->orWhere('nama_wilayah', 'like', "%{$search}%")
                  ->orWhere('tipe_wilayah', 'like', "%{$search}%")
                  ->orWhere('kode_pos', 'like', "%{$search}%");
            });
        }

        // 2. Menerapkan Filter (Jika ada filter di tabel Filament)
        $filters = $request->query('filters', []);
        if (is_string($filters)) {
            $filters = json_decode($filters, true) ?: [];
        }

        if (!empty($filters)) {
            foreach ($filters as $name => $value) {
                if ($value === null) continue; // Skip jika filter kosong

                switch ($name) {
                    case 'tipe_wilayah':
                        $query->where('tipe_wilayah', $value);
                        break;
                    // Tambahkan case lain jika ada filter tambahan seperti filter kecamatan
                }
            }
        }

        // Ambil data berdasarkan query yang sudah difilter/search
        $wilayah = $query->get();

        $data = [
            'date'     => date('d-m-Y'),
            'wilayahs' => $wilayah, // Nama variabel ini harus sama dengan yang ada di @foreach di file Blade
        ];

        // Load view blade yang sudah kita buat sebelumnya
        $pdf = Pdf::loadView('filament.wilayahPDF', $data);
        
        // Nama file PDF saat didownload
        $fileName = date('d-m-Y') . ' - Laporan Data Wilayah.pdf';

        return $pdf->download($fileName);
    }
}