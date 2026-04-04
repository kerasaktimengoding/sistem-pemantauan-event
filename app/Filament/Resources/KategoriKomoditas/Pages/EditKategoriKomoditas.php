<?php

namespace App\Filament\Resources\KategoriKomoditas\Pages;

use App\Filament\Resources\KategoriKomoditas\KategoriKomoditasResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditKategoriKomoditas extends EditRecord
{
    protected static string $resource = KategoriKomoditasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
