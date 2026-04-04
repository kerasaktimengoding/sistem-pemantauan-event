<?php

namespace App\Filament\Resources\PesertaEvents\Pages;

use App\Filament\Resources\PesertaEvents\PesertaEventResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPesertaEvent extends ViewRecord
{
    protected static string $resource = PesertaEventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
