<?php

namespace App\Filament\Resources\RekapHargas\Pages;

use App\Filament\Resources\RekapHargas\RekapHargaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Filament\Actions\Action;
use Barryvdh\DomPDF\Facade\Pdf;

class ListRekapHargas extends ListRecords
{
    protected static string $resource = RekapHargaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('download pdf')
                ->label('Export PDF')
                ->icon('heroicon-o-document')
                ->action(function ($livewire) {
                    $records = $livewire->getFilteredTableQuery()->get();

                    $pdf = Pdf::loadView("filament.RekapPDF", ["rekaps" => $records]);

                    $tanggal = now()->format('d-m-Y');
                    $namaFile = "Laporan-Rekap-Harga-{$tanggal}.pdf";

                    return response()->streamDownload(
                        fn() => print ($pdf->output()),
                        $namaFile
                    );
                })
                ->openUrlInNewTab(),
            CreateAction::make()->
                label('Tambah Rekap Harga')
                ->color('primary')
                ->icon('heroicon-o-plus-circle'),
        ];
    }

    protected function modifyQueryUsing(Builder $query): Builder
    {
        return $query->with(['desa', 'komoditas']);
    }
}
