<?php

namespace App\Filament\Resources\RekapHargas\Pages;

use App\Filament\Resources\RekapHargas\RekapHargaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Filament\Actions\Action;

class ListRekapHargas extends ListRecords
{
    protected static string $resource = RekapHargaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('download pdf')
                ->label('Export PDF')
                ->icon('heroicon-o-document')
                ->url(fn() => route('download17.tes17', [
                    // Mengambil kata kunci pencarian yang sedang aktif
                    'search' => $this->tableSearch,
                    // Mengambil filter yang sedang aktif
                    'filters' => $this->tableFilters,
                ]))
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
