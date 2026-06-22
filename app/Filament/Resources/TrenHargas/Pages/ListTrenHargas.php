<?php

namespace App\Filament\Resources\TrenHargas\Pages;

use App\Filament\Resources\TrenHargas\TrenHargaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\TrenHarga;

class ListTrenHargas extends ListRecords
{
    protected static string $resource = TrenHargaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('download pdf')
                ->label('Export PDF')
                ->icon('heroicon-o-document')
                ->action(function ($livewire) {
                    $records = $livewire->getFilteredTableQuery()->get();

                    $pdf = Pdf::loadView("filament.TrenPDF", ["trens" => $records]);

                    $tanggal = now()->format('d-m-Y');
                    $namaFile = "Laporan-Tren-Harga-{$tanggal}.pdf";

                    return response()->streamDownload(
                        fn() => print ($pdf->output()),
                        $namaFile
                    );
                })
                ->openUrlInNewTab(),
            CreateAction::make()->
                label('Tambah Tren Harga')
                ->color('primary')
                ->icon('heroicon-o-plus-circle')
                ->modal(TrenHarga::class),
        ];
    }
}
