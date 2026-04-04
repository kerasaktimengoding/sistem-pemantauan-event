<?php

namespace App\Filament\Resources\RekapHargas\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class RekapHargaInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('kode_rekap_harga'),
                TextEntry::make('komoditas_id')
                    ->numeric(),
                TextEntry::make('wilayah_id')
                    ->numeric(),
                TextEntry::make('periode_rekap')
                    ->date(),
                TextEntry::make('harga_rata_rata')
                    ->numeric(),
                TextEntry::make('harga_maksimum')
                    ->numeric(),
                TextEntry::make('harga_minimum')
                    ->numeric(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
