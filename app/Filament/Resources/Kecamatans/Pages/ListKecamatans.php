<?php

namespace App\Filament\Resources\Kecamatans\Pages;

use App\Filament\Resources\Kecamatans\KecamatanResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;

class ListKecamatans extends ListRecords
{
    protected static string $resource = KecamatanResource::class;

    protected function getHeaderActions(): array
    {
        return [

            Action::make('download pdf')
            ->label('Export PDF')
            ->icon('heroicon-o-document')
            ->url(fn () => route('download1.tes1', [
                // Mengambil kata kunci pencarian yang sedang aktif
                'search' => $this->tableSearch,
                // Mengambil filter yang sedang aktif
                'filters' => $this->tableFilters,
            ]))
            ->openUrlInNewTab(),



            CreateAction::make()->
                label('Tambah Kecamatan')
                ->color('primary')
                ->icon('heroicon-o-plus-circle'),
        ];
    }
}
