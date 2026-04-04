<?php

namespace App\Filament\Resources\TrenHargas\Pages;

use App\Filament\Resources\TrenHargas\TrenHargaResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditTrenHarga extends EditRecord
{
    protected static string $resource = TrenHargaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
