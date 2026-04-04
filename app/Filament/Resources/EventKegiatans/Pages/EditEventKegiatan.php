<?php

namespace App\Filament\Resources\EventKegiatans\Pages;

use App\Filament\Resources\EventKegiatans\EventKegiatanResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditEventKegiatan extends EditRecord
{
    protected static string $resource = EventKegiatanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
