<?php

namespace App\Filament\Resources\PesertaEvents\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\Alignment;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\DeleteAction;

class PesertaEventsTable
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

                TextColumn::make('nama_peserta')
                    ->label('Peserta')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn ($record) => "NIK: " . $record->nik),

                TextColumn::make('nama_usaha')
                    ->label('Nama Usaha')
                    ->searchable()
                    ->sortable()
                    ->description(fn ($record) => "Produk: " . $record->jenis_produk),

                // Menampilkan Event yang diikuti (Relasi)
                TextColumn::make('event.nama_event')
                    ->label('Event')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('info'),

                TextColumn::make('no_hp')
                    ->label('WhatsApp')
                    ->icon('heroicon-m-phone')
                    ->iconColor('success')
                    ->copyable()
                    ->searchable(),

                // Lokasi Peserta
                TextColumn::make('wilayah.nama_wilayah')
                    ->label('Wilayah')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('status_partisipasi')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Terdaftar' => 'info',
                        'Hadir' => 'success',
                        'Batal' => 'danger',
                        default => 'secondary',
                    }),

                TextColumn::make('tanggal_registrasi')
                    ->label('Tgl Daftar')
                    ->date('d M Y')
                    ->sortable()
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
