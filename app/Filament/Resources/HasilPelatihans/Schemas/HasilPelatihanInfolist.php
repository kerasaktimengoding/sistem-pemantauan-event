<?php

namespace App\Filament\Resources\HasilPelatihans\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontFamily;
use Filament\Support\Enums\FontWeight;
use Filament\Schemas\Components\Section;

class HasilPelatihanInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Hasil Pelatihan')
                    ->columns(2)
                    ->schema([

                        TextEntry::make('pesertaevent.nama_peserta')
                            ->label('Informasi Peserta')
                            ->weight(FontWeight::Bold)
                            ->color('gray')
                            ->icon('heroicon-m-user')
                            ->helperText(fn($record) => 'ID Pelatihan: ' . ($record->kode_hasil_pelatihan ?? '-'))
                            ->columnSpanFull()
                            ->placeholder('-'),

                        TextEntry::make('status_kelulusan')
                            ->label('Hasil Akhir')
                            ->badge()
                            ->color(fn($state): string => match ((string) $state) {
                                'Lulus' => 'success',
                                'Tidak Lulus' => 'danger',
                                'Remedial' => 'warning',
                                default => 'gray',
                            })
                            ->icon(fn($state): string => match ((string) $state) {
                                'Lulus' => 'heroicon-m-academic-cap',
                                'Tidak Lulus' => 'heroicon-m-x-circle',
                                'Remedial' => 'heroicon-m-arrow-path',
                                default => 'heroicon-m-minus-small',
                            })
                            ->placeholder('-'),

                        TextEntry::make('kode_hasil_pelatihan')
                            ->label('Kode Hasil Pelatihan')
                            ->copyable()
                            ->copyMessage('Kode hasil pelatihan berhasil disalin')
                            ->fontFamily(FontFamily::Mono)
                            ->weight(FontWeight::Bold)
                            ->color('primary')
                            ->placeholder('-'),

                        TextEntry::make('nilai_pretest')
                            ->label('Pre-Test')
                            ->numeric(decimalPlaces: 2)
                            ->fontFamily(FontFamily::Mono)
                            ->color('gray')
                            ->helperText('Awal')
                            ->placeholder('-'),

                        TextEntry::make('nilai_posttest')
                            ->label('Post-Test')
                            ->numeric(decimalPlaces: 2)
                            ->fontFamily(FontFamily::Mono)
                            ->weight(FontWeight::SemiBold)
                            ->color('primary')
                            ->helperText(function ($record) {
                                $pretest = (float) ($record->nilai_pretest ?? 0);
                                $posttest = (float) ($record->nilai_posttest ?? 0);
                                $selisih = $posttest - $pretest;

                                return $selisih >= 0
                                    ? "📈 Naik (+{$selisih})"
                                    : "📉 Turun ({$selisih})";
                            })
                            ->placeholder('-'),

                        TextEntry::make('nilai_akhir')
                            ->label('Nilai Akhir')
                            ->numeric(decimalPlaces: 2)
                            ->fontFamily(FontFamily::Mono)
                            ->weight(FontWeight::Bold)
                            ->color(fn($state) => match (true) {
                                (float) $state >= 85 => 'success',
                                (float) $state >= 75 => 'info',
                                (float) $state >= 60 => 'warning',
                                default => 'danger',
                            })
                            ->helperText('KKM: 75.00')
                            ->placeholder('-'),

                        TextEntry::make('catatan')
                            ->label('Catatan Evaluasi')
                            ->limit(100)
                            ->tooltip(fn($state) => $state)
                            ->color('gray')
                            ->columnSpanFull()
                            ->placeholder('Tidak ada catatan'),

                        TextEntry::make('updated_at')
                            ->label('Pembaruan')
                            ->dateTime('d M Y, H:i')
                            ->fontFamily(FontFamily::Mono)
                            ->color('gray')
                            ->placeholder('-'),

                    ]),
            ]);
    }
}
