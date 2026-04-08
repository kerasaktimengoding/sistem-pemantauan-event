<?php

namespace App\Filament\Resources\RekapHargas\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\Alignment;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RekapHargasTable
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

                TextColumn::make('periode_rekap')
                    ->label('Periode')
                    ->date('F Y') // Contoh: April 2026
                    ->sortable()
                    ->weight('bold')
                    ->description(fn ($record) => "ID: " . $record->kode_rekap_harga),

                TextColumn::make('komoditas.nama_komoditas')
                    ->label('Komoditas')
                    ->searchable()
                    ->sortable()
                    ->description(fn ($record) => "Wilayah: " . ($record->wilayah->nama_wilayah ?? '-')),

                // Statistik Harga (Rata-rata)
                TextColumn::make('harga_rata_rata')
                    ->label('Rata-rata')
                    ->money('IDR', locale: 'id_ID')
                    ->sortable()
                    ->alignment(Alignment::Right)
                    ->color('primary')
                    ->weight('bold'),

                // Batas Harga Atas
                TextColumn::make('harga_maksimum')
                    ->label('Tertinggi')
                    ->money('IDR', locale: 'id_ID')
                    ->sortable()
                    ->alignment(Alignment::Right)
                    ->color('danger'),

                // Batas Harga Bawah
                TextColumn::make('harga_minimum')
                    ->label('Terendah')
                    ->money('IDR', locale: 'id_ID')
                    ->sortable()
                    ->alignment(Alignment::Right)
                    ->color('success'),

                TextColumn::make('updated_at')
                    ->label('Terakhir Update')
                    ->dateTime('d/m/Y H:i')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('periode_rekap', 'desc')
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
