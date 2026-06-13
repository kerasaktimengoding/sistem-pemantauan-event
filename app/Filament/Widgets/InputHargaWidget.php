<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Carbon;
use Filament\Schemas\Schema;
use Filament\Forms\Components\DatePicker;
use Filament\Widgets\ChartWidget\Concerns\HasFiltersSchema;

class InputHargaWidget extends ChartWidget
{
    protected static ?int $sort = 4;
    protected ?string $heading = 'Grafik Tren Perubahan Harga Komoditas';
    use InteractsWithPageFilters;

    use HasFiltersSchema;
    protected int|string|array $columnSpan = '3';
    // protected ?string $maxHeight = '350px';

    // 1. DROPDOWN FILTER LOKAL DI KANAN ATAS WIDGET


    // protected function getFilters(): ?array
    // {
    //     // Jika user menggunakan filter kustom (Start & End Date), beri opsi "Rentang Kustom"
    //     if (($this->pageFilters['startDate'] ?? null) || ($this->pageFilters['endDate'] ?? null)) {
    //         return [
    //             'custom' => 'Rentang Tanggal Kustom',
    //         ];
    //     }

    //     return [
    //         'month' => 'Bulan Ini',
    //         'today' => 'Hari Ini',
    //         'week' => 'Minggu Ini',
    //         'year' => 'Tahun Ini',
    //     ];
    // }

   public function filtersSchema(Schema $schema): Schema
{
    return $schema->components([
        DatePicker::make('startDate')
            ->label('Dari Tanggal')
            ->native(false)                // Memunculkan pop-up kalender Filament yang rapi
            ->displayFormat('d/m/Y')       // Mengubah format tampilan menjadi dd/mm/yyyy
            ->default(now()->subDays(30)),
            
        DatePicker::make('endDate')
            ->label('Sampai Tanggal')
            ->native(false)                // Memunculkan pop-up kalender Filament yang rapi
            ->displayFormat('d/m/Y')       // Mengubah format tampilan menjadi dd/mm/yyyy
            ->default(now()),
    ]);
}

    protected function getData(): array
    {
        // // Tangkap filter kustom dari halaman utama dashboard
        // $startDate = $this->pageFilters['startDate'] ?? null;
        // $endDate = $this->pageFilters['endDate'] ?? null;
        $startDate = $this->filters['startDate'] ?? null;
        $endDate = $this->filters['endDate'] ?? null;

        // Tentukan filter aktif. Jika ada filter kustom, abaikan filter dropdown lokal.
        if ($startDate || $endDate) {
            $activeFilter = 'custom';
        } else {
            $activeFilter = $this->filter ?? 'month';
        }

        // Atur format label sumbu X dan Grouping berdasarkan pilihan filter yang aktif
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
                // Jika filter kustom aktif, kita kelompokkan per hari agar grafiknya mendetail
                $selectDateFormat = "DATE_FORMAT(input_hargas.tanggal_input, '%d %b %Y')";
                $labelFormat = "";
                break;
            case 'year':
                $selectDateFormat = "DATE_FORMAT(input_hargas.tanggal_input, '%Y')";
                $labelFormat = "";
                break;
            case 'month':
            default:
                $selectDateFormat = "DATE_FORMAT(input_hargas.tanggal_input, '%Y-%m')";
                $labelFormat = "";
                break;
        }

        // Ambil data harga pokok dari database
        $rows = DB::table('input_hargas')
            // Jalankan filter global (Start & End Date) jika dipilih oleh user
            ->when($startDate, fn(Builder $query) => $query->whereDate('input_hargas.tanggal_input', '>=', $startDate))
            ->when($endDate, fn(Builder $query) => $query->whereDate('input_hargas.tanggal_input', '<=', $endDate))

            // Jalankan filter lokal otomatis HANYA jika filter kustom kosong
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

            // PERBAIKAN DI SINI: Urutkan berdasarkan MIN(tanggal_input) agar aman dari ONLY_FULL_GROUP_BY
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
                    'borderWidth' => 1,
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
        // Diubah ke 'line' agar lebih representatif dalam membaca grafik fluktuasi/tren berkala
        return 'bar';
    }
}
