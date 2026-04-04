<?php

namespace App\Filament\Resources\Komoditas\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class KomoditasInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('kode_komoditas'),
                TextEntry::make('nama_komoditas'),
                TextEntry::make('kategori_id')
                    ->numeric(),
                TextEntry::make('satuan_id')
                    ->numeric(),
                TextEntry::make('deskripsi')
                    ->columnSpanFull(),
                TextEntry::make('status_komoditas'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
