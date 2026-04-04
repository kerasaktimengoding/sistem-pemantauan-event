<?php

namespace App\Filament\Resources\Komoditas\Pages;

use App\Filament\Resources\Komoditas\KomoditasResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewKomoditas extends ViewRecord
{
    protected static string $resource = KomoditasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
