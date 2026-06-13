<?php

namespace App\Filament\Resources\EventKegiatans\Pages;

use App\Filament\Resources\EventKegiatans\EventKegiatanResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;

class ListEventKegiatans extends ListRecords
{
    protected static string $resource = EventKegiatanResource::class;

    protected function getHeaderActions(): array
    {
        return [
             Action::make('download pdf')
                ->label('Export PDF')
                ->icon('heroicon-o-document')
                ->url(fn() => route('download12.tes12', [
                    // Mengambil kata kunci pencarian yang sedang aktif
                    'search' => $this->tableSearch,
                    // Mengambil filter yang sedang aktif
                    'filters' => $this->tableFilters,
                ]))
                ->openUrlInNewTab(),
            CreateAction::make()->
                label('Tambah Event Kegiatan')
                ->color('primary')
                ->icon('heroicon-o-plus-circle'),
        ];
    }
}
