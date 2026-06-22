<?php

namespace App\Filament\Resources\JadwalMonitorings\Pages;

use App\Filament\Resources\JadwalMonitorings\JadwalMonitoringResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\JadwalMonitoring;

class ListJadwalMonitorings extends ListRecords
{
    protected static string $resource = JadwalMonitoringResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('download pdf')
                ->label('Export PDF')
                ->icon('heroicon-o-document')
                ->action(function ($livewire) {
                    $records = $livewire->getFilteredTableQuery()->get();

                    $pdf = Pdf::loadView("filament.JadwalPDF", ["jadwals" => $records]);

                    $tanggal = now()->format('d-m-Y');
                    $namaFile = "Laporan-Jadwal-Monitoring-{$tanggal}.pdf";

                    return response()->streamDownload(
                        fn() => print ($pdf->output()),
                        $namaFile
                    );
                })
                ->openUrlInNewTab(),
            CreateAction::make()->
                label('Tambah Jadwal Monitoring')
                ->color('primary')
                ->icon('heroicon-o-plus-circle')
                ->modal(JadwalMonitoring::class),
        ];
    }
}
