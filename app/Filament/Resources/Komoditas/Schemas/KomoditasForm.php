<?php

namespace App\Filament\Resources\Komoditas\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class KomoditasForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('kode_komoditas')
                    ->required(),
                TextInput::make('nama_komoditas')
                    ->required(),
                TextInput::make('kategori_id')
                    ->required()
                    ->numeric(),
                TextInput::make('satuan_id')
                    ->required()
                    ->numeric(),
                Textarea::make('deskripsi')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('status_komoditas')
                    ->required(),
            ]);
    }
}
