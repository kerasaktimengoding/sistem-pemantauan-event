<?php

namespace App\Filament\Resources\PerbandinganWilayahs\Pages;

use App\Filament\Resources\PerbandinganWilayahs\PerbandinganWilayahResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;
use Barryvdh\DomPDF\Facade\Pdf;

class ListPerbandinganWilayahs extends ListRecords
{
    protected static string $resource = PerbandinganWilayahResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('download pdf')
                ->label('Export PDF')
                ->icon('heroicon-o-document')
                ->action(function ($livewire) {
                    $records = $livewire->getFilteredTableQuery()->get();

                    $pdf = Pdf::loadView("filament.PerbandinganPDF", ["perbandingans" => $records]);

                    $tanggal = now()->format('d-m-Y');
                    $namaFile = "Laporan-Perbandingan-Harga-{$tanggal}.pdf";

                    return response()->streamDownload(
                        fn() => print ($pdf->output()),
                        $namaFile
                    );
                })
                ->openUrlInNewTab(),
            CreateAction::make()->
                label('Tambah Perbandingan Wilayah')
                ->color('primary')
                ->icon('heroicon-o-plus-circle'),
        ];
    }
}
