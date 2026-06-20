<?php

namespace App\Filament\Resources\Tempats\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\FontWeight;
use Filament\Actions\DeleteAction;
use Filament\Tables\Filters\SelectFilter;

class TempatsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // 1. Kolom Nomor Urut Ringkas
                TextColumn::make('No')
                    ->rowIndex()
                    ->label('No.')
                    ->alignment(Alignment::Center)
                    ->width('50px'),

                // 2. Kode Tempat Usaha (Identitas Utama)
                TextColumn::make('kode_tempat_usaha')
                    ->label('Kode Tempat')
                    ->searchable()
                    ->sortable()
                    ->copyable() // Admin bisa langsung copas kode dengan sekali klik
                    ->copyMessage('Kode tempat berhasil disalin')
                    ->weight(FontWeight::Bold)
                    ->color('primary'),

                // 3. Nomor / Nama Blok Tempat
                TextColumn::make('nomor_tempat')
                    ->label('No. Tempat / Blok')
                    ->searchable()
                    ->sortable()
                    ->alignment(Alignment::Center)
                    ->description(fn($record) => "Jenis Tempat: " . $record->jenis_tempat),

                // 4. Luas Tempat dengan Suffix Satuan Metrik
                TextColumn::make('luas_tempat')
                    ->label('Luas Ukuran')
                    ->sortable()
                    ->suffix(' m²')
                    ->alignment(Alignment::Right)
                    ->weight(FontWeight::Medium),

                // 5. Penyatuan Data Pasar & Lokasi Desa (Efisiensi Kolom)
                TextColumn::make('pasar.nama_pasar')
                    ->label('Lokasi Pasar')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::SemiBold)
                    ->description(fn($record) => "Wilayah: " . ($record->desa->nama_desa ?? '-')),

                // 6. Penyatuan Data Pedagang & Kontak WhatsApp Interaktif
                TextColumn::make('pedagang.nama_pedagang')
                    ->label('Pengelola / Pedagang')
                    ->searchable(['nama_pedagang', 'nomor_hp']) // Smart Search: Ketik No HP di kolom pencarian akan langsung ketemu
                    ->sortable()
                    ->weight(FontWeight::Medium)
                    ->description(fn($record) => $record->nomor_hp ? "📞 " . $record->nomor_hp : "📞 -")
                    // Mengubah kolom menjadi tombol panggil WhatsApp otomatis jika diklik
                    ->url(fn($record) => $record->nomor_hp ? "https://wa.me/" . preg_replace('/[^0-9]/', '', $record->nomor_hp) : null)
                    ->openUrlInNewTab()
                    ->color(fn($record) => $record->nomor_hp ? 'success' : null),

                // 7. Badge Status Dinamis & Kebal Error Typo Database
                TextColumn::make('status_tempat')
                    ->label('Status')
                    ->badge()
                    ->alignment(Alignment::Center)
                    ->color(fn(string $state): string => match (strtolower(trim($state))) {
                        'aktif', 'terisi', 'tersewa' => 'success',    // Hijau
                        'kosong', 'tersedia' => 'gray',              // Abu-abu
                        'perbaikan', 'rusak', 'renovasi' => 'danger', // Merah
                        'booking', 'dipesan' => 'warning',           // Kuning/Emas
                        default => 'info',                            // Biru (Cadangan)
                    }),

                // 8. Timestamps Otomatis (Hidden by Default)
                TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Diperbarui Pada')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

            ])
            ->filters([
                SelectFilter::make('Status')
                    ->label('Status Tempat')
                    ->options([
                        'aktif' => 'Aktif',
                        'Terisi' => 'Terisi',
                        'Tersewa' => 'Tersewa',
                        'Perbaikan' => 'Perbaikan',
                        'Rusak' => 'Rusak',
                        'Renovasi' => 'Renovasi',
                        'Booking' => 'Booking',
                        'Dipesan' => 'Dipesan',

                    ]),


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
