<?php

namespace App\Filament\Resources\HasilPelatihans\Pages;

use App\Filament\Resources\HasilPelatihans\HasilPelatihanResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewHasilPelatihan extends ViewRecord
{
    protected static string $resource = HasilPelatihanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
