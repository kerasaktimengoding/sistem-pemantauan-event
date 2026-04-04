<?php

namespace App\Filament\Resources\TrenHargas\Pages;

use App\Filament\Resources\TrenHargas\TrenHargaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTrenHargas extends ListRecords
{
    protected static string $resource = TrenHargaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
