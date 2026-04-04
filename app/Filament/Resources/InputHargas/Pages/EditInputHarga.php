<?php

namespace App\Filament\Resources\InputHargas\Pages;

use App\Filament\Resources\InputHargas\InputHargaResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditInputHarga extends EditRecord
{
    protected static string $resource = InputHargaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
