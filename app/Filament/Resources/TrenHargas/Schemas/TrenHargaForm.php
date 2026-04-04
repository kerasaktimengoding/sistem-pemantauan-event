<?php

namespace App\Filament\Resources\TrenHargas\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TrenHargaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('komoditas_id')
                    ->required()
                    ->numeric(),
                TextInput::make('wilayah_id')
                    ->required()
                    ->numeric(),
                DatePicker::make('periode_tren')
                    ->required(),
                TextInput::make('harga_awal')
                    ->required()
                    ->numeric(),
                TextInput::make('harga_akhir')
                    ->required()
                    ->numeric(),
                TextInput::make('arah_tren')
                    ->required(),
                TextInput::make('persentase_perubahan')
                    ->required()
                    ->numeric(),
            ]);
    }
}
