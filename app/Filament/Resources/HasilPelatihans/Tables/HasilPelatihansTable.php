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
                // 1. Nomor Urut Otomatis (Rapi & Sejajar)
                TextColumn::make('No')
                    ->rowIndex()
                    ->label('No.')
                    ->width('50px')
                    ->alignment(Alignment::Center),

                // 2. Profil & Identitas Peserta (Scannable, Kaya Informasi)
                TextColumn::make('pesertaevent.nama_peserta')
                    ->label('Informasi Peserta')
                    ->searchable(query: function ($query, string $search) {
                        // Pencarian pintar: Bisa cari berdasarkan nama ATAU kode pelatihan sekaligus
                        $query->whereHas('pesertaevent', function ($q) use ($search) {
                            $q->where('nama_peserta', 'like', "%{$search}%");
                        })->orWhere('kode_hasil_pelatihan', 'like', "%{$search}%");
                    })
                    ->sortable()
                    ->weight('bold')
                    ->color('gray.800')
                    ->icon('heroicon-m-user') // Ikon mikro solid penanda profil
                    ->description(fn($record) => "ID Pelatihan: " . ($record->kode_hasil_pelatihan ?? '-')),

                // 3. Rekapitulasi Nilai Pre-Test (Dilengkapi Badge Progress)
                TextColumn::make('nilai_pretest')
                    ->label('Pre-Test')
                    ->numeric(decimalPlaces: 2)
                    ->sortable()
                    ->alignment(Alignment::Center)
                    ->fontFamily('mono') // Font angka sejajar vertikal profesional
                    ->color('gray.500')
                    ->description(fn($record) => "Awal", position: 'above'),

                // 4. Rekapitulasi Nilai Post-Test dengan Tren Indikator Kenaikan
                TextColumn::make('nilai_posttest')
                    ->label('Post-Test')
                    ->numeric(decimalPlaces: 2)
                    ->sortable()
                    ->alignment(Alignment::Center)
                    ->fontFamily('mono')
                    ->weight('semibold')
                    ->color('primary')
                    // Teks pembantu dinamis: Menghitung selisih/progres nilai langsung di tabel
                    ->description(function ($record) {
                        $selisih = $record->nilai_posttest - $record->nilai_pretest;
                        return $selisih >= 0
                            ? "📈 Naik (+{$selisih})"
                            : "📉 Turun ({$selisih})";
                    }),

                // 5. Nilai Akhir Berwarna Kontras (Kondisional Tajam)
                TextColumn::make('nilai_akhir')
                    ->label('Nilai Akhir')
                    ->numeric(decimalPlaces: 2)
                    ->sortable()
                    ->alignment(Alignment::Center)
                    ->fontFamily('mono')
                    ->weight('bold')
                    // Warna dinamis dipertegas dari skala amber (waspada) hingga success/danger
                    ->color(fn($state) => match (true) {
                        $state >= 85 => 'success',
                        $state >= 75 => 'info',
                        $state >= 60 => 'warning',
                        default => 'danger',
                    })
                    ->description(fn($record) => "KKM: 75.00", position: 'above'),

                // 6. Status Kelulusan dengan Micro-Icon Solid (High Saturasi Visual)
                TextColumn::make('status_kelulusan')
                    ->label('Hasil Akhir')
                    ->badge()
                    ->alignment(Alignment::Center)
                    ->color(fn(string $state): string => match ($state) {
                        'Lulus' => 'success',
                        'Tidak Lulus' => 'danger',
                        'Remedial' => 'warning',
                        default => 'gray',
                    })
                    // Menggunakan varian 'heroicon-m-...' (mini/solid) agar warna ikon di dalam badge lebih solid & terbaca
                    ->icon(fn(string $state): string => match ($state) {
                        'Lulus' => 'heroicon-m-academic-cap',
                        'Tidak Lulus' => 'heroicon-m-x-circle',
                        'Remedial' => 'heroicon-m-arrow-path',
                        default => 'heroicon-m-minus-small',
                    }),

                // 7. Catatan Ringkas + Tooltip Cerdas (Solusi Teks Panjang)
                TextColumn::make('catatan')
                    ->label('Catatan Evaluasi')
                    ->limit(25) // Memotong teks agar layout tabel tetap ramping & rapi
                    ->placeholder('Tidak ada catatan')
                    ->color('gray.500')
                    // Hover/Arahkan kursor pada baris ini untuk memunculkan teks catatan seutuhnya tanpa geser kolom
                    ->tooltip(fn($state) => $state)
                    ->toggleable(isToggledHiddenByDefault: true), // Tersembunyi default, dapat dimunculkan via opsi kolom

                // 8. Metadata Waktu Input (Audit Log Logis)
                TextColumn::make('updated_at')
                    ->label('Pembaruan')
                    ->dateTime('d M Y, H:i') // Format Indonesia: 04 Jun 2026, 10:15
                    ->fontFamily('mono')
                    ->color('gray.400')
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
