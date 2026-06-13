<?php

namespace App\Filament\Resources\DetailEvents\Pages;

use App\Filament\Resources\DetailEvents\DetailEventResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;

class ListDetailEvents extends ListRecords
{
    protected static string $resource = DetailEventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('download pdf')
                ->label('Export PDF')
                ->icon('heroicon-o-document')
                ->url(fn() => route('download16.tes16', [
                    // Mengambil kata kunci pencarian yang sedang aktif
                    'search' => $this->tableSearch,
                    // Mengambil filter yang sedang aktif
                    'filters' => $this->tableFilters,
                ]))
                ->openUrlInNewTab(),
            CreateAction::make()->
                label('Tambah Detail Event')
                ->color('primary')
                ->icon('heroicon-o-plus-circle'),
        ];
    }
}
