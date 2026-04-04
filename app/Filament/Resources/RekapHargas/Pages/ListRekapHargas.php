<?php

namespace App\Filament\Resources\RekapHargas\Pages;

use App\Filament\Resources\RekapHargas\RekapHargaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRekapHargas extends ListRecords
{
    protected static string $resource = RekapHargaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
