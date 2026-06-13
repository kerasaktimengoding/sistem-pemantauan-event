<?php

namespace App\Filament\Resources\TrenHargas\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontFamily;
use Filament\Support\Enums\FontWeight;
use Filament\Schemas\Components\Section;

class TrenHargaInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Tren Harga')
                    ->columns(2)
                    ->schema([

                        TextEntry::make('periode_tren')
                            ->label('Periode')
                            ->date('M Y')
                            ->weight(FontWeight::Bold)
                            ->color('gray')
                            ->icon('heroicon-m-calendar-days')
                            ->iconColor('gray')
                            ->placeholder('-'),

                        TextEntry::make('arah_tren')
                            ->label('Kondisi')
                            ->badge()
                            ->color(fn($state): string => match ((string) $state) {
                                'Naik' => 'danger',
                                'Turun' => 'success',
                                'Stabil' => 'info',
                                default => 'gray',
                            })
                            ->icon(fn($state): string => match ((string) $state) {
                                'Naik' => 'heroicon-m-arrow-trending-up',
                                'Turun' => 'heroicon-m-arrow-trending-down',
                                'Stabil' => 'heroicon-m-minus',
                                default => 'heroicon-m-question-mark-circle',
                            })
                            ->placeholder('-'),

                        TextEntry::make('komoditas.nama_komoditas')
                            ->label('Komoditas & Wilayah')
                            ->weight(FontWeight::Bold)
                            ->helperText(fn($record) => '📍 Wilayah: ' . ($record->desa->nama_desa ?? '-'))
                            ->columnSpanFull()
                            ->placeholder('-'),

                        TextEntry::make('harga_akhir')
                            ->label('Informasi Harga')
                            ->money('IDR', locale: 'id_ID')
                            ->fontFamily(FontFamily::Mono)
                            ->weight(FontWeight::Bold)
                            ->color('primary')
                            ->helperText(fn($record) => 'Semula: Rp ' . number_format((float) ($record->harga_awal ?? 0), 0, ',', '.'))
                            ->columnSpanFull()
                            ->placeholder('-'),

                        TextEntry::make('harga_awal')
                            ->label('Harga Awal')
                            ->money('IDR', locale: 'id_ID')
                            ->fontFamily(FontFamily::Mono)
                            ->color('gray')
                            ->placeholder('-'),

                        TextEntry::make('persentase_perubahan')
                            ->label('Selisih (%)')
                            ->fontFamily(FontFamily::Mono)
                            ->weight(FontWeight::Bold)
                            ->color(fn($record) => match ($record->arah_tren) {
                                'Naik' => 'danger',
                                'Turun' => 'success',
                                default => 'gray',
                            })
                            ->formatStateUsing(fn($state, $record) => match ($record->arah_tren) {
                                'Naik' => "▲ {$state}%",
                                'Turun' => "▼ {$state}%",
                                default => "• {$state}%",
                            })
                            ->placeholder('-'),

                    ]),
            ]);
    }
}
