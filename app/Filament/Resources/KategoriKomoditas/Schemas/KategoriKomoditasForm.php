<?php

namespace App\Filament\Resources\KategoriKomoditas\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class KategoriKomoditasForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('kode_kategori')
                    ->required(),
                TextInput::make('nama_kategori')
                    ->required(),
            ]);
    }
}
