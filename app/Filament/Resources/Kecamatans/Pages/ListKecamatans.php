<?php

namespace App\Filament\Resources\Kecamatans\Pages;

use App\Filament\Resources\Kecamatans\KecamatanResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\kecamatan;

class ListKecamatans extends ListRecords
{
    protected static string $resource = KecamatanResource::class;

    protected function getHeaderActions(): array
    {
        return [

            Action::make('download pdf')
                ->label('Export PDF')
                ->icon('heroicon-o-document')
                ->action(function ($livewire) {
                    $records = $livewire->getFilteredTableQuery()->get();

                    $pdf = Pdf::loadView("filament.KecamatanPDF", ["kecamatans" => $records]);

                    // Membuat format tanggal-bulan-tahun saat ini (Contoh: 15-06-2026)
                    $tanggal = now()->format('d-m-Y');
                    $namaFile = "Laporan-Kecamatan-{$tanggal}.pdf";

                    return response()->streamDownload(
                        fn() => print ($pdf->output()),
                        $namaFile
                    );
                })
                ->openUrlInNewTab(),



            CreateAction::make()
                ->model(kecamatan::class)
                ->label('Tambah Kecamatan')
                ->icon('heroicon-o-plus-circle')
                ->color('primary'),
        ];
    }
}
