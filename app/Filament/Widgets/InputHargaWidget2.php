<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Carbon;
use Filament\Schemas\Schema;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Widgets\ChartWidget\Concerns\HasFiltersSchema;

class InputHargaWidget2 extends ChartWidget
{
    protected static ?int $sort = 5;
    protected ?string $heading = 'Grafik Tren Perubahan Harga Komoditas 2';

    use InteractsWithPageFilters;
    use HasFiltersSchema;
    
    protected int|string|array $columnSpan = '6';

    public function filtersSchema(Schema $schema): Schema
    {
        return $schema->components([
            DatePicker::make('startDate')
                ->label('Dari Tanggal')
                ->native(false)
                ->displayFormat('d/m/Y')
                ->default(now()->subDays(60)),
                
            DatePicker::make('endDate')
                ->label('Sampai Tanggal')
                ->native(false)
                ->displayFormat('d/m/Y')
                ->default(now()),

            Select::make('komoditas_id')
                ->label('Komoditas')
                ->placeholder('-- Semua Komoditas --')
                ->options(\App\Models\Komoditas::pluck('nama_komoditas', 'id')->toArray())
                ->native(false),
        ]);
    }

    protected function getData(): array
    {
        $startDate = $this->filters['startDate'] ?? null;
        $endDate = $this->filters['endDate'] ?? null;
        $komoditasId = $this->filters['komoditas_id'] ?? null;

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
                $selectDateFormat = "DATE_FORMAT(input_hargas.tanggal_input, '%Y-%m')";
                $labelFormat = "";
                break;
        }

        $rows = DB::table('input_hargas')
            ->when($startDate, fn(Builder $query) => $query->whereDate('input_hargas.tanggal_input', '>=', $startDate))
            ->when($endDate, fn(Builder $query) => $query->whereDate('input_hargas.tanggal_input', '<=', $endDate))
            ->when($komoditasId, fn(Builder $query) => $query->where('input_hargas.komoditas_id', $komoditasId))

            ->when($activeFilter === 'today', fn(Builder $query) => $query->whereDate('input_hargas.tanggal_input', Carbon::today()))
            ->when($activeFilter === 'week', fn(Builder $query) => $query->whereBetween('input_hargas.tanggal_input', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]))
            ->when($activeFilter === 'month', fn(Builder $query) => $query->whereMonth('input_hargas.tanggal_input', Carbon::now()->month)->whereYear('input_hargas.tanggal_input', Carbon::now()->year))
            ->when($activeFilter === 'year', fn(Builder $query) => $query->whereYear('input_hargas.tanggal_input', Carbon::now()->year))

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
            '#8b5cf6', // Violet
            '#ec4899', // Pink
            '#06b6d4', // Cyan
            '#0ea5e9', // Sky
            '#ef4444', // Red
            '#10b981', // Emerald
            '#f59e0b', // Amber
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
                    'backgroundColor' => $color . '15',
                    'borderWidth' => 2.5,
                    'tension' => 0.35,
                    'fill' => true,
                    'pointRadius' => 3,
                    'pointHoverRadius' => 5,
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
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'top',
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => false,
                ]
            ]
        ];
    }
}