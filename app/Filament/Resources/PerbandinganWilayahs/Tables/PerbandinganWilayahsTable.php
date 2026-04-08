<?php

namespace App\Filament\Resources\PerbandinganWilayahs\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Support\Enums\Alignment;

class PerbandinganWilayahsTable
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

                TextColumn::make('kode_perbandingan')
                    ->label('Kode')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->fontFamily('mono'),

                TextColumn::make('komoditas.nama_komoditas')
                    ->label('Komoditas')
                    ->searchable()
                    ->sortable(),

                // Kolom Perbandingan Wilayah 1
                TextColumn::make('wilayah1.nama_wilayah')
                    ->label('Wilayah 1')
                    ,

                // Kolom Perbandingan Wilayah 2
                TextColumn::make('wilayah2.nama_wilayah')
                    ->label('Wilayah 2')
                    ,

                // Indikator Selisih (Disparitas)
                TextColumn::make('selisih_harga')
                    ->label('Selisih Harga')
                    ->money('IDR', locale: 'id_ID')
                    ->sortable()
                    ->alignment(Alignment::Right)
                    ->color(fn ($state) => $state > 5000 ? 'danger' : 'warning')
                    ->weight('bold'),

                // Status Disparitas Berdasarkan Persentase (Opsional jika ada di kolom lain)
                TextColumn::make('keterangan')
                    ->label('Analisis Keterangan')
                    ->limit(30)
                    ->tooltip(fn ($state) => $state) // Munculkan teks lengkap saat hover
                    ->toggleable(),
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
