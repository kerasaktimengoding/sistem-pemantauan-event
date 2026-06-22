<?php

namespace App\Filament\Resources\InputHargas\Pages;

use App\Filament\Resources\InputHargas\InputHargaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\InputHarga;

class ListInputHargas extends ListRecords
{
    protected static string $resource = InputHargaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('download pdf')
                ->label('Export PDF')
                ->icon('heroicon-o-document')
                ->action(function ($livewire) {
                    $records = $livewire->getFilteredTableQuery()->get();

                    $pdf = Pdf::loadView("filament.InputPDF", ["inputs" => $records]);

                    $tanggal = now()->format('d-m-Y');
                    $namaFile = "Laporan-Input-Harga-{$tanggal}.pdf";

                    return response()->streamDownload(
                        fn() => print ($pdf->output()),
                        $namaFile
                    );
                })
                ->openUrlInNewTab(),
            CreateAction::make()->
                label('Tambah Harga')
                ->color('primary')
                ->icon('heroicon-o-plus-circle')
                ->modal(InputHarga::class),
        ];
    }
}
