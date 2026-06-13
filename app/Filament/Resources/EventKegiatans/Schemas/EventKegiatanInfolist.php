<?php

namespace App\Filament\Resources\EventKegiatans\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Schemas\Components\Section;
use Filament\Support\Enums\FontFamily;

class EventKegiatanInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
          ->components([
    Section::make('Informasi Kegiatan')
        ->columns(2)
        ->schema([

            TextEntry::make('nama_event')
                ->label('Nama Kegiatan')
                ->weight(FontWeight::Bold)
                ->size('lg')
                ->icon('heroicon-m-sparkles')
                ->iconColor('primary')
                ->helperText(fn ($record) => 'Kode Event: ' . ($record->kode_event ?? '-'))
                ->columnSpanFull()
                ->placeholder('-'),

            TextEntry::make('jenis_event')
                ->label('Jenis Agenda')
                ->badge()
                ->color(fn ($state): string => match ((string) $state) {
                    'Sosialisasi', 'Penyuluhan' => 'info',
                    'Rapat', 'Koordinasi' => 'warning',
                    'Inspeksi', 'Sidak', 'Monitoring' => 'danger',
                    'Festival', 'Pasar Murah' => 'success',
                    default => 'gray',
                })
                ->formatStateUsing(fn ($state) => strtoupper((string) $state))
                ->placeholder('-'),

            TextEntry::make('status_event')
                ->label('Status')
                ->badge()
                ->color(fn ($state): string => match ((string) $state) {
                    'Terjadwal' => 'info',
                    'Berlangsung' => 'warning',
                    'Selesai' => 'success',
                    'Dibatalkan' => 'danger',
                    default => 'gray',
                })
                ->icon(fn ($state): string => match ((string) $state) {
                    'Terjadwal' => 'heroicon-m-calendar',
                    'Berlangsung' => 'heroicon-m-arrow-path',
                    'Selesai' => 'heroicon-m-check-badge',
                    'Dibatalkan' => 'heroicon-m-x-circle',
                    default => 'heroicon-m-clock',
                })
                ->placeholder('-'),

            TextEntry::make('tanggal_mulai')
                ->label('Periode Pelaksanaan')
                ->date('d M Y')
                ->weight(FontWeight::Medium)
                ->icon('heroicon-m-calendar-days')
                ->iconColor('success')
                ->helperText(fn ($record) => $record->tanggal_selesai
                    ? 's/d ' . \Carbon\Carbon::parse($record->tanggal_selesai)->format('d M Y')
                    : 'Satu Hari Selesai'
                )
                ->columnSpanFull()
                ->placeholder('-'),

            TextEntry::make('wilayah.nama_wilayah')
                ->label('Lokasi & Wilayah')
                ->weight(FontWeight::Medium)
                ->icon('heroicon-m-map-pin')
                ->iconColor('danger')
                ->helperText(fn ($record) => 'Detail: ' . \Illuminate\Support\Str::limit($record->lokasi_event ?? '-', 80))
                ->columnSpanFull()
                ->placeholder('-'),

            TextEntry::make('kode_event')
                ->label('Kode Event')
                ->copyable()
                ->copyMessage('Kode event berhasil disalin')
                ->fontFamily(FontFamily::Mono)
                ->weight(FontWeight::Bold)
                ->color('primary')
                ->placeholder('-'),

            TextEntry::make('lokasi_event')
                ->label('Detail Lokasi')
                ->limit(100)
                ->tooltip(fn ($state) => $state)
                ->color('gray')
                ->columnSpanFull()
                ->placeholder('-'),

        ]),
]);
    }
}
