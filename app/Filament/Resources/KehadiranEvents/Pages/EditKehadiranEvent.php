<?php

namespace App\Filament\Resources\KehadiranEvents\Pages;

use App\Filament\Resources\KehadiranEvents\KehadiranEventResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditKehadiranEvent extends EditRecord
{
    protected static string $resource = KehadiranEventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
