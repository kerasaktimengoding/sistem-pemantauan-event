<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Carbon;

class InputHargaWidget2 extends ChartWidget
{
    protected static ?int $sort = 5;
    protected ?string $heading = 'Grafik Tren Perubahan Harga Komoditas 2';

    // Trait ini yang bertugas menghubungkan state filter halaman utama ke dalam widget secara otomatis
    use InteractsWithPageFilters;
    
    protected int|string|array $columnSpan = '3';

    // Tentukan nilai default filter lokal widget langsung di properti bawaan Filament
    public ?string $filter = 'month';

    // 1. DROPDOWN FILTER LOKAL DI KANAN ATAS WIDGET
    protected function getFilters(): ?array
    {
        // Jika user menggunakan filter kustom dari Dashboard Utama, kunci tampilan lokal
        if (($this->pageFilters['startDate'] ?? null) || ($this->pageFilters['endDate'] ?? null)) {
            return [
                'custom' => 'Rentang Tanggal Kustom',
            ];
        }

        $tahunSekarang = Carbon::now()->year;

        return [
            'month' => 'Bulan Ini',
            'today' => 'Hari Ini',
            'week' => 'Minggu Ini',
            'year' => 'Tahun Ini',
            ($tahunSekarang - 1) => 'Tahun ' . ($tahunSekarang - 1),
            ($tahunSekarang - 2) => 'Tahun ' . ($tahunSekarang - 2),
            ($tahunSekarang - 3) => 'Tahun ' . ($tahunSekarang - 3),
        ];
    }

    protected function getData(): array
    {
        // Membaca filter global dari Dashboard
        $startDate = $this->pageFilters['startDate'] ?? null;
        $endDate = $this->pageFilters['endDate'] ?? null;

        // Tentukan filter aktif
        if ($startDate || $endDate) {
            $activeFilter = 'custom';
        } else {
            $activeFilter = $this->filter ?? 'month';
        }

        switch ($activeFilter) {
            case 'today':
                $selectDateFormat = "DATE_FORMAT(input_hargas.tanggal_input, '%H:%i')";
                $labelFormat = "Jam ";
                break;
            case 'week':
                $selectDateFormat = "DATE_FORMAT(input_hargas.tanggal_input, '%Y-W%v')";
                $labelFormat = "Minggu ";
                break;
            case 'custom':
                $selectDateFormat = "DATE_FORMAT(input_hargas.tanggal_input, '%d %b %Y')";
                $labelFormat = "";
                break;
            case 'year':
                $selectDateFormat = "DATE_FORMAT(input_hargas.tanggal_input, '%Y-%m')";
                $labelFormat = "";
                break;
            case 'month':
            default:
                if (is_numeric($activeFilter)) {
                    $selectDateFormat = "DATE_FORMAT(input_hargas.tanggal_input, '%Y-%m')";
                } else {
                    $selectDateFormat = "DATE_FORMAT(input_hargas.tanggal_input, '%Y-%m')";
                }
                $labelFormat = "";
                break;
        }

        // Ambil data harga pokok dari database
        $rows = DB::table('input_hargas')
            ->when($startDate, fn(Builder $query) => $query->whereDate('input_hargas.tanggal_input', '>=', $startDate))
            ->when($endDate, fn(Builder $query) => $query->whereDate('input_hargas.tanggal_input', '<=', $endDate))

            ->when($activeFilter === 'today', fn(Builder $query) => $query->whereDate('input_hargas.tanggal_input', Carbon::today()))
            ->when($activeFilter === 'week', fn(Builder $query) => $query->whereBetween('input_hargas.tanggal_input', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]))
            ->when($activeFilter === 'month', fn(Builder $query) => $query->whereMonth('input_hargas.tanggal_input', Carbon::now()->month)->whereYear('input_hargas.tanggal_input', Carbon::now()->year))
            ->when($activeFilter === 'year', fn(Builder $query) => $query->whereYear('input_hargas.tanggal_input', Carbon::now()->year))

            ->when(is_numeric($activeFilter), fn(Builder $query) => $query->whereYear('input_hargas.tanggal_input', $activeFilter))

            ->join('komoditas', 'input_hargas.komoditas_id', '=', 'komoditas.id')
            ->selectRaw("
                komoditas.nama_komoditas as komoditas,
                {$selectDateFormat} as periode,
                AVG(input_hargas.harga) as rata_harga
            ")
            ->groupBy('komoditas.nama_komoditas', 'periode')
            ->orderBy(DB::raw('MIN(input_hargas.tanggal_input)'), 'asc')
            ->get();

        $labels = $rows->pluck('periode')->unique()->values();

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

        $datasets = $rows
            ->groupBy('komoditas')
            ->values()
            ->map(function ($items, $i) use ($labels, $colors) {
                $color = $colors[$i % count($colors)];

                return [
                    'label' => $items->first()->komoditas,
                    'data' => $labels->map(function ($periode) use ($items) {
                        $data = $items->firstWhere('periode', $periode);
                        return $data ? round((float) $data->rata_harga, 0) : null;
                    })->toArray(),
                    'borderColor' => $color,
                    'backgroundColor' => $color,
                    'borderWidth' => 2,
                    'tension' => 0.3,
                    'fill' => false,
                    'pointRadius' => 4,
                    'pointHoverRadius' => 6,
                ];
            })
            ->toArray();

        return [
            'datasets' => $datasets,
            'labels' => $labels->map(fn($l) => $labelFormat . $l)->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}