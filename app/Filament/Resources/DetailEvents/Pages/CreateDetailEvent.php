<?php

namespace App\Filament\Resources\DetailEvents\Pages;

use App\Filament\Resources\DetailEvents\DetailEventResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDetailEvent extends CreateRecord
{
    protected static string $resource = DetailEventResource::class;
}
