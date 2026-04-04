<?php

namespace App\Filament\Resources\EventKegiatans\Pages;

use App\Filament\Resources\EventKegiatans\EventKegiatanResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEventKegiatans extends ListRecords
{
    protected static string $resource = EventKegiatanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
