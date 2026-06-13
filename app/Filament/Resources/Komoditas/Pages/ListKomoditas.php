<?php

namespace App\Filament\Resources\Komoditas\Pages;

use App\Filament\Resources\Komoditas\KomoditasResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;

class ListKomoditas extends ListRecords
{
    protected static string $resource = KomoditasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('download pdf')
                ->label('Export PDF')
                ->icon('heroicon-o-document')
                ->url(fn() => route('download7.tes7', [
                    // Mengambil kata kunci pencarian yang sedang aktif
                    'search' => $this->tableSearch,
                    // Mengambil filter yang sedang aktif
                    'filters' => $this->tableFilters,
                ]))
                ->openUrlInNewTab(),
            CreateAction::make()->
                label('Tambah Bahan Pokok')
                ->color('primary')
                ->icon('heroicon-o-plus-circle'),
        ];
    }
}
