<?php

namespace App\Filament\Resources\PerbandinganWilayahs\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\FontFamily;
use Filament\Schemas\Components\Section;

class PerbandinganWilayahInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Perbandingan Harga')
                    ->columns(2)
                    ->schema([

                        TextEntry::make('kode_perbandingan')
                            ->label('Cakupan Data')
                            ->weight(FontWeight::Bold)
                            ->fontFamily(FontFamily::Mono)
                            ->copyable()
                            ->copyMessage('Kode perbandingan berhasil disalin')
                            ->helperText(fn($record) => '📦 ' . ($record->komoditas->nama_komoditas ?? '-'))
                            ->columnSpanFull()
                            ->placeholder('-'),

                        TextEntry::make('desa1.nama_desa')
                            ->label('Titik Komparasi Wilayah')
                            ->weight(FontWeight::Medium)
                            ->color('primary')
                            ->helperText(fn($record) => '⚔️ Dibandingkan dengan: ' . ($record->desa2->nama_desa ?? '-'))
                            ->columnSpanFull()
                            ->placeholder('-'),

                        TextEntry::make('selisih_harga')
                            ->label('Selisih Harga')
                            ->money('IDR', locale: 'id_ID')
                            ->fontFamily(FontFamily::Mono)
                            ->weight(FontWeight::Bold)
                            ->color(
                                fn($state) => (float) $state > 5000
                                ? 'danger'
                                : ((float) $state > 2000 ? 'warning' : 'success')
                            )
                            ->formatStateUsing(fn($state) => 'Rp ' . number_format((float) $state, 0, ',', '.'))
                            ->placeholder('-'),

                        TextEntry::make('keterangan')
                            ->label('Analisis Disparitas')
                            ->badge()
                            ->color(fn($record) => match (true) {
                                (float) $record->selisih_harga > 5000 => 'danger',
                                (float) $record->selisih_harga > 2000 => 'warning',
                                default => 'success',
                            })
                            ->icon(fn($record) => match (true) {
                                (float) $record->selisih_harga > 5000 => 'heroicon-m-exclamation-triangle',
                                (float) $record->selisih_harga > 2000 => 'heroicon-m-arrow-path',
                                default => 'heroicon-m-check-badge',
                            })
                            ->limit(80)
                            ->tooltip(fn($state) => $state)
                            ->placeholder('-'),

                    ]),
            ]);
    }
}
