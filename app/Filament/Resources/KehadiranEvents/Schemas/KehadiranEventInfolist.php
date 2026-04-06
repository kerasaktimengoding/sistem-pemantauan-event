<?php

namespace App\Filament\Resources\KehadiranEvents\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class KehadiranEventInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('kode_kehadiran'),
                TextEntry::make('peserta_event_id')
                    ->numeric(),
                TextEntry::make('status_kehadiran'),
                TextEntry::make('waktu_kehadiran')
                    ->dateTime(),
                TextEntry::make('catatan')
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
