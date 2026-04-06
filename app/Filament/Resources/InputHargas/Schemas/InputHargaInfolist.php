<?php

namespace App\Filament\Resources\InputHargas\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class InputHargaInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('kode_input_harga'),
                TextEntry::make('komoditas_id')
                    ->numeric(),
                TextEntry::make('wilayah_id')
                    ->numeric(),
                TextEntry::make('pasar_id')
                    ->numeric(),
                TextEntry::make('pedagang_id')
                    ->numeric(),
                TextEntry::make('pegawai_id')
                    ->numeric(),
                TextEntry::make('harga')
                    ->numeric(),
                TextEntry::make('tanggal_input')
                    ->date(),
                TextEntry::make('sumber_data'),
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
