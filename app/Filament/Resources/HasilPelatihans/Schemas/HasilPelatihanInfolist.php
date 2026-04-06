<?php

namespace App\Filament\Resources\HasilPelatihans\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class HasilPelatihanInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('kode_hasil_pelatihan'),
                TextEntry::make('peserta_event_id')
                    ->numeric(),
                TextEntry::make('nilai_pretest')
                    ->numeric(),
                TextEntry::make('nilai_posttest')
                    ->numeric(),
                TextEntry::make('nilai_akhir')
                    ->numeric(),
                TextEntry::make('status_kelulusan'),
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
