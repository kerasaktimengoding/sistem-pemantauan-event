<?php

namespace App\Filament\Resources\KehadiranEvents\Pages;

use App\Filament\Resources\KehadiranEvents\KehadiranEventResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListKehadiranEvents extends ListRecords
{
    protected static string $resource = KehadiranEventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
