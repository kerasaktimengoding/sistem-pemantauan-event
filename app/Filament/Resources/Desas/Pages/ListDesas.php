<?php

namespace App\Filament\Resources\Desas\Pages;

use App\Filament\Resources\Desas\DesaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\desa;

class ListDesas extends ListRecords
{
    protected static string $resource = DesaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('download pdf')
                ->label('Export PDF')
                ->icon('heroicon-o-document')
                ->action(function ($livewire) {
                    $records = $livewire->getFilteredTableQuery()->get();

                    $pdf = Pdf::loadView("filament.DesaPDF", ["desas" => $records]);

                    $tanggal = now()->format('d-m-Y');
                    $namaFile = "Laporan-Desa-{$tanggal}.pdf";

                    return response()->streamDownload(
                        fn() => print ($pdf->output()),
                        $namaFile
                    );
                })
                ->openUrlInNewTab(),
            CreateAction::make()
                ->model(desa::class)
                ->label('Tambah Desa')
                ->color('primary')
                ->icon('heroicon-o-plus-circle'),
        ];
    }
}
