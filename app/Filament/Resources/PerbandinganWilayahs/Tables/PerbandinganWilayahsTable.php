<?php

namespace App\Filament\Resources\PerbandinganWilayahs\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PerbandinganWilayahsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode_perbandingan')
                    ->searchable(),
                TextColumn::make('komoditas_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('wilayah_1_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('wilayah_2_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('harga_wilayah_1')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('harga_wilayah_2')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('selisih_harga')
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
