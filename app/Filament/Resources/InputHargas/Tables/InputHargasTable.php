<?php

namespace App\Filament\Resources\InputHargas\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Support\Enums\Alignment;

class InputHargasTable
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

                TextColumn::make('tanggal_input')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable()
                    ->searchable(),

                // Menampilkan Komoditas + Satuan sebagai deskripsi
                TextColumn::make('komoditas.nama_komoditas')
                    ->label('Komoditas')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn ($record) => "Satuan: " . ($record->komoditas->satuan->nama_satuan ?? '-')),

                // Fokus pada Nominal Harga
                TextColumn::make('harga')
                    ->label('Harga Jual')
                    ->money('IDR', locale: 'id_ID')
                    ->sortable()
                    ->alignment(Alignment::Right)
                    ->color('primary')
                    ->weight('bold'),

                // Lokasi Pemantauan (Pasar & Wilayah)
                TextColumn::make('pasar.nama_pasar')
                    ->label('Lokasi Pasar')
                    ->searchable()
                    ->sortable()
                    ->description(fn ($record) => $record->wilayah->nama_wilayah ?? '-'),

                // Informasi Sumber & Petugas
                TextColumn::make('pegawai.nama_pegawai')
                    ->label('Enumerator')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('sumber_data')
                    ->label('Sumber')
                    ->badge()
                    ->color('gray')
                    ->searchable(),

                TextColumn::make('keterangan')
                    ->label('Ket.')
                    ->limit(20)
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('tanggal_input', 'desc')
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
