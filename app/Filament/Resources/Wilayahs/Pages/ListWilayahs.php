<?php

namespace App\Filament\Resources\Wilayahs\Pages;

use App\Filament\Resources\Wilayahs\WilayahResource;

use Filament\Resources\Pages\ListRecords;

use App\Filament\Resources\Wilayahs\Widgets\WilayahWidget;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;

class ListWilayahs extends ListRecords
{
    protected static string $resource = WilayahResource::class;
     protected ?string $heading = 'DATA WILAYAH';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('Laporan')
                ->label('Laporan PDF')
                ->icon('heroicon-o-printer')
                ->color('primary')
                ->url(fn () => route('download1.tes3', [
                    // Mengambil kata kunci pencarian yang sedang aktif
                    'search' => $this->tableSearch,
                    // Mengambil filter yang sedang aktif
                    'filters' => $this->tableFilters,
                ]))
                ->openUrlInNewTab(),
            CreateAction::make()->
                label('Tambah Wilayah')
                ->color('primary')
                ->icon('heroicon-o-plus-circle'),
        ];
    }
    public function getHeaderWidgets(): array
    {
        return [
            WilayahWidget::class,
        ];
        
    }
}
