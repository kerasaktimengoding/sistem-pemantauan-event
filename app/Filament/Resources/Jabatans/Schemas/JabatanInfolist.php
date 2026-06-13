<?php

namespace App\Filament\Resources\Jabatans\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Support\Enums\FontFamily;
use Filament\Support\Enums\FontWeight;
use Illuminate\Support\Str;

class JabatanInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
    Section::make('Informasi Jabatan')
        ->columns(2)
        ->schema([

            TextEntry::make('kode_jabatan')
                ->label('Kode Jabatan')
                ->copyable()
                ->copyMessage('Kode jabatan berhasil disalin')
                ->fontFamily(FontFamily::Mono)
                ->weight(FontWeight::Bold)
                ->color('primary')
                ->placeholder('-'),

            TextEntry::make('status_jabatan')
                ->label('Status')
                ->badge()
                ->color(fn ($state): string => match (strtolower(trim((string) $state))) {
                    'aktif' => 'success',
                    'non-aktif', 'non aktif', 'tidak aktif' => 'danger',
                    default => 'warning',
                })
                ->icon(fn ($state): string => match (strtolower(trim((string) $state))) {
                    'aktif' => 'heroicon-m-check-circle',
                    'non-aktif', 'non aktif', 'tidak aktif' => 'heroicon-m-x-circle',
                    default => 'heroicon-m-minus-circle',
                })
                ->placeholder('-'),

            TextEntry::make('nama_jabatan')
                ->label('Nama & Tugas Jabatan')
                ->weight(FontWeight::Bold)
                ->color('gray')
                ->icon('heroicon-m-briefcase')
                ->iconColor('primary')
                ->helperText(fn ($record) => '📝 Tugas: ' . Str::limit($record->tugas_pokok ?? '-', 60, '...'))
                ->columnSpanFull()
                ->placeholder('-'),

            TextEntry::make('wewenang')
                ->label('Wewenang Utama')
                ->limit(80)
                ->tooltip(fn ($record) => $record->wewenang)
                ->color('gray')
                ->columnSpanFull()
                ->placeholder('-'),

            TextEntry::make('created_at')
                ->label('Dibuat Pada')
                ->dateTime('d M Y H:i')
                ->icon('heroicon-m-calendar')
                ->iconColor('gray')
                ->color('gray')
                ->placeholder('-'),

            TextEntry::make('updated_at')
                ->label('Terakhir Diubah')
                ->dateTime('d M Y H:i')
                ->icon('heroicon-m-clock')
                ->iconColor('gray')
                ->color('gray')
                ->placeholder('-'),

        ]),
]);
    }
}
