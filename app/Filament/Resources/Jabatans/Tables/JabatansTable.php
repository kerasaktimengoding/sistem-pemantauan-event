<?php

namespace App\Filament\Resources\Jabatans\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\Alignment;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\DeleteAction;

class JabatansTable
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

                TextColumn::make('kode_jabatan')
                    ->label('Kode Jabatan')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->weight('bold'),

                TextColumn::make('nama_jabatan')
                    ->label('Nama Jabatan')
                    ->searchable()
                    ->sortable(),

                // Menampilkan tugas pokok dengan batas karakter agar tidak merusak layout
                TextColumn::make('tugas_pokok')
                    ->label('Tugas Pokok')
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->tugas_pokok) // Munculkan teks lengkap saat kursor diarahkan
                    ->searchable(),

                TextColumn::make('status_jabatan')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Aktif' => 'success',
                        'Non-Aktif' => 'danger',
                        default => 'secondary',
                    })
                    ->icon(fn (string $state): string => match ($state) {
                        'Aktif' => 'heroicon-o-check-circle',
                        'Non-Aktif' => 'heroicon-o-x-circle',
                        default => 'heroicon-o-minus-circle',
                    }),

                TextColumn::make('created_at')
                    ->label('Terdaftar Pada')
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
