<?php

namespace App\Filament\Resources\PesertaEvents\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\FontFamily;

class PesertaEventInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Peserta Event')
                    ->columns(2)
                    ->schema([

                        TextEntry::make('nama_peserta')
                            ->label('Nama Peserta / NIK')
                            ->weight(FontWeight::Bold)
                            ->size('lg')
                            ->icon('heroicon-m-user')
                            ->iconColor('primary')
                            ->helperText(fn($record) => 'NIK: ' . ($record->nik ?? '-'))
                            ->columnSpanFull()
                            ->placeholder('-'),

                        TextEntry::make('nama_usaha')
                            ->label('Profil Usaha & Produk')
                            ->weight(FontWeight::Medium)
                            ->color('primary')
                            ->icon('heroicon-m-building-storefront')
                            ->iconColor('warning')
                            ->helperText(fn($record) => 'Produk: ' . ($record->jenis_produk ?? '-'))
                            ->columnSpanFull()
                            ->placeholder('-'),

                        TextEntry::make('event.nama_event')
                            ->label('Agenda Kegiatan')
                            ->badge()
                            ->color('info')
                            ->icon('heroicon-m-sparkles')
                            ->weight(FontWeight::Bold)
                            ->formatStateUsing(fn($state) => strtoupper((string) $state))
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
                            ->copyMessageDuration(1500)
                            ->placeholder('-'),

                        TextEntry::make('status_partisipasi')
                            ->label('Status')
                            ->badge()
                            ->color(fn($state): string => match ((string) $state) {
                                'Terdaftar' => 'info',
                                'Hadir' => 'success',
                                'Batal' => 'danger',
                                default => 'gray',
                            })
                            ->icon(fn($state): string => match ((string) $state) {
                                'Terdaftar' => 'heroicon-m-clipboard-document-check',
                                'Hadir' => 'heroicon-m-check-badge',
                                'Batal' => 'heroicon-m-x-circle',
                                default => 'heroicon-m-question-mark-circle',
                            })
                            ->placeholder('-'),

                        TextEntry::make('wilayah.nama_wilayah')
                            ->label('Wilayah Asal')
                            ->weight(FontWeight::Medium)
                            ->icon('heroicon-m-map-pin')
                            ->iconColor('danger')
                            ->placeholder('Luar Wilayah'),

                        TextEntry::make('tanggal_registrasi')
                            ->label('Waktu Mendaftar')
                            ->dateTime('d M Y, H:i')
                            ->fontFamily(FontFamily::Mono)
                            ->color('gray')
                            ->size('sm')
                            ->placeholder('-'),

                        TextEntry::make('nik')
                            ->label('NIK')
                            ->fontFamily(FontFamily::Mono)
                            ->copyable()
                            ->copyMessage('NIK berhasil disalin')
                            ->color('gray')
                            ->placeholder('-'),

                        TextEntry::make('jenis_produk')
                            ->label('Jenis Produk')
                            ->badge()
                            ->color('gray')
                            ->placeholder('-'),

                    ]),
            ]);
    }
}
