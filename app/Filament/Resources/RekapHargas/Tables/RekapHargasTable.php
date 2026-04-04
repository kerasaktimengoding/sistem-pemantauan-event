<?php

namespace App\Filament\Resources\RekapHargas\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RekapHargasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode_rekap_harga')
                    ->searchable(),
                TextColumn::make('komoditas_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('wilayah_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('periode_rekap')
                    ->date()
                    ->sortable(),
                TextColumn::make('harga_rata_rata')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('harga_maksimum')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('harga_minimum')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
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
