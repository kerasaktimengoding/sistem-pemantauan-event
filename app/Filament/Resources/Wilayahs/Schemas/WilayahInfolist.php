<?php

namespace App\Filament\Resources\Wilayahs\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontFamily;
use Filament\Support\Enums\FontWeight;
use Filament\Schemas\Components\Section;

class WilayahInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // 1. Nomor Urut
                // Catatan: TextEntry tidak punya rowIndex(), jadi dibuat statis/opsional.
               Section::make('Informasi Wilayah')
                ->columns(3)
                ->schema([

        TextEntry::make('kode_wilayah')
            ->label('Kode Wilayah')
            ->copyable()
            ->copyMessage('Kode wilayah berhasil disalin')
            ->fontFamily(FontFamily::Mono)
            ->weight(FontWeight::Bold)
            ->color('primary')
            ->placeholder('-'),

        TextEntry::make('kecamatan.nama_kecamatan')
            ->label('Nama Wilayah')
            ->weight(FontWeight::SemiBold)
            ->color('gray')
            ->helperText(fn ($record) => '🏡 Desa: ' . ($record->desa?->nama_desa ?? '-'))
            ->placeholder('-'),

        TextEntry::make('kode_pos')
            ->label('Kode Pos')
            ->badge()
            ->color('gray')
            ->fontFamily(FontFamily::Mono)
            ->placeholder('-'),

        TextEntry::make('luas_wilayah')
            ->label('Luas Wilayah')
            ->numeric(decimalPlaces: 2)
            ->suffix(' km²')
            ->placeholder('-'),

        TextEntry::make('jumlah_penduduk')
            ->label('Populasi')
            ->numeric()
            ->color('info')
            ->weight(FontWeight::Medium)
            ->placeholder('-'),

        TextEntry::make('potensi_ekonomi')
            ->label('Potensi Ekonomi')
            ->badge()
            ->color(fn ($state): string => match (strtolower(trim((string) $state))) {
                'pertanian', 'perkebunan' => 'success',
                'perdagangan', 'jasa' => 'warning',
                'industri', 'pariwisata' => 'info',
                'maritim', 'perikanan' => 'primary',
                default => 'gray',
            })
            ->icon(fn ($state): string => match (strtolower(trim((string) $state))) {
                'pertanian', 'perkebunan' => 'heroicon-m-building-storefront',
                default => 'heroicon-m-academic-cap',
            })
            ->placeholder('-'),

        TextEntry::make('batas_utara')
            ->label('Batas Utara')
            ->color('gray')
            ->placeholder('-'),

        TextEntry::make('batas_selatan')
            ->label('Batas Selatan')
            ->color('gray')
            ->placeholder('-'),

        TextEntry::make('keterangan_geografis')
            ->label('Geografis')
            ->limit(30)
            ->tooltip(fn ($record) => $record->keterangan_geografis)
            ->color('gray')
            ->columnSpanFull()
            ->placeholder('-'),

        TextEntry::make('created_at')
            ->label('Dibuat Pada')
            ->dateTime('d M Y H:i')
            ->color('gray')
            ->placeholder('-'),
             ]),
        ]);
    }


}
