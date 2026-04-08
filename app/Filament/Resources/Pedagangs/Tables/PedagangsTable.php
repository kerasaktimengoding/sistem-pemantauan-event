<?php

namespace App\Filament\Resources\Pedagangs\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\Alignment;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\DeleteAction;

class PedagangsTable
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

                TextColumn::make('nama_pedagang')
                    ->label('Nama Pedagang')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn ($record) => "Kode: " . $record->kode_pedagang),

                TextColumn::make('nik')
                    ->label('NIK')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('jenis_tempat')
                    ->label('Tempat Usaha')
                    ->badge()
                    ->color('info')
                    ->searchable(),

                // Menampilkan Wilayah (Relasi)
                TextColumn::make('wilayah.nama_wilayah')
                    ->label('Wilayah / Kecamatan')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('no_hp')
                    ->label('No. WhatsApp')
                    ->icon('heroicon-m-phone')
                    ->iconColor('success')
                    ->copyable()
                    ->searchable(),

                TextColumn::make('status_pedagang')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Aktif' => 'success',
                        'Non-Aktif' => 'danger',
                        'Tersuspend' => 'warning',
                        default => 'secondary',
                    }),

                TextColumn::make('alamat')
                    ->label('Alamat')
                    ->limit(30)
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
