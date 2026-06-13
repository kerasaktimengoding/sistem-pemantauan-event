<?php

namespace App\Filament\Resources\Tempats\Pages;

use App\Filament\Resources\Tempats\TempatResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;

class ListTempats extends ListRecords
{
    protected static string $resource = TempatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('download pdf')
                ->label('Export PDF')
                ->icon('heroicon-o-document')
                ->url(fn() => route('download6.tes6', [
                    // Mengambil kata kunci pencarian yang sedang aktif
                    'search' => $this->tableSearch,
                    // Mengambil filter yang sedang aktif
                    'filters' => $this->tableFilters,
                ]))
                ->openUrlInNewTab(),
            CreateAction::make()->
                label('Tambah Tempat')
                ->color('primary')
                ->icon('heroicon-o-plus-circle'),
        ];
    }
}
