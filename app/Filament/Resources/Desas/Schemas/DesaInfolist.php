<?php

namespace App\Filament\Resources\Desas\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontFamily;
use Filament\Support\Enums\FontWeight;
use Filament\Schemas\Components\Section;

class DesaInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                 Section::make('Informasi Desa')
                ->columns(4)
                ->schema([

                    TextEntry::make('kode_desa')
                        ->label('Kode Desa')
                        ->fontFamily(FontFamily::Mono)
                        ->copyable()
                        ->copyMessage('Kode Desa berhasil disalin')
                        ->color('gray')
                        ->placeholder('-'),

                    TextEntry::make('nama_desa')
                        ->label('Informasi Wilayah')
                        ->weight(FontWeight::Bold)
                        ->color('primary')
                        ->helperText(fn ($record) => 'Kecamatan: ' . ($record->kecamatan?->nama_kecamatan ?? '-'))
                        ->placeholder('-'),

                    TextEntry::make('kecamatan.nama_kecamatan')
                        ->label('Kecamatan')
                        ->icon('heroicon-m-map')
                        ->iconColor('info')
                        ->color('gray')
                        ->placeholder('-'),

                    TextEntry::make('nama_pembakal')
                        ->label('Pimpinan / Pembakal')
                        ->icon('heroicon-m-user')
                        ->iconColor('success')
                        ->weight(FontWeight::Medium)
                        ->helperText(fn ($record) => '📞 ' . ($record->no_hp_pembakal ?? '-'))
                        ->placeholder('-'),

                    TextEntry::make('alamat_kantor_desa')
                        ->label('Alamat Kantor')
                        ->limit(30)
                        ->tooltip(fn ($record) => $record->alamat_kantor_desa)
                        ->color('gray')
                        ->columnSpanFull()
                        ->placeholder('-'),

                    TextEntry::make('kode_pos')
                        ->label('Kode Pos')
                        ->fontFamily(FontFamily::Mono)
                        ->badge()
                        ->color('warning')
                        ->placeholder('-'),

                    TextEntry::make('koordinat')
                        ->label('Koordinat (Lat, Long)')
                        ->state(fn ($record) => $record->latitude && $record->longitude
                            ? "{$record->latitude}, {$record->longitude}"
                            : '-'
                        )
                        ->icon('heroicon-m-map-pin')
                        ->iconColor('danger')
                        ->fontFamily(FontFamily::Mono)
                        ->color('gray')
                        ->placeholder('-'),

                    TextEntry::make('latitude')
                        ->label('Latitude')
                        ->numeric()
                        ->fontFamily(FontFamily::Mono)
                        ->color('gray')
                        ->placeholder('-'),

                    TextEntry::make('longitude')
                        ->label('Longitude')
                        ->numeric()
                        ->fontFamily(FontFamily::Mono)
                        ->color('gray')
                        ->placeholder('-'),

                    TextEntry::make('no_hp_pembakal')
                        ->label('No. HP Pembakal')
                        ->icon('heroicon-m-phone')
                        ->iconColor('success')
                        ->copyable()
                        ->copyMessage('Nomor HP berhasil disalin')
                        ->placeholder('-'),

                    TextEntry::make('is_active')
                        ->label('Status')
                        ->badge()
                        ->color(fn ($state): string => match ((string) $state) {
                            '1' => 'success',
                            '0' => 'danger',
                            default => 'gray',
                        })
                        ->formatStateUsing(fn ($state): string => match ((string) $state) {
                            '1' => 'Aktif',
                            '0' => 'Non-Aktif',
                            default => 'Tidak Diketahui',
                        })
                        ->placeholder('-'),

                    TextEntry::make('created_at')
                        ->label('Dibuat Pada')
                        ->dateTime('d M Y H:i')
                        ->icon('heroicon-m-calendar')
                        ->iconColor('gray')
                        ->color('gray')
                        ->placeholder('-'),

                    TextEntry::make('updated_at')
                        ->label('Diperbarui Pada')
                        ->dateTime('d M Y H:i')
                        ->icon('heroicon-m-clock')
                        ->iconColor('gray')
                        ->color('gray')
                        ->placeholder('-'),

                ]),
            ]);
    }
}
