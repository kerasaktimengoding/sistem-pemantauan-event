<?php

namespace App\Filament\Resources\EventKegiatans\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\Alignment;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\DeleteAction;

class EventKegiatansTable
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

                TextColumn::make('nama_event')
                    ->label('Nama Kegiatan')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn ($record) => "Kode: " . $record->kode_event),

                TextColumn::make('jenis_event')
                    ->label('Jenis')
                    ->badge()
                    ->color('gray')
                    ->searchable(),

                // Menampilkan rentang tanggal kegiatan
                TextColumn::make('tanggal_mulai')
                    ->label('Pelaksanaan')
                    ->date('d M Y')
                    ->sortable()
                    ->description(fn ($record) => "s/d " . \Carbon\Carbon::parse($record->tanggal_selesai)->format('d M Y')),

                TextColumn::make('wilayah.nama_wilayah')
                    ->label('Wilayah')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('lokasi_event')
                    ->label('Lokasi Detail')
                    ->limit(30)
                    ->searchable(),

                TextColumn::make('status_event')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Terjadwal' => 'info',
                        'Berlangsung' => 'warning',
                        'Selesai' => 'success',
                        'Dibatalkan' => 'danger',
                        default => 'secondary',
                    })
                    ->icon(fn (string $state): string => match ($state) {
                        'Terjadwal' => 'heroicon-o-calendar',
                        'Berlangsung' => 'heroicon-o-play-circle',
                        'Selesai' => 'heroicon-o-check-badge',
                        'Dibatalkan' => 'heroicon-o-x-circle',
                        default => 'heroicon-o-clock',
                    }),
            ])
            ->defaultSort('tanggal_mulai', 'desc')
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
