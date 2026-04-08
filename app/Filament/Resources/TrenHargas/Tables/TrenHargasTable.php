<?php

namespace App\Filament\Resources\TrenHargas\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\Alignment;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\DeleteAction;

class TrenHargasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
               
                TextColumn::make('No')
                    ->rowIndex()
                    ->label('No.')
                    ->width('50px')
                    ->alignment(Alignment::Center),

                TextColumn::make('periode_tren')
                    ->label('Periode')
                    ->date('M Y') // Contoh: Apr 2026
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('komoditas.nama_komoditas')
                    ->label('Komoditas')
                    ->searchable()
                    ->sortable()
                    ->description(fn ($record) => "Wilayah: " . ($record->wilayah->nama_wilayah ?? '-')),

                // Perbandingan Harga
                TextColumn::make('harga_awal')
                    ->label('Harga Awal')
                    ->money('IDR', locale: 'id_ID')
                    ->alignment(Alignment::Right),
                

                TextColumn::make('harga_akhir')
                    ->label('Harga Akhir')
                    ->money('IDR', locale: 'id_ID')
                    ->alignment(Alignment::Right)
                    ->weight('bold'),

                // Indikator Arah Tren (Logika Warna & Ikon)
                TextColumn::make('arah_tren')
                    ->label('Kondisi')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Naik' => 'danger',
                        'Turun' => 'success',
                        'Stabil' => 'info',
                        default => 'gray',
                    })
                    ->icon(fn (string $state): string => match ($state) {
                        'Naik' => 'heroicon-m-arrow-trending-up',
                        'Turun' => 'heroicon-m-arrow-trending-down',
                        'Stabil' => 'heroicon-m-minus',
                        default => 'heroicon-m-question-mark-circle',
                    }),

                // Persentase Perubahan
                TextColumn::make('persentase_perubahan')
                    ->label('Selisih (%)')
                    ->suffix('%')
                    ->alignment(Alignment::Center)
                    ->color(fn ($record) => $record->arah_tren === 'Naik' ? 'danger' : ($record->arah_tren === 'Turun' ? 'success' : 'gray'))
                    ->weight('bold'),

                TextColumn::make('updated_at')
                    ->label('Update')
                    ->dateTime('d/m/Y')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('periode_tren', 'desc')
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                 DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
