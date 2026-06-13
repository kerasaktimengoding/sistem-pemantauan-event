<?php

namespace App\Filament\Resources\InputHargas\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontFamily;
use Filament\Support\Enums\FontWeight;
use Filament\Schemas\Components\Section;

class InputHargaInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Input Harga')
                    ->columns(2)
                    ->schema([

                        TextEntry::make('tanggal_input')
                            ->label('Waktu & Kode Transaksi')
                            ->date('d M Y')
                            ->weight(FontWeight::Bold)
                            ->color('primary')
                            ->icon('heroicon-m-calendar-days')
                            ->iconColor('primary')
                            ->fontFamily(FontFamily::Mono)
                            ->helperText(fn($record) => 'ID: ' . ($record->kode_input_harga ?? '-'))
                            ->columnSpanFull()
                            ->placeholder('-'),

                        TextEntry::make('komoditas.nama_komoditas')
                            ->label('Komoditas Produk')
                            ->weight(FontWeight::Bold)
                            ->size('md')
                            ->icon('heroicon-m-shopping-bag')
                            ->iconColor('warning')
                            ->helperText(fn($record) => 'Satuan Takaran: ' . ($record->komoditas->satuan ?? '-'))
                            ->columnSpanFull()
                            ->placeholder('-'),

                        TextEntry::make('harga')
                            ->label('Harga Jual Resmi')
                            ->money('IDR', locale: 'id_ID')
                            ->color('success')
                            ->weight(FontWeight::ExtraBold)
                            ->fontFamily(FontFamily::Mono)
                            ->placeholder('-'),

                        TextEntry::make('sumber_data')
                            ->label('Asal Sumber')
                            ->badge()
                            ->weight(FontWeight::Bold)
                            ->color(function ($state): string {
                                $cleanedState = strtolower(trim((string) $state));

                                return match (true) {
                                    in_array($cleanedState, ['dinas', 'pemerintah', 'resmi']) => 'info',
                                    in_array($cleanedState, ['pedagang', 'pasar', 'primer']) => 'warning',
                                    in_array($cleanedState, ['masyarakat', 'online']) => 'success',
                                    default => 'gray',
                                };
                            })
                            ->formatStateUsing(fn($state) => strtoupper(trim((string) $state)))
                            ->placeholder('-'),

                        TextEntry::make('pasar.nama_pasar')
                            ->label('Lokasi Pasar & Wilayah')
                            ->weight(FontWeight::Medium)
                            ->icon('heroicon-m-map-pin')
                            ->iconColor('danger')
                            ->helperText(fn($record) => 'Wilayah: ' . ($record->wilayah->nama_wilayah ?? '-'))
                            ->columnSpanFull()
                            ->placeholder('-'),

                        TextEntry::make('pegawai.nama_pegawai')
                            ->label('Petugas Enumerator')
                            ->weight(FontWeight::Medium)
                            ->icon('heroicon-m-user')
                            ->iconColor('gray')
                            ->placeholder('Bukan Pegawai Tetap'),

                        TextEntry::make('kode_input_harga')
                            ->label('Kode Input Harga')
                            ->copyable()
                            ->copyMessage('Kode input harga berhasil disalin')
                            ->fontFamily(FontFamily::Mono)
                            ->weight(FontWeight::Bold)
                            ->color('gray')
                            ->placeholder('-'),

                        TextEntry::make('keterangan')
                            ->label('Catatan Lapangan')
                            ->limit(100)
                            ->tooltip(fn($state) => $state)
                            ->color('gray')
                            ->columnSpanFull()
                            ->placeholder('Tidak ada catatan'),

                    ]),
            ]);
    }
}
