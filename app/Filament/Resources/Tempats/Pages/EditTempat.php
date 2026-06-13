<?php

namespace App\Filament\Resources\Tempats\Pages;

use App\Filament\Resources\Tempats\TempatResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditTempat extends EditRecord
{
    protected static string $resource = TempatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
