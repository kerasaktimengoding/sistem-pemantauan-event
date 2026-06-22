<?php

namespace App\Filament\Resources\PesertaEvents\Pages;

use App\Filament\Resources\PesertaEvents\PesertaEventResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\PesertaEvent;

class ListPesertaEvents extends ListRecords
{
    protected static string $resource = PesertaEventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('download pdf')
                ->label('Export PDF')
                ->icon('heroicon-o-document')
                ->action(function ($livewire) {
                    $records = $livewire->getFilteredTableQuery()->get();

                    $pdf = Pdf::loadView("filament.PesertaPDF", ["pesertas" => $records]);

                    $tanggal = now()->format('d-m-Y');
                    $namaFile = "Laporan-Peserta-Event-{$tanggal}.pdf";

                    return response()->streamDownload(
                        fn() => print ($pdf->output()),
                        $namaFile
                    );
                })
                ->openUrlInNewTab(),
            CreateAction::make()->
                label('Tambah Peserta Event')
                ->color('primary')
                ->icon('heroicon-o-plus-circle')
                ->modal(PesertaEvent::class),
        ];
    }
}
