<?php

namespace App\Filament\Resources\Satuans\Pages;

use App\Filament\Resources\Satuans\SatuanResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewSatuan extends ViewRecord
{
    protected static string $resource = SatuanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
