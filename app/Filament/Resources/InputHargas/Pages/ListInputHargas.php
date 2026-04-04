<?php

namespace App\Filament\Resources\InputHargas\Pages;

use App\Filament\Resources\InputHargas\InputHargaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListInputHargas extends ListRecords
{
    protected static string $resource = InputHargaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
