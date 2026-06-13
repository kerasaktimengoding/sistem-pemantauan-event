<?php

namespace App\Filament\Resources\JadwalMonitorings\Pages;

use App\Filament\Resources\JadwalMonitorings\JadwalMonitoringResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewJadwalMonitoring extends ViewRecord
{
    protected static string $resource = JadwalMonitoringResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
