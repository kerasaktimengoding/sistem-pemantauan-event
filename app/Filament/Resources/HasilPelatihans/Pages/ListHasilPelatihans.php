<?php

namespace App\Filament\Resources\HasilPelatihans\Pages;

use App\Filament\Resources\HasilPelatihans\HasilPelatihanResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;
use Barryvdh\DomPDF\Facade\Pdf;

class ListHasilPelatihans extends ListRecords
{
    protected static string $resource = HasilPelatihanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('download pdf')
                ->label('Export PDF')
                ->icon('heroicon-o-document')
                ->action(function ($livewire) {
                    $records = $livewire->getFilteredTableQuery()->get();

                    $pdf = Pdf::loadView("filament.HasilPDF", ["hasils" => $records]);

                    $tanggal = now()->format('d-m-Y');
                    $namaFile = "Laporan-Hasil-Pelatihan-{$tanggal}.pdf";

                    return response()->streamDownload(
                        fn() => print ($pdf->output()),
                        $namaFile
                    );
                })
                ->openUrlInNewTab(),
            CreateAction::make()->
                label('Tambah Hasil Pelatihan')
                ->color('primary')
                ->icon('heroicon-o-plus-circle'),
        ];
    }
}
