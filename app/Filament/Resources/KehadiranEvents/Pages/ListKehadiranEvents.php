<?php

namespace App\Filament\Resources\KehadiranEvents\Pages;

use App\Filament\Resources\KehadiranEvents\KehadiranEventResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;

class ListKehadiranEvents extends ListRecords
{
    protected static string $resource = KehadiranEventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('download pdf')
                ->label('Export PDF')
                ->icon('heroicon-o-document')
                ->url(fn() => route('download14.tes14', [
                    // Mengambil kata kunci pencarian yang sedang aktif
                    'search' => $this->tableSearch,
                    // Mengambil filter yang sedang aktif
                    'filters' => $this->tableFilters,
                ]))
                ->openUrlInNewTab(),
            CreateAction::make()->
                label('Tambah Kehadiran Event')
                ->color('primary')
                ->icon('heroicon-o-plus-circle'),
        ];
    }
}
