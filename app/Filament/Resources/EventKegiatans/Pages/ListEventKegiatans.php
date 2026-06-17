<?php

namespace App\Filament\Resources\EventKegiatans\Pages;

use App\Filament\Resources\EventKegiatans\EventKegiatanResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;
use Barryvdh\DomPDF\Facade\Pdf;

class ListEventKegiatans extends ListRecords
{
    protected static string $resource = EventKegiatanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('download pdf')
                ->label('Export PDF')
                ->icon('heroicon-o-document')
                ->action(function ($livewire) {
                    $records = $livewire->getFilteredTableQuery()->get();

                    $pdf = Pdf::loadView("filament.EventPDF", ["events" => $records]);

                    $tanggal = now()->format('d-m-Y');
                    $namaFile = "Laporan-Event-UMKM-{$tanggal}.pdf";

                    return response()->streamDownload(
                        fn() => print ($pdf->output()),
                        $namaFile
                    );
                })
                ->openUrlInNewTab(),
            CreateAction::make()->
                label('Tambah Event Kegiatan')
                ->color('primary')
                ->icon('heroicon-o-plus-circle'),
        ];
    }
}
