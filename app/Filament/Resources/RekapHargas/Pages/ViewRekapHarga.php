<?php

namespace App\Filament\Resources\RekapHargas\Pages;

use App\Filament\Resources\RekapHargas\RekapHargaResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewRekapHarga extends ViewRecord
{
    protected static string $resource = RekapHargaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
