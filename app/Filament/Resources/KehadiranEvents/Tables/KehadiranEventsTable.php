<?php

namespace App\Filament\Resources\KehadiranEvents\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\Alignment;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\DeleteAction;

class KehadiranEventsTable
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

                // Menampilkan Waktu Kehadiran (Jam & Menit)
                TextColumn::make('waktu_kehadiran')
                    ->label('Waktu Kedatangan')
                    ->dateTime('d M Y, H:i') // Contoh: 06 Apr 2026, 14:30
                    ->sortable()
                    ->description(fn ($record) => "ID: " . $record->kode_kehadiran),

                // Relasi ke Nama Peserta & Nama Usaha
                TextColumn::make('pesertaevent.nama_peserta')
                    ->label('Nama Peserta')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn ($record) => $record->pesertaevent->nama_usaha ?? '-'),

                // Status Kehadiran dengan Badge
                TextColumn::make('status_kehadiran')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Hadir' => 'success',
                        'Izin' => 'warning',
                        'Terlambat' => 'danger',
                        default => 'secondary',
                    })
                    ->icon(fn (string $state): string => match ($state) {
                        'Hadir' => 'heroicon-o-check-circle',
                        'Izin' => 'heroicon-o-envelope',
                        'Terlambat' => 'heroicon-o-clock',
                        default => 'heroicon-o-question-mark-circle',
                    }),

                TextColumn::make('catatan')
                    ->label('Catatan/Keterangan')
                    ->limit(30)
                    ->placeholder('Tidak ada catatan')
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('Sistem Record')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('waktu_kehadiran', 'desc')
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
