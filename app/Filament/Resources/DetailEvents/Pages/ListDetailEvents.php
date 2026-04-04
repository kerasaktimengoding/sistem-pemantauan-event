<?php

namespace App\Filament\Resources\DetailEvents\Pages;

use App\Filament\Resources\DetailEvents\DetailEventResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDetailEvents extends ListRecords
{
    protected static string $resource = DetailEventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
