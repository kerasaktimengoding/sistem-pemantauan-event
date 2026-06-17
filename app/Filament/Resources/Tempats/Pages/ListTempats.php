<?php

namespace App\Filament\Resources\Tempats\Pages;

use App\Filament\Resources\Tempats\TempatResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;
use Barryvdh\DomPDF\Facade\Pdf;

class ListTempats extends ListRecords
{
    protected static string $resource = TempatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('download pdf')
                ->label('Export PDF')
                ->icon('heroicon-o-document')
                ->action(function ($livewire) {
                    $records = $livewire->getFilteredTableQuery()->get();

                    $pdf = Pdf::loadView("filament.TempatPDF", ["tempats" => $records]);

                    $tanggal = now()->format('d-m-Y');
                    $namaFile = "Laporan-Tempat-Usaha-{$tanggal}.pdf";

                    return response()->streamDownload(
                        fn() => print ($pdf->output()),
                        $namaFile
                    );
                })
                ->openUrlInNewTab(),
            CreateAction::make()->
                label('Tambah Tempat')
                ->color('primary')
                ->icon('heroicon-o-plus-circle'),
        ];
    }
}
