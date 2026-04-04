<?php

namespace App\Filament\Resources\RekapHargas\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class RekapHargaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('kode_rekap_harga')
                    ->required(),
                TextInput::make('komoditas_id')
                    ->required()
                    ->numeric(),
                TextInput::make('wilayah_id')
                    ->required()
                    ->numeric(),
                DatePicker::make('periode_rekap')
                    ->required(),
                TextInput::make('harga_rata_rata')
                    ->required()
                    ->numeric(),
                TextInput::make('harga_maksimum')
                    ->required()
                    ->numeric(),
                TextInput::make('harga_minimum')
                    ->required()
                    ->numeric(),
            ]);
    }
}
