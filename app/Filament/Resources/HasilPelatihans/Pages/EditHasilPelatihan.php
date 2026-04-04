<?php

namespace App\Filament\Resources\HasilPelatihans\Pages;

use App\Filament\Resources\HasilPelatihans\HasilPelatihanResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditHasilPelatihan extends EditRecord
{
    protected static string $resource = HasilPelatihanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
