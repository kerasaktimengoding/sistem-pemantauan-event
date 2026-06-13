<?php

namespace App\Filament\Resources\Komoditas\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\FontFamily;
use Filament\Schemas\Components\Section;

class KomoditasInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Komoditas')
                    ->columns(2)
                    ->schema([

                        TextEntry::make('kode_komoditas')
                            ->label('Kode')
                            ->copyable()
                            ->copyMessage('Kode komoditas berhasil disalin')
                            ->copyMessageDuration(1500)
                            ->weight(FontWeight::Bold)
                            ->fontFamily(FontFamily::Mono)
                            ->color('primary')
                            ->icon('heroicon-m-square-2-stack')
                            ->iconColor('gray')
                            ->placeholder('-'),

                        TextEntry::make('status_komoditas')
                            ->label('Status')
                            ->badge()
                            ->color(fn($state): string => match (strtolower(trim((string) $state))) {
                                'aktif' => 'success',
                                'terbatas' => 'warning',
                                'non-aktif', 'matikan' => 'danger',
                                default => 'gray',
                            })
                            ->icon(fn($state): string => match (strtolower(trim((string) $state))) {
                                'aktif' => 'heroicon-m-check-circle',
                                'terbatas' => 'heroicon-m-exclamation-triangle',
                                'non-aktif', 'matikan' => 'heroicon-m-x-circle',
                                default => 'heroicon-m-question-mark-circle',
                            })
                            ->placeholder('-'),

                        TextEntry::make('nama_komoditas')
                            ->label('Informasi Komoditas')
                            ->weight(FontWeight::SemiBold)
                            ->size('md')
                            ->color('gray')
                            ->helperText(fn($record) => '📦 Kategori: ' . ($record->kategori ?? '-') . ' | ⚖️ Satuan: ' . ($record->satuan ?? '-'))
                            ->columnSpanFull()
                            ->placeholder('-'),

                        TextEntry::make('deskripsi')
                            ->label('Deskripsi')
                            ->state(
                                fn($record) => $record->deskripsi
                                ? '💡 ' . str($record->deskripsi)->limit(120)
                                : 'Tidak ada deskripsi tambahan'
                            )
                            ->color('gray')
                            ->columnSpanFull()
                            ->placeholder('-'),

                        TextEntry::make('kategori')
                            ->label('Kategori')
                            ->badge()
                            ->color('info')
                            ->placeholder('-'),

                        TextEntry::make('satuan')
                            ->label('Satuan')
                            ->badge()
                            ->color('gray')
                            ->placeholder('-'),

                        TextEntry::make('updated_at')
                            ->label('Pembaruan')
                            ->since()
                            ->icon('heroicon-m-clock')
                            ->iconColor('gray')
                            ->color('gray')
                            ->size('sm')
                            ->placeholder('-'),

                    ]),
            ]);
    }
}
