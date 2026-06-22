<?php

namespace App\Filament\Resources\KehadiranEvents\Pages;

use App\Filament\Resources\KehadiranEvents\KehadiranEventResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\KehadiranEvent;

class ListKehadiranEvents extends ListRecords
{
    protected static string $resource = KehadiranEventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('download pdf')
                ->label('Export PDF')
                ->icon('heroicon-o-document')
                ->action(function ($livewire) {
                    $records = $livewire->getFilteredTableQuery()->get();

                    $pdf = Pdf::loadView("filament.KehadiranPDF", ["kehadirans" => $records]);

                    $tanggal = now()->format('d-m-Y');
                    $namaFile = "Laporan-Kehadiran-Event-{$tanggal}.pdf";

                    return response()->streamDownload(
                        fn() => print ($pdf->output()),
                        $namaFile
                    );
                })
                ->openUrlInNewTab(),
            CreateAction::make()->
                label('Tambah Kehadiran Event')
                ->color('primary')
                ->icon('heroicon-o-plus-circle')
                ->modal(KehadiranEvent::class),
        ];
    }
}
