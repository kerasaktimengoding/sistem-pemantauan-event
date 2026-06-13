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
use Filament\Tables\Filters\SelectFilter;

class KehadiranEventsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // 1. Nomor Urut Otomatis
                TextColumn::make('No')
                    ->rowIndex()
                    ->label('No.')
                    ->width('50px')
                    ->alignment(Alignment::Center),

                // 2. Waktu Kedatangan & ID Transaksi (Penggabungan Berbasis Waktu)
                TextColumn::make('waktu_kehadiran')
                    ->label('Waktu Kedatangan')
                    ->dateTime('d M Y, H:i') // Output rapi: 06 Apr 2026, 14:30
                    ->sortable()
                    ->weight('bold')
                    ->color('primary') // Warna biru interaktif agar menonjol sebagai log waktu
                    ->fontFamily('mono') // Karakter angka sejajar lurus vertikal kebawah
                    ->icon('heroicon-m-calendar-days')
                    ->iconColor('primary')
                    // Menampilkan Kode Kehadiran dengan font kecil di bawah log waktu
                    ->description(fn($record) => "Log ID: " . ($record->kode_kehadiran ?? '-')),

                // 3. Nama Peserta & Entitas Bisnis Usaha (Profil Dua Jalur)
                TextColumn::make('pesertaevent.nama_peserta')
                    ->label('Nama Peserta / Profil Usaha')
                    ->searchable(query: function ($query, string $search) {
                        // Pencarian pintar: mencakup nama peserta sekaligus nama usaha relasinya
                        $query->whereHas('pesertaevent', function ($q) use ($search) {
                            $q->where('nama_peserta', 'like', "%{$search}%")
                                ->orWhere('nama_usaha', 'like', "%{$search}%");
                        });
                    })
                    ->sortable()
                    ->weight('bold')
                    ->size('lg') // Visual Anchor utama agar fokus mata tertuju pada profil orang
                    ->icon('heroicon-m-user-circle')
                    ->iconColor('gray')
                    // Menggabungkan nama usaha di bawah nama orang dengan warna abu-abu elegan
                    ->description(fn($record) => "Usaha: " . ($record->pesertaevent->nama_usaha ?? '-')),

                // 4. Status Kehadiran dengan Ikon & Badge Solid Kontras Tinggi
                TextColumn::make('status_kehadiran')
                    ->label('Status Presensi')
                    ->badge()
                    ->alignment(Alignment::Center)
                    ->color(fn(string $state): string => match ($state) {
                        'Hadir' => 'success',      // Hijau segar
                        'Izin' => 'warning',       // Amber/Kuning perhatian
                        'Terlambat' => 'danger',   // Merah tegas
                        default => 'gray',         // Abu-abu netral
                    })
                    ->icon(fn(string $state): string => match ($state) {
                        'Hadir' => 'heroicon-m-check-badge',
                        'Izin' => 'heroicon-m-envelope-open',
                        'Terlambat' => 'heroicon-m-exclamation-triangle',
                        default => 'heroicon-m-question-mark-circle',
                    }),

                // 5. Catatan / Alasan / Keterangan Tambahan dengan Tooltip Interaktif
                TextColumn::make('catatan')
                    ->label('Catatan/Keterangan')
                    ->limit(35) // Memotong teks panjang agar layout horizontal tabel tetap stabil
                    ->placeholder('Tidak ada catatan')
                    ->color('gray')
                    ->icon('heroicon-m-document-text')
                    ->iconColor('gray')
                    // Memunculkan balon teks (tooltip) utuh saat kursor diarahkan ke catatan yang terpotong
                    ->tooltip(
                        fn(TextColumn $column, $record): ?string =>
                        $record->catatan && strlen($record->catatan) > $column->getCharacterLimit() ? $record->catatan : null
                    )
                    ->toggleable(isToggledHiddenByDefault: false),

                // 6. Metadata Audit Log Sistem (Disembunyikan secara Default)
                TextColumn::make('created_at')
                    ->label('Sistem Record')
                    ->dateTime('d M Y, H:i:s')
                    ->fontFamily('mono')
                    ->color('gray')
                    ->size('sm')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('waktu_kehadiran', 'desc')
            ->filters([
                //
                SelectFilter::make('status_kehadiran')
                    ->label('Status Kehadiran')
                    ->options([
                        'Hadir' => 'Hadir',
                        'Izin' => 'Izin',
                        'Terlambat' => 'Terlambat',
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
