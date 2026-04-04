<?php

namespace App\Filament\Resources\KehadiranEvents\Pages;

use App\Filament\Resources\KehadiranEvents\KehadiranEventResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewKehadiranEvent extends ViewRecord
{
    protected static string $resource = KehadiranEventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
