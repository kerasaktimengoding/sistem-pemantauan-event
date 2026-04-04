<?php

namespace App\Filament\Resources\PerbandinganWilayahs\Pages;

use App\Filament\Resources\PerbandinganWilayahs\PerbandinganWilayahResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPerbandinganWilayah extends ViewRecord
{
    protected static string $resource = PerbandinganWilayahResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
