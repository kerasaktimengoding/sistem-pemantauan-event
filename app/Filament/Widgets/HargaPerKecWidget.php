<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class HargaPerKecWidget extends ChartWidget
{
    protected ?string $heading = 'Harga Per Kec Widget';

    protected function getData(): array
    {
        return [
            //
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
