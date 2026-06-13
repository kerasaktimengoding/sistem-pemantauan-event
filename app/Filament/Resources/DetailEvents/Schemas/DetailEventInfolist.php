<?php

namespace App\Filament\Resources\DetailEvents\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontFamily;
use Filament\Support\Enums\FontWeight;
use Filament\Schemas\Components\Section;

class DetailEventInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Detail Event')
                    ->columns(2)
                    ->schema([

                        TextEntry::make('event.nama_event')
                            ->label('Detail Kegiatan')
                            ->weight(FontWeight::Bold)
                            ->color('gray')
                            ->helperText(fn($record) => 'Kode: ' . ($record->kode_detail_event ?? '-') . ' • Oleh: ' . ($record->penyelenggara ?? '-'))
                            ->columnSpanFull()
                            ->placeholder('-'),

                        TextEntry::make('narasumber')
                            ->label('Narasumber')
                            ->icon('heroicon-s-user')
                            ->iconColor('gray')
                            ->color('gray')
                            ->weight(FontWeight::SemiBold)
                            ->placeholder('-'),

                        TextEntry::make('kuota_peserta')
                            ->label('Kuota')
                            ->badge()
                            ->fontFamily(FontFamily::Mono)
                            ->color(fn($state) => (int) $state <= 20 ? 'danger' : 'info')
                            ->icon(fn($state) => (int) $state <= 20 ? 'heroicon-m-exclamation-triangle' : 'heroicon-m-users')
                            ->suffix(' Orang')
                            ->placeholder('-'),

                        TextEntry::make('anggaran_event')
                            ->label('Anggaran')
                            ->money('IDR', locale: 'id_ID')
                            ->fontFamily(FontFamily::Mono)
                            ->weight(FontWeight::Bold)
                            ->color(fn($state) => (float) $state >= 50000000 ? 'success' : 'primary')
                            ->placeholder('-'),

                        TextEntry::make('kode_detail_event')
                            ->label('Kode Detail Event')
                            ->copyable()
                            ->copyMessage('Kode detail event berhasil disalin')
                            ->fontFamily(FontFamily::Mono)
                            ->weight(FontWeight::Bold)
                            ->color('primary')
                            ->placeholder('-'),

                        TextEntry::make('penyelenggara')
                            ->label('Penyelenggara')
                            ->icon('heroicon-m-building-office')
                            ->iconColor('primary')
                            ->color('gray')
                            ->placeholder('-'),

                        TextEntry::make('deskripsi_event')
                            ->label('Deskripsi Kegiatan')
                            ->limit(120)
                            ->tooltip(fn($state) => $state)
                            ->color('gray')
                            ->columnSpanFull()
                            ->placeholder('Tidak ada deskripsi tambahan'),

                        TextEntry::make('updated_at')
                            ->label('Update Terakhir')
                            ->dateTime('d M Y, H:i')
                            ->fontFamily(FontFamily::Mono)
                            ->color('gray')
                            ->placeholder('-'),

                    ]),
            ]);
    }
}
