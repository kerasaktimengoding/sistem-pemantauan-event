<?php

namespace App\Filament\Resources\Jabatans\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class JabatanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('kode_jabatan')
                    ->required(),
                TextInput::make('nama_jabatan')
                    ->required(),
                Textarea::make('tugas_pokok')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('status_jabatan')
                    ->required(),
            ]);
    }
}
