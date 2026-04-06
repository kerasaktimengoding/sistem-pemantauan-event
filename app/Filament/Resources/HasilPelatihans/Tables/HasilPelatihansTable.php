<?php

namespace App\Filament\Resources\HasilPelatihans\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class HasilPelatihansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode_hasil_pelatihan')
                    ->searchable(),
                TextColumn::make('peserta_event_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('nilai_pretest')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('nilai_posttest')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('nilai_akhir')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('status_kelulusan')
                    ->searchable(),
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
