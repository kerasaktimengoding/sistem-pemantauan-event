<?php

namespace App\Filament\Resources\PerbandinganWilayahs\Pages;

use App\Filament\Resources\PerbandinganWilayahs\PerbandinganWilayahResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPerbandinganWilayahs extends ListRecords
{
    protected static string $resource = PerbandinganWilayahResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
