<?php

namespace App\Filament\Resources\InputHargas\Pages;

use App\Filament\Resources\InputHargas\InputHargaResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewInputHarga extends ViewRecord
{
    protected static string $resource = InputHargaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
