<?php

namespace App\Filament\Resources\JadwalMonitorings\Pages;

use App\Filament\Resources\JadwalMonitorings\JadwalMonitoringResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditJadwalMonitoring extends EditRecord
{
    protected static string $resource = JadwalMonitoringResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
