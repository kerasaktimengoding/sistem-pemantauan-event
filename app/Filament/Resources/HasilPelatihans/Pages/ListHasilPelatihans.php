<?php

namespace App\Filament\Resources\HasilPelatihans\Pages;

use App\Filament\Resources\HasilPelatihans\HasilPelatihanResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;

class ListHasilPelatihans extends ListRecords
{
    protected static string $resource = HasilPelatihanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('download pdf')
                ->label('Export PDF')
                ->icon('heroicon-o-document')
                ->url(fn() => route('download15.tes15', [
                    // Mengambil kata kunci pencarian yang sedang aktif
                    'search' => $this->tableSearch,
                    // Mengambil filter yang sedang aktif
                    'filters' => $this->tableFilters,
                ]))
                ->openUrlInNewTab(),
            CreateAction::make()->
                label('Tambah Hasil Pelatihan')
                ->color('primary')
                ->icon('heroicon-o-plus-circle'),
        ];
    }
}
