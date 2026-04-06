<?php

namespace App\Filament\Resources\Satuans\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\Alignment;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SatuansTable
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

                TextColumn::make('kode_satuan')
                    ->label('Kode Satuan')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->weight('bold')
                    ->description(fn ($record) => "ID: " . $record->id), // Sub-teks kecil untuk info tambahan

                TextColumn::make('nama_satuan')
                    ->label('Nama Satuan')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Waktu Input')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Terakhir Diubah')
                    ->dateTime('d M Y H:i')
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
