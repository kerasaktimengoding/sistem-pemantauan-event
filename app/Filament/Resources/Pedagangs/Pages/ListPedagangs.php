<?php

namespace App\Filament\Resources\Pedagangs\Pages;

use App\Filament\Resources\Pedagangs\PedagangResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;
use Barryvdh\DomPDF\Facade\Pdf;

class ListPedagangs extends ListRecords
{
    protected static string $resource = PedagangResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('download pdf')
                ->label('Export PDF')
                ->icon('heroicon-o-document')
                ->action(function ($livewire) {
                    $records = $livewire->getFilteredTableQuery()->get();

                    $pdf = Pdf::loadView("filament.PedagangPDF", ["pedagangs" => $records]);

                    $tanggal = now()->format('d-m-Y');
                    $namaFile = "Laporan-Pedagang-{$tanggal}.pdf";

                    return response()->streamDownload(
                        fn() => print ($pdf->output()),
                        $namaFile
                    );
                })
                ->openUrlInNewTab(),
            CreateAction::make()->
                label('Tambah Pedagang')
                ->color('primary')
                ->icon('heroicon-o-plus-circle'),
        ];
    }
}
