<?php

namespace App\Filament\Resources\JadwalMonitorings\Pages;

use App\Filament\Resources\JadwalMonitorings\JadwalMonitoringResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;

class ListJadwalMonitorings extends ListRecords
{
    protected static string $resource = JadwalMonitoringResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('download pdf')
                ->label('Export PDF')
                ->icon('heroicon-o-document')
                ->url(fn() => route('download10.tes10', [
                    // Mengambil kata kunci pencarian yang sedang aktif
                    'search' => $this->tableSearch,
                    // Mengambil filter yang sedang aktif
                    'filters' => $this->tableFilters,
                ]))
                ->openUrlInNewTab(),
            CreateAction::make()->
                label('Tambah Jadwal Monitoring')
                ->color('primary')
                ->icon('heroicon-o-plus-circle'),
        ];
    }
}
