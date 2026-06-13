<?php

namespace App\Filament\Resources\Kecamatans\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\kecamatan;
use Filament\Support\Enums\IconPosition;

class KecamatanOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
       $totalKecamatan = kecamatan::count();
        $totalPenduduk = kecamatan::sum('jumlah_penduduk');
        $totalLuas = kecamatan::sum('luas_wilayah');
        
        // Menghitung rata-rata kependudukan secara aman (mencegah division by zero)
        $rataPenduduk = $totalKecamatan > 0 ? ($totalPenduduk / $totalKecamatan) : 0;

        return [
            // --- WIDGET 1: TOTAL KECAMATAN (Primary - Blue) ---
            Stat::make('Total Kecamatan', $totalKecamatan . ' Wilayah')
                ->description('Cakupan wilayah administratif')
                ->descriptionIcon('heroicon-m-building-office-2', IconPosition::Before)
                ->chart([3, 5, 4, 7, 6, 8, $totalKecamatan]) // Grafik tren pemanis layout
                ->color('primary'),

            // --- WIDGET 2: TOTAL PENDUDUK (Success - Green) ---
            Stat::make('Total Penduduk', number_format($totalPenduduk, 0, ',', '.') . ' Jiwa')
                ->description('Agregasi populasi terdata')
                ->descriptionIcon('heroicon-m-users', IconPosition::Before)
                ->chart([12000, 15000, 14000, 18000, 21000, $totalPenduduk])
                ->color('success'),

            // --- WIDGET 3: TOTAL LUAS WILAYAH (Info - Cyan) ---
            Stat::make('Total Luas Wilayah', number_format($totalLuas, 2, ',', '.') . ' Km²')
                ->description('Total area geografis sistem')
                ->descriptionIcon('heroicon-m-map', IconPosition::Before)
                ->color('info'),

            // --- WIDGET 4: RATA-RATA PENDUDUK / KECAMATAN (Warning - Amber) ---
            Stat::make('Rata-rata Penduduk', number_format($rataPenduduk, 0, ',', '.') . ' Jiwa / Kec')
                ->description('Analisis kepadatan rata-rata')
                ->descriptionIcon('heroicon-m-arrow-trending-up', IconPosition::Before)
                ->chart([500, 700, 600, 900, 850, $rataPenduduk])
                ->color('warning'),
        ];
    }
}
