<?php

namespace App\Filament\Resources\Pedagangs\Pages;

use App\Filament\Resources\Pedagangs\PedagangResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPedagang extends ViewRecord
{
    protected static string $resource = PedagangResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
