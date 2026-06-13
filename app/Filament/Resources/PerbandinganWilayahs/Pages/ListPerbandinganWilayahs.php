<?php

namespace App\Filament\Resources\PerbandinganWilayahs\Pages;

use App\Filament\Resources\PerbandinganWilayahs\PerbandinganWilayahResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;

class ListPerbandinganWilayahs extends ListRecords
{
    protected static string $resource = PerbandinganWilayahResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('download pdf')
                ->label('Export PDF')
                ->icon('heroicon-o-document')
                ->url(fn() => route('download19.tes19', [
                    // Mengambil kata kunci pencarian yang sedang aktif
                    'search' => $this->tableSearch,
                    // Mengambil filter yang sedang aktif
                    'filters' => $this->tableFilters,
                ]))
                ->openUrlInNewTab(),
            CreateAction::make()->
                label('Tambah Perbandingan Wilayah')
                ->color('primary')
                ->icon('heroicon-o-plus-circle'),
        ];
    }
}
