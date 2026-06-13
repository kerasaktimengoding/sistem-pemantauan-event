<?php

namespace App\Filament\Resources\Tempats\Pages;

use App\Filament\Resources\Tempats\TempatResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewTempat extends ViewRecord
{
    protected static string $resource = TempatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
