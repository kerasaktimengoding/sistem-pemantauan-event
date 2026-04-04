<?php

namespace App\Filament\Resources\KategoriKomoditas\Pages;

use App\Filament\Resources\KategoriKomoditas\KategoriKomoditasResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewKategoriKomoditas extends ViewRecord
{
    protected static string $resource = KategoriKomoditasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
