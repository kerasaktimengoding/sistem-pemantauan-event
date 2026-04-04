<?php

namespace App\Filament\Resources\TrenHargas\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class TrenHargaInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('komoditas_id')
                    ->numeric(),
                TextEntry::make('wilayah_id')
                    ->numeric(),
                TextEntry::make('periode_tren')
                    ->date(),
                TextEntry::make('harga_awal')
                    ->numeric(),
                TextEntry::make('harga_akhir')
                    ->numeric(),
                TextEntry::make('arah_tren'),
                TextEntry::make('persentase_perubahan')
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
