<?php

namespace App\Filament\Resources\DetailEvents\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class DetailEventInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('event_id')
                    ->numeric(),
                TextEntry::make('kode_detail_event'),
                TextEntry::make('deskripsi_event')
                    ->columnSpanFull(),
                TextEntry::make('anggaran_event')
                    ->numeric(),
                TextEntry::make('penyelenggara'),
                TextEntry::make('narasumber'),
                TextEntry::make('kuota_peserta')
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
