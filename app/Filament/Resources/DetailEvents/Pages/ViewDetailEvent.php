<?php

namespace App\Filament\Resources\DetailEvents\Pages;

use App\Filament\Resources\DetailEvents\DetailEventResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewDetailEvent extends ViewRecord
{
    protected static string $resource = DetailEventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
