<?php

namespace App\Filament\Resources\Jabatans\Pages;

use App\Filament\Resources\Jabatans\JabatanResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Jabatan;

class ListJabatans extends ListRecords
{
    protected static string $resource = JabatanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('download pdf')
                ->label('Export PDF')
                ->icon('heroicon-o-document')
                ->action(function ($livewire) {
                    $records = $livewire->getFilteredTableQuery()->get();

                    $pdf = Pdf::loadView("filament.JabatanPDF", ["jabatans" => $records]);

                    $tanggal = now()->format('d-m-Y');
                    $namaFile = "Laporan-Jabatan-{$tanggal}.pdf";

                    return response()->streamDownload(
                        fn() => print ($pdf->output()),
                        $namaFile
                    );
                })
                ->openUrlInNewTab(),
            CreateAction::make()
                ->label('Tambah Jabatan')
                ->color('primary')
                ->icon('heroicon-o-plus-circle')
                ->modal(Jabatan::class)
                // Mengambil komponen form dari JabatanResource secara otomatis
                ->form(fn($form) => JabatanResource::form($form)->getComponents())
                ->modalWidth('lg'),
        ];
    }
}
