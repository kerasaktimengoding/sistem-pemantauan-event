<?php

namespace App\Filament\Resources\Wilayahs\Pages;

use App\Filament\Resources\Wilayahs\WilayahResource;

use Filament\Resources\Pages\ListRecords;

use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Wilayah;




class ListWilayahs extends ListRecords
{
    protected static string $resource = WilayahResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('download pdf')
                ->label('Export PDF')
                ->icon('heroicon-o-document')
                ->action(function ($livewire) {
                    $records = $livewire->getFilteredTableQuery()->get();

                    $pdf = Pdf::loadView("filament.WilayahPDF", ["wilayahs" => $records]);

                    $tanggal = now()->format('d-m-Y');
                    $namaFile = "Laporan-Wilayah-{$tanggal}.pdf";

                    return response()->streamDownload(
                        fn() => print ($pdf->output()),
                        $namaFile
                    );
                })
                ->openUrlInNewTab(),
            CreateAction::make()
                ->label('Tambah Wilayah')
                ->color('primary')
                ->modal(Wilayah::class)
                ->form(fn($form) => WilayahResource::form($form)->getComponents())
                
                ->icon('heroicon-o-plus-circle'),
        ];
    }

}
