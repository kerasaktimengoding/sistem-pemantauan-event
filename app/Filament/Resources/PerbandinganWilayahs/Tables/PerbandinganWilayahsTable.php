<?php

namespace App\Filament\Resources\PerbandinganWilayahs\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Support\Enums\Alignment;
use Filament\Actions\DeleteAction;
use Filament\Support\Enums\FontWeight;

class PerbandinganWilayahsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // 1. Nomor Urut Desain Minimalis
                TextColumn::make('No')
                    ->rowIndex()
                    ->label('No.')
                    ->width('50px')
                    ->alignment(Alignment::Center),

                // 2. Kode Transaksi / Perbandingan dengan Pencarian Lintas Relasi
                TextColumn::make('kode_perbandingan')
                    ->label('Cakupan Data')
                    ->weight(FontWeight::Bold)
                    ->fontFamily('mono')
                    ->searchable()
                    ->sortable()
                    ->description(fn($record) => '📦 ' . ($record->komoditas->nama_komoditas ?? '-')),

                // 3. Penyatuan Dua Wilayah Komparasi (Menghemat Ruang Horizontal)
                TextColumn::make('desa1.nama_desa')
                    ->label('Titik Komparasi Wilayah')
                    ->weight(FontWeight::Medium)
                    ->color('primary')
                    ->searchable(query: function ($query, string $search) {
                        $query->whereHas('desa1', fn($q) => $q->where('nama_desa', 'like', "%{$search}%"))
                            ->orWhereHas('desa2', fn($q) => $q->where('nama_desa', 'like', "%{$search}%"));
                    })
                    ->description(fn($record) => '⚔️ Dibandingkan dengan: ' . ($record->desa2->nama_desa ?? '-')),

                // 4. Metrik Finansial Selisih Harga (Aksesibilitas Tinggi)
                TextColumn::make('selisih_harga')
                    ->label('Selisih Harga')
                    ->money('IDR', locale: 'id_ID')
                    ->fontFamily('mono')
                    ->weight(FontWeight::Bold)
                    ->sortable()
                    ->alignment(Alignment::Right)
                    ->color(fn($state) => $state > 5000 ? 'danger' : ($state > 2000 ? 'warning' : 'success'))
                    ->formatStateUsing(fn($state) => "Rp " . number_format($state, 0, ',', '.')),

                // 5. Transformasi Analisis Keterangan Menjadi Solid Badge & Micro-Icon
                TextColumn::make('keterangan')
                    ->label('Analisis Disparitas')
                    ->badge()
                    ->alignment(Alignment::Center)
                    ->searchable()
                    // Pewarnaan dinamis berdasarkan tingkat keparahan selisih harga
                    ->color(fn($record) => match (true) {
                        $record->selisih_harga > 5000 => 'danger',   // Disparitas Tinggi / Rawan
                        $record->selisih_harga > 2000 => 'warning',  // Disparitas Sedang
                        default => 'success',                        // Stabil / Normal
                    })
                    // Ikon otomatis yang berubah sesuai kondisi disparitas pasar
                    ->icon(fn($record) => match (true) {
                        $record->selisih_harga > 5000 => 'heroicon-m-exclamation-triangle',
                        $record->selisih_harga > 2000 => 'heroicon-m-arrow-path',
                        default => 'heroicon-m-check-badge',
                    })
                    ->limit(35)
                    ->tooltip(fn($state) => $state)
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
