<?php

namespace App\Filament\Resources\Pasars\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PasarInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('kode_pasar'),
                TextEntry::make('nama_pasar'),
                TextEntry::make('wilayah_id')
                    ->numeric(),
                TextEntry::make('alamat_pasar')
                    ->columnSpanFull(),
                TextEntry::make('status_pasar'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
