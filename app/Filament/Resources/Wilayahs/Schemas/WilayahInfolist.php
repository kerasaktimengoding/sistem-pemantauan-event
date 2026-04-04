<?php

namespace App\Filament\Resources\Wilayahs\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class WilayahInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('kode_wilayah'),
                TextEntry::make('nama_wilayah'),
                TextEntry::make('tipe_wilayah'),
                TextEntry::make('kode_pos'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
