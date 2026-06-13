<?php

namespace App\Filament\Resources\Pedagangs\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\FontFamily;

class PedagangInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Pedagang')
                    ->columns(2)
                    ->schema([

                        TextEntry::make('nama_pedagang')
                            ->label('Informasi Pedagang')
                            ->weight(FontWeight::Bold)
                            ->size('md')
                            ->color('primary')
                            ->helperText(fn($record) => '🔑 KODE: ' . ($record->kode_pedagang ?? '-'))
                            ->columnSpanFull()
                            ->placeholder('-'),

                        TextEntry::make('nik')
                            ->label('NIK')
                            ->state(
                                fn($record) => $record->nik
                                ? '🪪 NIK: ' . $record->nik
                                : '⚠️ NIK Belum Direkam'
                            )
                            ->fontFamily(FontFamily::Mono)
                            ->copyable()
                            ->copyMessage('NIK berhasil disalin')
                            ->color('gray')
                            ->columnSpanFull()
                            ->placeholder('-'),

                        TextEntry::make('kecamatan.nama_kecamatan')
                            ->label('Domisili Wilayah')
                            ->weight(FontWeight::SemiBold)
                            ->color('gray')
                            ->icon('heroicon-m-map-pin')
                            ->iconColor('danger')
                            ->helperText(fn($record) => '🏡 Desa: ' . ($record->desa?->nama_desa ?? '-'))
                            ->columnSpanFull()
                            ->placeholder('-'),

                        TextEntry::make('alamat')
                            ->label('Alamat')
                            ->state(
                                fn($record) => $record->alamat
                                ? '📍 ' . str($record->alamat)->limit(100)
                                : 'Tidak ada detail alamat'
                            )
                            ->color('gray')
                            ->columnSpanFull()
                            ->placeholder('-'),

                        TextEntry::make('no_hp')
                            ->label('Kontak WhatsApp')
                            ->icon('heroicon-m-chat-bubble-left-right')
                            ->iconColor('success')
                            ->color('success')
                            ->weight(FontWeight::Medium)
                            ->fontFamily(FontFamily::Mono)
                            ->copyable()
                            ->copyMessage('Nomor WhatsApp berhasil disalin')
                            ->url(
                                fn($record) => $record->no_hp
                                ? 'https://wa.me/' . preg_replace('/[^0-9]/', '', $record->no_hp)
                                : null
                            )
                            ->openUrlInNewTab()
                            ->columnSpanFull()
                            ->placeholder('-'),

                        TextEntry::make('kode_pedagang')
                            ->label('Kode Pedagang')
                            ->copyable()
                            ->copyMessage('Kode pedagang berhasil disalin')
                            ->fontFamily(FontFamily::Mono)
                            ->weight(FontWeight::Bold)
                            ->color('primary')
                            ->placeholder('-'),

                        TextEntry::make('status_pedagang')
                            ->label('Status')
                            ->badge()
                            ->color(fn($state): string => match (strtolower(trim((string) $state))) {
                                'aktif' => 'success',
                                'tersuspend', 'ditangguhkan' => 'warning',
                                'non-aktif', 'pasif' => 'danger',
                                default => 'gray',
                            })
                            ->icon(fn($state): string => match (strtolower(trim((string) $state))) {
                                'aktif' => 'heroicon-m-shield-check',
                                'tersuspend', 'ditangguhkan' => 'heroicon-m-no-symbol',
                                'non-aktif', 'pasif' => 'heroicon-m-minus-circle',
                                default => 'heroicon-m-question-mark-circle',
                            })
                            ->placeholder('-'),

                    ]),
            ]);
    }
}
