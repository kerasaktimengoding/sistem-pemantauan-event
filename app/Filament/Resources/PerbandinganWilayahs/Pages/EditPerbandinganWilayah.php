<?php

namespace App\Filament\Resources\PerbandinganWilayahs\Pages;

use App\Filament\Resources\PerbandinganWilayahs\PerbandinganWilayahResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditPerbandinganWilayah extends EditRecord
{
    protected static string $resource = PerbandinganWilayahResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
