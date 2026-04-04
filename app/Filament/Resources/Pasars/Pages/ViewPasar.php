<?php

namespace App\Filament\Resources\Pasars\Pages;

use App\Filament\Resources\Pasars\PasarResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPasar extends ViewRecord
{
    protected static string $resource = PasarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
