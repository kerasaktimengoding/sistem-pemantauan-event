<?php

namespace App\Filament\Resources\Pasars\Pages;

use App\Filament\Resources\Pasars\PasarResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;

class ListPasars extends ListRecords
{
    protected static string $resource = PasarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('download pdf')
                ->label('Export PDF')
                ->icon('heroicon-o-document')
                ->url(fn() => route('download8.tes8', [
                    // Mengambil kata kunci pencarian yang sedang aktif
                    'search' => $this->tableSearch,
                    // Mengambil filter yang sedang aktif
                    'filters' => $this->tableFilters,
                ]))
                ->openUrlInNewTab(),
            CreateAction::make()->
                label('Tambah Pasar')
                ->color('primary')
                ->icon('heroicon-o-plus-circle'),
        ];
    }
}
