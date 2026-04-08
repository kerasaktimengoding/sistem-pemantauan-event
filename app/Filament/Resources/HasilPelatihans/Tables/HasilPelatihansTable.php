<?php

namespace App\Filament\Resources\HasilPelatihans\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\Alignment;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\DeleteAction;

class HasilPelatihansTable
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

                // Informasi Peserta
                TextColumn::make('pesertaevent.nama_peserta')
                    ->label('Nama Peserta')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn ($record) => "ID: " . $record->kode_hasil_pelatihan),

                // Nilai-Nilai (Dibuat berdampingan untuk komparasi)
                TextColumn::make('nilai_pretest')
                    ->label('Pre-Test')
                    ->numeric(decimalPlaces: 2)
                    ->sortable()
                    ->alignment(Alignment::Center),

                TextColumn::make('nilai_posttest')
                    ->label('Post-Test')
                    ->numeric(decimalPlaces: 2)
                    ->sortable()
                    ->alignment(Alignment::Center)
                    ->color('primary'),

                TextColumn::make('nilai_akhir')
                    ->label('Nilai Akhir')
                    ->numeric(decimalPlaces: 2)
                    ->sortable()
                    ->alignment(Alignment::Center)
                    ->weight('bold')
                    ->color(fn ($state) => $state >= 75 ? 'success' : 'danger'),

                // Status Kelulusan dengan Badge
                TextColumn::make('status_kelulusan')
                    ->label('Hasil Akhir')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Lulus' => 'success',
                        'Tidak Lulus' => 'danger',
                        'Remedial' => 'warning',
                        default => 'secondary',
                    })
                    ->icon(fn (string $state): string => match ($state) {
                        'Lulus' => 'heroicon-o-academic-cap',
                        'Tidak Lulus' => 'heroicon-o-x-circle',
                        'Remedial' => 'heroicon-o-arrow-path',
                        default => 'heroicon-o-minus',
                    }),

                TextColumn::make('catatan')
                    ->label('Catatan')
                    ->limit(20)
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
