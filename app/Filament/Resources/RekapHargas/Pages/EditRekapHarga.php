<?php

namespace App\Filament\Resources\RekapHargas\Pages;

use App\Filament\Resources\RekapHargas\RekapHargaResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditRekapHarga extends EditRecord
{
    protected static string $resource = RekapHargaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
