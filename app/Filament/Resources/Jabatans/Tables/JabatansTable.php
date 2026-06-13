<?php

namespace App\Filament\Resources\Jabatans\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\Alignment;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\DeleteAction;
use Filament\Support\Enums\FontFamily;
use Filament\Support\Enums\FontWeight;
use Illuminate\Support\Str;
use Filament\Tables\Filters\SelectFilter;

class JabatansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->persistColumnsInSession()
            ->persistFiltersInSession()

            ->columns([
                // 1. Nomor Urut (Desain Rapi & Center)
                TextColumn::make('No')
                    ->rowIndex()
                    ->label('No.')
                    ->width('50px')
                    ->alignment(Alignment::Center),

                // 2. Kode Jabatan (Format Monospace & Copyable Berwarna Cerah)
                TextColumn::make('kode_jabatan')
                    ->label('Kode Jabatan')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->copyMessage('Kode jabatan berhasil disalin')
                    ->fontFamily(FontFamily::Mono)
                    ->weight(FontWeight::Bold)
                    ->color('primary'),

                // 3. Nama Jabatan (PENGGABUNGAN INFORMASI: Tugas Pokok disematkan di bawahnya)
                TextColumn::make('nama_jabatan')
                    ->label('Nama & Tugas Jabatan')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Bold)
                    ->color('gray')
                    ->icon('heroicon-m-briefcase')
                    ->iconColor('primary')
                    // Menyisipkan potongan tugas pokok sebagai sub-informasi yang rapi
                    ->description(fn($record) => "📝 Tugas: " . Str::limit($record->tugas_pokok, 60, '...')),

                // 4. Wewenang (Dibatasi dengan Ringkas + Tooltip Interaktif)
                TextColumn::make('wewenang')
                    ->label('Wewenang Utama')
                    ->limit(45)
                    ->tooltip(fn($record) => $record->wewenang) // Muncul teks utuh saat hover kursor
                    ->searchable()
                    ->color('gray')
                    ->wrap(),

                // 5. Status Jabatan (Warna Kontras Tinggi, Case-Insensitive, & Menggunakan Micro Icon v3)
                TextColumn::make('status_jabatan')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match (strtolower(trim($state))) {
                        'aktif' => 'success',
                        'non-aktif', 'non aktif', 'tidak aktif' => 'danger',
                        default => 'warning',
                    })
                    ->icon(fn(string $state): string => match (strtolower(trim($state))) {
                        'aktif' => 'heroicon-m-check-circle',
                        'non-aktif', 'non aktif', 'tidak aktif' => 'heroicon-m-x-circle',
                        default => 'heroicon-m-minus-circle',
                    })
                    ->sortable()
                    ->alignment(Alignment::Center),

                // 6. Timestamps Penjejak Data (Disembunyikan default, bisa dibuka via Toggle Kolom)
                TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d M Y H:i')
                    ->color('gray')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Terakhir Diubah')
                    ->dateTime('d M Y H:i')
                    ->color('gray')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([

                
                //
                SelectFilter::make('status_jabatan')
                    ->label('Status Jabatan')
                    ->options([
                        'aktif' => 'Aktif',
                        'non-aktif' => 'Non-Aktif',
                        'non aktif' => 'Non Aktif',
                        'tidak aktif' => 'Tidak Aktif',
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
