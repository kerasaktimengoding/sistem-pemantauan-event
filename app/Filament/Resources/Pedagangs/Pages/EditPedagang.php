<?php

namespace App\Filament\Resources\Pedagangs\Pages;

use App\Filament\Resources\Pedagangs\PedagangResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditPedagang extends EditRecord
{
    protected static string $resource = PedagangResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
