<?php

namespace App\Filament\Resources\Pegawais\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\Alignment;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\DeleteAction;

class PegawaisTable
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

                TextColumn::make('nama_pegawai')
                    ->label('Nama Pegawai')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn ($record) => "NIP: " . $record->nip),

                TextColumn::make('nik')
                    ->label('NIK')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                // Menampilkan Jabatan (Relasi)
                TextColumn::make('jabatan.nama_jabatan')
                    ->label('Jabatan')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('info'),

                // Menampilkan Wilayah Tugas (Relasi)
                TextColumn::make('wilayah.nama_wilayah')
                    ->label('Wilayah Tugas')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->label('Email')
                    ->icon('heroicon-m-envelope')
                    ->iconColor('primary')
                    ->searchable(),

                TextColumn::make('no_hp')
                    ->label('No. Kontak')
                    ->copyable()
                    ->searchable(),

                TextColumn::make('status_pegawai')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Aktif' => 'success',
                        'Non-Aktif' => 'danger',
                        'Cuti' => 'warning',
                        default => 'secondary',
                    }),

                TextColumn::make('tanggal_masuk')
                    ->label('Tgl Masuk')
                    ->date('d M Y')
                    ->sortable()
                    ->toggleable(),
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
