<?php

namespace App\Filament\Resources\Komoditas\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Komoditas;

class KomoditasWidget extends StatsOverviewWidget
{
       protected ?string $heading = 'Statistik Komoditas';
     public function getColumns(): int | array
    {
        return 6;
    }

    protected function getStats(): array
    {
        return [
            //
                Stat::make('Jumlah Komodditas', Komoditas::count(). 'Komoditas')
                ->description('Jumlah Komoditas')
                 ->descriptionIcon('heroicon-m-map')
                ->color('success')
                ->chart([10, 25, 15, 30, 12, 28, 22]),
        ];
    }
}
