<?php

namespace App\Filament\Resources\Komoditas\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\Alignment;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\DeleteAction;

class KomoditasTable
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

                TextColumn::make('kode_komoditas')
                    ->label('Kode')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->weight('bold'),

                TextColumn::make('nama_komoditas')
                    ->label('Nama Komoditas')
                    ->searchable()
                    ->sortable()
                    ->description(fn ($record) => $record->deskripsi), // Menampilkan deskripsi singkat di bawah nama

                // Menampilkan Nama Kategori hasil relasi
                TextColumn::make('kategori.nama_kategori')
                    ->label('Kategori')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('info'),

                // Menampilkan Nama Satuan hasil relasi
                TextColumn::make('satuan.nama_satuan')
                    ->label('Satuan')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('status_komoditas')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Aktif' => 'success',
                        'Non-Aktif' => 'danger',
                        'Terbatas' => 'warning',
                        default => 'secondary',
                    }),

                TextColumn::make('updated_at')
                    ->label('Update Terakhir')
                    ->dateTime('d M Y')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
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
