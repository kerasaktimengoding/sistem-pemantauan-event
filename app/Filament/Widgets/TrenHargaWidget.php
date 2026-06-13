<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class TrenHargaWidget extends ChartWidget
{
    use InteractsWithPageFilters;

        protected static ?int $sort = 11;
    protected ?string $heading = 'Data Tren Harga';

    protected int|string|array $columnSpan = '12';

    protected function getData(): array
    {
        /*
        |--------------------------------------------------------------------------
        | Ambil Data Tren Harga
        |--------------------------------------------------------------------------
        | Tabel:
        | - tren_hargas
        | - komoditas
        |
        | Relasi:
        | tren_hargas.komoditas_id = komoditas.id
        */

        $rows = DB::table('tren_hargas')
            ->join('komoditas', 'tren_hargas.komoditas_id', '=', 'komoditas.id')
            ->selectRaw("
                komoditas.nama_komoditas as komoditas,
                DATE_FORMAT(tren_hargas.periode_tren, '%Y-%m') as periode,
                AVG(tren_hargas.harga_awal) as rata_harga_awal,
                AVG(tren_hargas.harga_akhir) as rata_harga_akhir
            ")
            ->groupBy(
                'komoditas.nama_komoditas',
                'periode'
            )
            ->orderBy('periode')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Label Bulan / Periode
        |--------------------------------------------------------------------------
        */

        $labels = $rows
            ->pluck('periode')
            ->unique()
            ->values();

        /*
        |--------------------------------------------------------------------------
        | Warna Chart
        |--------------------------------------------------------------------------
        */

        $colors = [
            '#2563EB',
            '#DC2626',
            '#16A34A',
            '#7C3AED',
            '#EA580C',
            '#0891B2',
            '#EC4899',
            '#F59E0B',
            '#10B981',
            '#6366F1',
        ];

        /*
        |--------------------------------------------------------------------------
        | Dataset Chart
        |--------------------------------------------------------------------------
        | Setiap komoditas memiliki:
        | - Harga Awal
        | - Harga Akhir
        */

        $datasets = [];

  foreach ($rows->groupBy('komoditas')->values() as $i => $items) {

    $color = $colors[$i % count($colors)];

            /*
            |--------------------------------------------------------------------------
            | Dataset Harga Awal
            |--------------------------------------------------------------------------
            */

            $datasets[] = [
                'label' => $items->first()->komoditas . ' - Harga Awal',

                'data' => $labels->map(function ($periode) use ($items) {

                    $data = $items->firstWhere('periode', $periode);

                    return $data
                        ? round((float) $data->rata_harga_awal, 0)
                        : null;
                })->toArray(),

                'borderColor' => $color,
                'backgroundColor' => $color,
                'borderWidth' => 2,
                'tension' => 0.3,
                'fill' => false,
                'pointRadius' => 4,
                'pointHoverRadius' => 6,
            ];

            /*
            |--------------------------------------------------------------------------
            | Dataset Harga Akhir
            |--------------------------------------------------------------------------
            */

            $datasets[] = [
                'label' => $items->first()->komoditas . ' - Harga Akhir',

                'data' => $labels->map(function ($periode) use ($items) {

                    $data = $items->firstWhere('periode', $periode);

                    return $data
                        ? round((float) $data->rata_harga_akhir, 0)
                        : null;
                })->toArray(),

                'borderColor' => $color,
                'backgroundColor' => $color,
                'borderDash' => [5, 5],
                'borderWidth' => 2,
                'tension' => 0.3,
                'fill' => false,
                'pointRadius' => 4,
                'pointHoverRadius' => 6,
            ];
        }

        return [
            'datasets' => $datasets,
            'labels' => $labels->toArray(),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Jenis Chart
    |--------------------------------------------------------------------------
    */

    protected function getType(): string
    {
        return 'bar';
    }
}