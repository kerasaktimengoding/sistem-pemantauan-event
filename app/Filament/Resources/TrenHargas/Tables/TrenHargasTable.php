<?php

namespace App\Filament\Resources\TrenHargas\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TrenHargasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('komoditas_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('wilayah_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('periode_tren')
                    ->date()
                    ->sortable(),
                TextColumn::make('harga_awal')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('harga_akhir')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('arah_tren')
                    ->searchable(),
                TextColumn::make('persentase_perubahan')
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
