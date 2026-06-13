<?php

namespace App\Filament\Resources\Pegawais\Pages;

use App\Filament\Resources\Pegawais\PegawaiResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;

class ListPegawais extends ListRecords
{
    protected static string $resource = PegawaiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('download pdf')
            ->label('Export PDF')
            ->icon('heroicon-o-document')
            ->url(fn () => route('download5.tes5', [
                // Mengambil kata kunci pencarian yang sedang aktif
                'search' => $this->tableSearch,
                // Mengambil filter yang sedang aktif
                'filters' => $this->tableFilters,
            ]))
            ->openUrlInNewTab(),
             CreateAction::make()->
                label('Tambah Pegawai')
                ->color('primary')
                ->icon('heroicon-o-plus-circle'),
        ];
    }
}
