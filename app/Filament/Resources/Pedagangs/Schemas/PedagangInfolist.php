<?php

namespace App\Filament\Resources\Pedagangs\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PedagangInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('nik'),
                TextEntry::make('kode_pedagang'),
                TextEntry::make('nama_pedagang'),
                TextEntry::make('jenis_tempat'),
                TextEntry::make('no_hp'),
                TextEntry::make('alamat')
                    ->columnSpanFull(),
                TextEntry::make('wilayah_id')
                    ->numeric(),
                TextEntry::make('status_pedagang'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
