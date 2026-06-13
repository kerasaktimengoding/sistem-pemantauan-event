<?php

namespace App\Filament\Resources\Tempats\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\FontFamily;
use Filament\Schemas\Components\Section;

class TempatInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Tempat Usaha')
                    ->columns(2)
                    ->schema([

                        TextEntry::make('kode_tempat_usaha')
                            ->label('Kode Tempat')
                            ->copyable()
                            ->copyMessage('Kode tempat berhasil disalin')
                            ->fontFamily(FontFamily::Mono)
                            ->weight(FontWeight::Bold)
                            ->color('primary')
                            ->placeholder('-'),

                        TextEntry::make('status_tempat')
                            ->label('Status')
                            ->badge()
                            ->color(fn($state): string => match (strtolower(trim((string) $state))) {
                                'aktif', 'terisi', 'tersewa' => 'success',
                                'kosong', 'tersedia' => 'gray',
                                'perbaikan', 'rusak', 'renovasi' => 'danger',
                                'booking', 'dipesan' => 'warning',
                                default => 'info',
                            })
                            ->placeholder('-'),

                        TextEntry::make('nomor_tempat')
                            ->label('No. Tempat / Blok')
                            ->weight(FontWeight::Medium)
                            ->columnSpanFull()
                            ->placeholder('-'),

                        TextEntry::make('luas_tempat')
                            ->label('Luas Ukuran')
                            ->suffix(' m²')
                            ->weight(FontWeight::Medium)
                            ->placeholder('-'),

                        TextEntry::make('pasar.nama_pasar')
                            ->label('Lokasi Pasar')
                            ->weight(FontWeight::SemiBold)
                            ->helperText(fn($record) => 'Wilayah: ' . ($record->wilayah->nama_desa ?? '-'))
                            ->placeholder('-'),

                        TextEntry::make('pedagang.nama_pedagang')
                            ->label('Pengelola / Pedagang')
                            ->weight(FontWeight::Medium)
                            ->helperText(fn($record) => $record->nomor_hp ? '📞 ' . $record->nomor_hp : '📞 -')
                            ->url(
                                fn($record) => $record->nomor_hp
                                ? 'https://wa.me/' . preg_replace('/[^0-9]/', '', $record->nomor_hp)
                                : null
                            )
                            ->openUrlInNewTab()
                            ->color(fn($record) => $record->nomor_hp ? 'success' : 'gray')
                            ->columnSpanFull()
                            ->placeholder('-'),

                        TextEntry::make('created_at')
                            ->label('Dibuat Pada')
                            ->dateTime('d M Y H:i')
                            ->icon('heroicon-m-calendar')
                            ->iconColor('gray')
                            ->color('gray')
                            ->placeholder('-'),

                    ]),
            ]);
    }
}
