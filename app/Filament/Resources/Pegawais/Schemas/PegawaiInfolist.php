<?php

namespace App\Filament\Resources\Pegawais\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontFamily;
use Filament\Support\Enums\FontWeight;
use Filament\Schemas\Components\Section;

class PegawaiInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
           ->components([
    Section::make('Informasi Pegawai')
        ->columns(2)
        ->schema([

            TextEntry::make('nama_pegawai')
                ->label('Pegawai')
                ->weight(FontWeight::Bold)
                ->color('gray')
                ->helperText(fn ($record) => '🪪 NIP: ' . ($record->nip ?? '-'))
                ->columnSpanFull()
                ->placeholder('-'),

            TextEntry::make('jenis_kelamin')
                ->label('L/P')
                ->badge()
                ->color(fn ($state): string => match (strtolower(trim((string) $state))) {
                    'laki-laki', 'l' => 'info',
                    'perempuan', 'p' => 'success',
                    default => 'secondary',
                })
                ->icon(fn ($state): string => match (strtolower(trim((string) $state))) {
                    'laki-laki', 'l' => 'heroicon-m-user',
                    'perempuan', 'p' => 'heroicon-m-user-circle',
                    default => 'heroicon-m-question-mark-circle',
                })
                ->placeholder('-'),

            TextEntry::make('status_pegawai')
                ->label('Status')
                ->badge()
                ->color(fn ($state): string => match (strtolower(trim((string) $state))) {
                    'aktif' => 'success',
                    'non-aktif', 'tidak aktif' => 'danger',
                    'cuti' => 'warning',
                    default => 'secondary',
                })
                ->icon(fn ($state): string => match (strtolower(trim((string) $state))) {
                    'aktif' => 'heroicon-m-check-circle',
                    'non-aktif', 'tidak aktif' => 'heroicon-m-x-circle',
                    'cuti' => 'heroicon-m-clock',
                    default => 'heroicon-m-minus-circle',
                })
                ->placeholder('-'),

            TextEntry::make('jabatan.nama_jabatan')
                ->label('Jabatan & Wilayah')
                ->weight(FontWeight::SemiBold)
                ->icon('heroicon-m-briefcase')
                ->iconColor('primary')
                ->helperText(fn ($record) => '📍 ' . ($record->wilayah->nama_wilayah ?? 'Belum Ditentukan'))
                ->columnSpanFull()
                ->placeholder('-'),

            TextEntry::make('no_hp')
                ->label('Kontak')
                ->copyable()
                ->copyMessage('Nomor kontak berhasil disalin')
                ->icon('heroicon-m-phone')
                ->iconColor('success')
                ->weight(FontWeight::Medium)
                ->url(fn ($record) => $record->no_hp
                    ? 'https://wa.me/' . preg_replace('/[^0-9]/', '', $record->no_hp)
                    : null
                )
                ->openUrlInNewTab()
                ->helperText(fn ($record) => $record->email ? '✉️ ' . $record->email : '-')
                ->extraAttributes([
                    'title' => 'Klik untuk chat WhatsApp',
                ])
                ->columnSpanFull()
                ->placeholder('-'),

            TextEntry::make('tanggal_masuk')
                ->label('Mulai Bekerja')
                ->date('d F Y')
                ->icon('heroicon-m-calendar')
                ->iconColor('gray')
                ->color('gray')
                ->placeholder('-'),

            TextEntry::make('nik')
                ->label('NIK')
                ->fontFamily(FontFamily::Mono)
                ->copyable()
                ->copyMessage('NIK berhasil disalin')
                ->color('gray')
                ->placeholder('-'),

        ]),
]);
    }
}
