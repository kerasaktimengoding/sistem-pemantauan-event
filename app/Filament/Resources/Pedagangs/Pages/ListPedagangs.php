<?php

namespace App\Filament\Resources\Pedagangs\Pages;

use App\Filament\Resources\Pedagangs\PedagangResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPedagangs extends ListRecords
{
    protected static string $resource = PedagangResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
