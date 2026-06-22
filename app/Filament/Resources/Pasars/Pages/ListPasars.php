<?php

namespace App\Filament\Resources\Pasars\Pages;

use App\Filament\Resources\Pasars\PasarResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Pasar;

class ListPasars extends ListRecords
{
    protected static string $resource = PasarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('download pdf')
                ->label('Export PDF')
                ->icon('heroicon-o-document')
                ->action(function ($livewire) {
                    $records = $livewire->getFilteredTableQuery()->get();

                    $pdf = Pdf::loadView("filament.PasarPDF", ["pasars" => $records]);

                    $tanggal = now()->format('d-m-Y');
                    $namaFile = "Laporan-Pasar-{$tanggal}.pdf";

                    return response()->streamDownload(
                        fn() => print ($pdf->output()),
                        $namaFile
                    );
                })
                ->openUrlInNewTab(),
            CreateAction::make()->
                label('Tambah Pasar')
                ->color('primary')
                ->icon('heroicon-o-plus-circle')
                ->modal(Pasar::class),
        ];
    }
}
