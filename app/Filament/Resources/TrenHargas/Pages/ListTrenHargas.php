<?php

namespace App\Filament\Resources\TrenHargas\Pages;

use App\Filament\Resources\TrenHargas\TrenHargaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;

class ListTrenHargas extends ListRecords
{
    protected static string $resource = TrenHargaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('download pdf')
                ->label('Export PDF')
                ->icon('heroicon-o-document')
                ->url(fn() => route('download18.tes18', [
                    // Mengambil kata kunci pencarian yang sedang aktif
                    'search' => $this->tableSearch,
                    // Mengambil filter yang sedang aktif
                    'filters' => $this->tableFilters,
                ]))
                ->openUrlInNewTab(),
            CreateAction::make()->
                label('Tambah Tren Harga')
                ->color('primary')
                ->icon('heroicon-o-plus-circle'),
        ];
    }
}
