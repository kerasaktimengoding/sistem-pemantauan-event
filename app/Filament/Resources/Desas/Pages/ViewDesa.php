<?php

namespace App\Filament\Resources\Desas\Pages;

use App\Filament\Resources\Desas\DesaResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewDesa extends ViewRecord
{
    protected static string $resource = DesaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
