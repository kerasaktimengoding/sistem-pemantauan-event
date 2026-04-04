<?php

namespace App\Filament\Resources\DetailEvents\Pages;

use App\Filament\Resources\DetailEvents\DetailEventResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditDetailEvent extends EditRecord
{
    protected static string $resource = DetailEventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
