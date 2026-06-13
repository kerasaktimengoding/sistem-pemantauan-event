<?php

namespace App\Filament\Resources\InputHargas\Pages;

use App\Filament\Resources\InputHargas\InputHargaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;

class ListInputHargas extends ListRecords
{
    protected static string $resource = InputHargaResource::class;

    protected function getHeaderActions(): array
    {
        return [
           Action::make('download pdf')
                ->label('Export PDF')
                ->icon('heroicon-o-document')
                ->url(fn() => route('download11.tes11', [
                    // Mengambil kata kunci pencarian yang sedang aktif
                    'search' => $this->tableSearch,
                    // Mengambil filter yang sedang aktif
                    'filters' => $this->tableFilters,
                ]))
                ->openUrlInNewTab(),
            CreateAction::make()->
                label('Tambah Harga')
                ->color('primary')
                ->icon('heroicon-o-plus-circle'),
        ];
    }
}
