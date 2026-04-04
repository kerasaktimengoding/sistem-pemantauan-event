<?php

namespace App\Filament\Resources\PerbandinganWilayahs\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PerbandinganWilayahInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('kode_perbandingan'),
                TextEntry::make('komoditas_id')
                    ->numeric(),
                TextEntry::make('wilayah_1_id')
                    ->numeric(),
                TextEntry::make('wilayah_2_id')
                    ->numeric(),
                TextEntry::make('harga_wilayah_1')
                    ->numeric(),
                TextEntry::make('harga_wilayah_2')
                    ->numeric(),
                TextEntry::make('selisih_harga')
                    ->numeric(),
                TextEntry::make('keterangan')
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
