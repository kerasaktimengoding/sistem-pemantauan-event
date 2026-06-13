<?php

namespace App\Filament\Resources\Wilayahs\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Wilayah;

class WilayahWidget extends StatsOverviewWidget
{
     protected ?string $heading = 'Statistik Wilayah';
     public function getColumns(): int | array
    {
        return 6;
    }
    protected function getStats(): array
    {  
    
    {   
        
        return [
            // Stat::make('Jumlah Wilayah', Wilayah::count() . ' Desa')
            //     ->description('Wilayah Terdaftar')
            //     ->descriptionIcon('heroicon-m-map')
            //     ->color('success')
            //     ->chart([10, 25, 15, 30, 12, 28, 22]),
            // Stat::make('Jumlah Kecamatan', Wilayah::count('nama_kecamatan') . ' Kecamatan')
            //     ->description('Kecamatan Terdaftar')
            //     ->descriptionIcon('heroicon-m-map')
            //     ->color('primary')
            //     ->chart([5, 15, 10, 20, 8, 18, 12]),
            
                // menghitung jumlah penduduk diambil dari desa

                
            
           
        ];
    }
    }}
