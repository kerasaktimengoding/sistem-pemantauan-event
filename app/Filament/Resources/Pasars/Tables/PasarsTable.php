<?php

namespace App\Filament\Resources\Pasars\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\Alignment;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\DeleteAction;

class PasarsTable
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

                TextColumn::make('nama_pasar')
                    ->label('Nama Pasar')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn ($record) => "Kode: " . $record->kode_pasar),

                // Menampilkan Wilayah (Relasi)
                TextColumn::make('wilayah.nama_wilayah')
                    ->label('Wilayah / Kecamatan')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('info'),

                TextColumn::make('alamat_pasar')
                    ->label('Alamat Lengkap')
                    ->limit(50)
                    ->searchable()
                    ->wrap(), // Membungkus teks agar tidak terlalu panjang ke samping

                TextColumn::make('status_pasar')
                    ->label('Status Operasional')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Aktif' => 'success',
                        'Non-Aktif' => 'danger',
                        'Renovasi' => 'warning',
                        default => 'secondary',
                    })
                    ->icon(fn (string $state): string => match ($state) {
                        'Aktif' => 'heroicon-o-building-storefront',
                        'Non-Aktif' => 'heroicon-o-x-circle',
                        'Renovasi' => 'heroicon-o-wrench-screwdriver',
                        default => 'heroicon-o-question-mark-circle',
                    }),

                TextColumn::make('updated_at')
                    ->label('Terakhir Diperbarui')
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
