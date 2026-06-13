<?php

namespace App\Filament\Resources\KehadiranEvents\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontFamily;
use Filament\Support\Enums\FontWeight;
use Filament\Schemas\Components\Section;

class KehadiranEventInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Kehadiran Peserta')
                    ->columns(2)
                    ->schema([

                        TextEntry::make('waktu_kehadiran')
                            ->label('Waktu Kedatangan')
                            ->dateTime('d M Y, H:i')
                            ->weight(FontWeight::Bold)
                            ->color('primary')
                            ->fontFamily(FontFamily::Mono)
                            ->icon('heroicon-m-calendar-days')
                            ->iconColor('primary')
                            ->helperText(fn($record) => 'Log ID: ' . ($record->kode_kehadiran ?? '-'))
                            ->columnSpanFull()
                            ->placeholder('-'),

                        TextEntry::make('pesertaevent.nama_peserta')
                            ->label('Nama Peserta / Profil Usaha')
                            ->weight(FontWeight::Bold)
                            ->size('lg')
                            ->icon('heroicon-m-user-circle')
                            ->iconColor('gray')
                            ->helperText(fn($record) => 'Usaha: ' . ($record->pesertaevent->nama_usaha ?? '-'))
                            ->columnSpanFull()
                            ->placeholder('-'),

                        TextEntry::make('status_kehadiran')
                            ->label('Status Presensi')
                            ->badge()
                            ->color(fn($state): string => match ((string) $state) {
                                'Hadir' => 'success',
                                'Izin' => 'warning',
                                'Terlambat' => 'danger',
                                default => 'gray',
                            })
                            ->icon(fn($state): string => match ((string) $state) {
                                'Hadir' => 'heroicon-m-check-badge',
                                'Izin' => 'heroicon-m-envelope-open',
                                'Terlambat' => 'heroicon-m-exclamation-triangle',
                                default => 'heroicon-m-question-mark-circle',
                            })
                            ->placeholder('-'),

                        TextEntry::make('kode_kehadiran')
                            ->label('Kode Kehadiran')
                            ->copyable()
                            ->copyMessage('Kode kehadiran berhasil disalin')
                            ->fontFamily(FontFamily::Mono)
                            ->weight(FontWeight::Bold)
                            ->color('gray')
                            ->placeholder('-'),

                        TextEntry::make('catatan')
                            ->label('Catatan/Keterangan')
                            ->limit(100)
                            ->placeholder('Tidak ada catatan')
                            ->color('gray')
                            ->icon('heroicon-m-document-text')
                            ->iconColor('gray')
                            ->tooltip(fn($record) => $record->catatan)
                            ->columnSpanFull(),

                        TextEntry::make('created_at')
                            ->label('Sistem Record')
                            ->dateTime('d M Y, H:i:s')
                            ->fontFamily(FontFamily::Mono)
                            ->color('gray')
                            ->size('sm')
                            ->placeholder('-'),

                    ]),
            ]);
    }
}
