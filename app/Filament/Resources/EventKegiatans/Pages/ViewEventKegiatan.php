<?php

namespace App\Filament\Resources\EventKegiatans\Pages;

use App\Filament\Resources\EventKegiatans\EventKegiatanResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewEventKegiatan extends ViewRecord
{
    protected static string $resource = EventKegiatanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
