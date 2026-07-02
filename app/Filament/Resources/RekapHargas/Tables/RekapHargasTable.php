<?php

namespace App\Filament\Resources\RekapHargas\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\Alignment;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\DeleteAction;

class RekapHargasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // 1. Nomor Urut Otomatis (Rapi & Sejajar)
                TextColumn::make('No')
                    ->rowIndex()
                    ->label('No.')
                    ->width('50px')
                    ->alignment(Alignment::Center),

                // 2. Periode Rekapitulasi dengan Penanda Dokumen Struktural
                TextColumn::make('periode_rekap') // Sesuaikan dengan nama kolom Anda
                    ->label('Periode Rekap')
                    ->formatStateUsing(fn($state) => \Carbon\Carbon::parse($state)->isoFormat('MMMM YYYY'))
                    ->sortable()
                    ->searchable()
                    ->weight('bold')
                    ->color('primary')
                    ->icon('heroicon-m-calendar-days'),

                // 3. Komoditas (Standout Visual dengan Badge Atraktif)
                TextColumn::make('komoditas.nama_komoditas')
                    ->label('Komoditas')
                    ->searchable()
                    ->sortable()
                    ->weight('semibold')
                    ->color('gray.700')
                    ->icon('heroicon-m-shopping-bag')
                    ->iconColor('primary'),

                // 4. Integrasi Hierarki Wilayah Pintar (Kecamatan + Sub-Deskripsi Desa)
                TextColumn::make('kecamatan.nama_kecamatan')
                    ->label('Cakupan Wilayah')
                    ->sortable()
                    // Fitur Pencarian Pintar: Cari berdasarkan nama kecamatan ATAU nama desa sekaligus
                    ->searchable(query: function ($query, string $search) {
                        $query->whereHas('kecamatan', function ($q) use ($search) {
                            $q->where('nama_kecamatan', 'like', "%{$search}%");
                        })->orWhereHas('desa', function ($q) use ($search) {
                            $q->where('nama_desa', 'like', "%{$search}%");
                        });
                    })
                    ->weight('medium')
                    ->color('gray.800')
                    // Menyusun visual hierarki: Kecamatan sebagai judul utama, Desa sebagai keterangan tipis di bawahnya
                    ->description(fn($record) => $record->desa ? "📍 Desa: " . $record->desa->nama_desa : "🏢 Seluruh Kecamatan"),


                // TextColumn('pedagang.nama_pedagang')
                //     ->label('Pedagang')
                //     ->searchable()
                //     ->sortable()
                //     ->weight('semibold')
                //     ->color('gray.700')
                //     ->icon('heroicon-m-shopping-bag')
                //     ->badge()
                //     ->description(fn($record) => $record->pedagang ? "📍 Tempat: " . $record->pedagang->tempat()->nomor_tempat.' - '.$record->pedagang->tempat()->nama_tempat : "🏢 Pedagang: ")
                //     ->description(fn($record) => $record->pedagang ? "🏢 Pedagang: " . $record->pedagang->pasar_id->nama_pesar : "📍 Tempat: ")
                //     ->iconColor('primary'), 


                // 5. Statistik Harga Rata-rata (Standout Finansial Utama)
                TextColumn::make('harga_rata_rata')
                    ->label('Harga Rata-Rata')
                    ->money('IDR', locale: 'id_ID')
                    ->sortable()
                    ->alignment(Alignment::Right)
                    ->fontFamily('mono') // Font berjarak sama agar digit nominal uang sejajar vertikal
                    ->weight('bold')
                    ->color('primary')
                    // Menampilkan informasi "Spread / Selisih" harga tertinggi dan terendah secara dinamis
                    ->description(function ($record) {
                        $spread = $record->harga_maksimum - $record->harga_minimum;
                        return "Selisih: Rp " . number_format($spread, 0, ',', '.');
                    }),

                // 6. Batas Harga Atas (Tertinggi)
                TextColumn::make('harga_maksimum')
                    ->label('Tertinggi')
                    ->money('IDR', locale: 'id_ID')
                    ->sortable()
                    ->alignment(Alignment::Right)
                    ->fontFamily('mono')
                    ->color('danger')
                    ->description(fn() => "Batas Atas", position: 'above'),

                // 7. Batas Harga Bawah (Terendah)
                TextColumn::make('harga_minimum')
                    ->label('Terendah')
                    ->money('IDR', locale: 'id_ID')
                    ->sortable()
                    ->alignment(Alignment::Right)
                    ->fontFamily('mono')
                    ->color('success')
                    ->description(fn() => "Batas Bawah", position: 'above'),


                TextColumn::make('arah_tren')
                    ->label('Kondisi')
                    ->badge()
                    ->alignment(Alignment::Center)
                    ->color(fn(string $state): string => match ($state) {
                        'Naik' => 'danger',
                        'Turun' => 'success',
                        'Stabil' => 'info',
                        default => 'gray',
                    })
                    ->icon(fn(string $state): string => match ($state) {
                        'Naik' => 'heroicon-m-arrow-trending-up',
                        'Turun' => 'heroicon-m-arrow-trending-down',
                        'Stabil' => 'heroicon-m-minus',
                        default => 'heroicon-m-question-mark-circle',
                    }),

                // 8. Audit Log Waktu Sinkronisasi Sistem
                TextColumn::make('updated_at')
                    ->label('Terakhir Sinkron')
                    ->dateTime('d M Y, H:i') // Format: 04 Jun 2026, 11:38
                    ->fontFamily('mono')
                    ->color('gray.400')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('periode_rekap', 'desc')
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
