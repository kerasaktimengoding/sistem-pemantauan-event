<?php

namespace App\Filament\Resources\HasilPelatihans\Pages;

use App\Filament\Resources\HasilPelatihans\HasilPelatihanResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListHasilPelatihans extends ListRecords
{
    protected static string $resource = HasilPelatihanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
