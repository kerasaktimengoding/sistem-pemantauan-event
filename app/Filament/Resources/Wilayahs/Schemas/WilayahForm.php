<?php

namespace App\Filament\Resources\Wilayahs\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class WilayahForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('kode_wilayah')
                    ->required(),
                TextInput::make('nama_wilayah')
                    ->required(),
                TextInput::make('tipe_wilayah')
                    ->required(),
                TextInput::make('kode_pos')
                    ->required(),
            ]);
    }
}
