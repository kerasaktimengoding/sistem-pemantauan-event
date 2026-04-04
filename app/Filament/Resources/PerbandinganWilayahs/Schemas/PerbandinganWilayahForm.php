<?php

namespace App\Filament\Resources\PerbandinganWilayahs\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class PerbandinganWilayahForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('kode_perbandingan')
                    ->required(),
                TextInput::make('komoditas_id')
                    ->required()
                    ->numeric(),
                TextInput::make('wilayah_1_id')
                    ->required()
                    ->numeric(),
                TextInput::make('wilayah_2_id')
                    ->required()
                    ->numeric(),
                TextInput::make('harga_wilayah_1')
                    ->required()
                    ->numeric(),
                TextInput::make('harga_wilayah_2')
                    ->required()
                    ->numeric(),
                TextInput::make('selisih_harga')
                    ->required()
                    ->numeric(),
                Textarea::make('keterangan')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }
}
