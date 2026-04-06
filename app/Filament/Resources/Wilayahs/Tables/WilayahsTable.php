<?php

namespace App\Filament\Resources\Wilayahs\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\Alignment;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class WilayahsTable
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

                TextColumn::make('kode_wilayah')
                    ->label('Kode Wilayah')
                    ->searchable()
                    ->sortable()
                    ->copyable() // Fitur tambahan: bisa diklik untuk copy kode
                    ->weight('bold'),

                TextColumn::make('nama_wilayah')
                    ->label('Nama Wilayah')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('tipe_wilayah')
                    ->label('Tipe Wilayah')
                    ->searchable()
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Kecamatan' => 'info',
                        'Desa' => 'success',
                        'Kelurahan' => 'warning',
                        default => 'secondary',
                    }),

                TextColumn::make('kode_pos')
                    ->label('Kode Pos')
                    ->searchable()
                    ->alignment(Alignment::Center),

                TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d M Y H:i')
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
