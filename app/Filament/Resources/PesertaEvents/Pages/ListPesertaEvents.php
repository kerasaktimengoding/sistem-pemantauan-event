<?php

namespace App\Filament\Resources\PesertaEvents\Pages;

use App\Filament\Resources\PesertaEvents\PesertaEventResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPesertaEvents extends ListRecords
{
    protected static string $resource = PesertaEventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
