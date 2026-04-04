<?php

namespace App\Filament\Resources\KategoriKomoditas\Pages;

use App\Filament\Resources\KategoriKomoditas\KategoriKomoditasResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListKategoriKomoditas extends ListRecords
{
    protected static string $resource = KategoriKomoditasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
