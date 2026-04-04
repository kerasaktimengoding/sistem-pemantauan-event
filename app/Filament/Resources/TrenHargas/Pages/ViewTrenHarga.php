<?php

namespace App\Filament\Resources\TrenHargas\Pages;

use App\Filament\Resources\TrenHargas\TrenHargaResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewTrenHarga extends ViewRecord
{
    protected static string $resource = TrenHargaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
