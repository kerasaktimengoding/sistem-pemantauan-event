<?php

namespace App\Filament\Resources\Desas\Pages;

use App\Filament\Resources\Desas\DesaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;

class ListDesas extends ListRecords
{
    protected static string $resource = DesaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('download pdf')
            ->label('Export PDF')
            ->icon('heroicon-o-document')
            ->url(fn () => route('download1.tes2', [
                // Mengambil kata kunci pencarian yang sedang aktif
                'search' => $this->tableSearch,
                // Mengambil filter yang sedang aktif
                'filters' => $this->tableFilters,
            ]))
            ->openUrlInNewTab(),
             CreateAction::make()->
                label('Tambah Desa')
                ->color('primary')
                ->icon('heroicon-o-plus-circle'),
        ];
    }
}
