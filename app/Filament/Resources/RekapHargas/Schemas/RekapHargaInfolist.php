<?php

namespace App\Filament\Resources\RekapHargas\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\FontFamily;
use Filament\Schemas\Components\Section;

class RekapHargaInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Rekap Harga')
                    ->columns(2)
                    ->schema([

                        TextEntry::make('periode_rekap')
                            ->label('Periode Rekap')
                            ->formatStateUsing(fn($state) => \Carbon\Carbon::parse($state)->isoFormat('MMMM YYYY'))
                            ->weight(FontWeight::Bold)
                            ->color('primary')
                            ->icon('heroicon-m-calendar-days')
                            ->iconColor('primary')
                            ->columnSpanFull()
                            ->placeholder('-'),

                        TextEntry::make('komoditas.nama_komoditas')
                            ->label('Komoditas')
                            ->weight(FontWeight::SemiBold)
                            ->color('gray')
                            ->icon('heroicon-m-shopping-bag')
                            ->iconColor('primary')
                            ->columnSpanFull()
                            ->placeholder('-'),

                        TextEntry::make('kecamatan.nama_kecamatan')
                            ->label('Cakupan Wilayah')
                            ->weight(FontWeight::Medium)
                            ->color('gray')
                            ->helperText(
                                fn($record) => $record->desa
                                ? '📍 Desa: ' . $record->desa->nama_desa
                                : '🏢 Seluruh Kecamatan'
                            )
                            ->columnSpanFull()
                            ->placeholder('-'),

                        TextEntry::make('harga_rata_rata')
                            ->label('Harga Rata-Rata')
                            ->money('IDR', locale: 'id_ID')
                            ->fontFamily(FontFamily::Mono)
                            ->weight(FontWeight::Bold)
                            ->color('primary')
                            ->helperText(function ($record) {
                                $maksimum = (float) ($record->harga_maksimum ?? 0);
                                $minimum = (float) ($record->harga_minimum ?? 0);
                                $spread = $maksimum - $minimum;

                                return 'Selisih: Rp ' . number_format($spread, 0, ',', '.');
                            })
                            ->columnSpanFull()
                            ->placeholder('-'),

                        TextEntry::make('harga_maksimum')
                            ->label('Tertinggi')
                            ->money('IDR', locale: 'id_ID')
                            ->fontFamily(FontFamily::Mono)
                            ->color('danger')
                            ->helperText('Batas Atas')
                            ->placeholder('-'),

                        TextEntry::make('harga_minimum')
                            ->label('Terendah')
                            ->money('IDR', locale: 'id_ID')
                            ->fontFamily(FontFamily::Mono)
                            ->color('success')
                            ->helperText('Batas Bawah')
                            ->placeholder('-'),

                        TextEntry::make('updated_at')
                            ->label('Terakhir Sinkron')
                            ->dateTime('d M Y, H:i')
                            ->fontFamily(FontFamily::Mono)
                            ->color('gray')
                            ->placeholder('-'),

                    ]),
            ]);
    }
}
