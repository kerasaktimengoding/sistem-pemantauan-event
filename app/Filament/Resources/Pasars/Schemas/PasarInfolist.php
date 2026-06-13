<?php

namespace App\Filament\Resources\Pasars\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontFamily;
use Filament\Support\Enums\FontWeight;
use Filament\Schemas\Components\Section;

class PasarInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Pasar')
                    ->columns(2)
                    ->schema([

                        TextEntry::make('nama_pasar')
                            ->label('Detail Pasar')
                            ->weight(FontWeight::Bold)
                            ->size('md')
                            ->color('primary')
                            ->helperText(fn($record) => '🔑 KODE: ' . ($record->kode_pasar ?? '-'))
                            ->columnSpanFull()
                            ->placeholder('-'),

                        TextEntry::make('alamat_pasar')
                            ->label('Alamat Pasar')
                            ->state(
                                fn($record) => $record->alamat_pasar
                                ? '📍 ' . str($record->alamat_pasar)->limit(120)
                                : 'Belum ada alamat lengkap'
                            )
                            ->color('gray')
                            ->columnSpanFull()
                            ->placeholder('-'),

                        TextEntry::make('kecamatan.nama_kecamatan')
                            ->label('Lokasi Wilayah')
                            ->weight(FontWeight::SemiBold)
                            ->color('gray')
                            ->icon('heroicon-m-map')
                            ->iconColor('gray')
                            ->helperText(fn($record) => '🏡 Desa: ' . ($record->desa?->nama_desa ?? '-'))
                            ->columnSpanFull()
                            ->placeholder('-'),

                        TextEntry::make('desa.nama_desa')
                            ->label('Desa')
                            ->icon('heroicon-m-home')
                            ->iconColor('gray')
                            ->color('gray')
                            ->placeholder('-'),

                        TextEntry::make('kode_pasar')
                            ->label('Kode Pasar')
                            ->copyable()
                            ->copyMessage('Kode pasar berhasil disalin')
                            ->fontFamily(FontFamily::Mono)
                            ->weight(FontWeight::Bold)
                            ->color('primary')
                            ->placeholder('-'),

                        TextEntry::make('status_pasar')
                            ->label('Status Operasional')
                            ->badge()
                            ->color(fn($state): string => match (strtolower(trim((string) $state))) {
                                'aktif' => 'success',
                                'non-aktif', 'tutup' => 'danger',
                                'renovasi', 'perbaikan' => 'warning',
                                default => 'gray',
                            })
                            ->icon(fn($state): string => match (strtolower(trim((string) $state))) {
                                'aktif' => 'heroicon-m-building-storefront',
                                'non-aktif', 'tutup' => 'heroicon-m-x-circle',
                                'renovasi', 'perbaikan' => 'heroicon-m-wrench-screwdriver',
                                default => 'heroicon-m-question-mark-circle',
                            })
                            ->placeholder('-'),

                        TextEntry::make('updated_at')
                            ->label('Sinkronisasi')
                            ->since()
                            ->dateTimeTooltip('d M Y H:i:s')
                            ->icon('heroicon-m-clock')
                            ->iconColor('gray')
                            ->color('gray')
                            ->size('sm')
                            ->placeholder('-'),

                    ]),
            ]);
    }
}
