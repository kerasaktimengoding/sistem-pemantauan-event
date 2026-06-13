<?php

namespace App\Filament\Resources\Pedagangs\Pages;

use App\Filament\Resources\Pedagangs\PedagangResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;

class ListPedagangs extends ListRecords
{
    protected static string $resource = PedagangResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('download pdf')
                ->label('Export PDF')
                ->icon('heroicon-o-document')
                ->url(fn() => route('download9.tes9', [
                    // Mengambil kata kunci pencarian yang sedang aktif
                    'search' => $this->tableSearch,
                    // Mengambil filter yang sedang aktif
                    'filters' => $this->tableFilters,
                ]))
                ->openUrlInNewTab(),
            CreateAction::make()->
                label('Tambah Pedagang')
                ->color('primary')
                ->icon('heroicon-o-plus-circle'),
        ];
    }
}
