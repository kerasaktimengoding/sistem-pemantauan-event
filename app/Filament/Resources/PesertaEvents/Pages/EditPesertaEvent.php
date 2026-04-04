<?php

namespace App\Filament\Resources\PesertaEvents\Pages;

use App\Filament\Resources\PesertaEvents\PesertaEventResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditPesertaEvent extends EditRecord
{
    protected static string $resource = PesertaEventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
