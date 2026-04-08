<?php

namespace App\Filament\Resources\DetailEvents\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\Alignment;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DetailEventsTable
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

                // Relasi ke Event Utama
                TextColumn::make('event.nama_event')
                    ->label('Nama Kegiatan')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn ($record) => "Kode: " . $record->kode_detail_event),

                TextColumn::make('penyelenggara')
                    ->label('Penyelenggara')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('narasumber')
                    ->label('Narasumber / Pemateri')
                    ->searchable()
                    ->icon('heroicon-m-user')
                    ->iconColor('info'),

                // Format Anggaran ke Rupiah
                TextColumn::make('anggaran_event')
                    ->label('Anggaran')
                    ->money('IDR', locale: 'id_ID')
                    ->sortable()
                    ->alignment(Alignment::Right)
                    ->color('success'),

                // Menampilkan Kuota dengan Ikon
                TextColumn::make('kuota_peserta')
                    ->label('Kuota')
                    ->numeric()
                    ->suffix(' Orang')
                    ->alignment(Alignment::Center)
                    ->sortable(),

                TextColumn::make('deskripsi_event')
                    ->label('Deskripsi')
                    ->limit(30)
                    ->toggleable(isToggledHiddenByDefault: true),

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
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
