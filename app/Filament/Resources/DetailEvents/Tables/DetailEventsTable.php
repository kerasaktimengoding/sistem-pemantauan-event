<?php

namespace App\Filament\Resources\DetailEvents\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\Alignment;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\DeleteAction;

class DetailEventsTable
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

        // 2. Gabungan Informasi Event Utama, Kode Detail, dan Penyelenggara (Hemat Tempat & Scannable)
        TextColumn::make('event.nama_event')
            ->label('Detail Kegiatan')
            ->searchable(query: function ($query, string $search) {
                // Pencarian pintar: Cari berdasarkan nama event, kode detail, ATAU penyelenggara sekaligus
                $query->whereHas('event', function ($q) use ($search) {
                    $q->where('nama_event', 'like', "%{$search}%");
                })
                ->orWhere('kode_detail_event', 'like', "%{$search}%")
                ->orWhere('penyelenggara', 'like', "%{$search}%");
            })
            ->sortable()
            ->weight('bold')
            ->color('gray.800')
            ->description(function ($record) {
                return "Kode: {$record->kode_detail_event} • Oleh: {$record->penyelenggara}";
            }),

        // 3. Narasumber / Pemateri dengan Sentuhan Micro-Icon Modern
        TextColumn::make('narasumber')
            ->label('Narasumber')
            ->searchable()
            ->sortable()
            ->icon('heroicon-s-user') // Menggunakan ikon badge user solid yang lebih spesifik
            ->iconColor('gray.500')
            ->color('gray.700')
            ->weight('semibold'),

        // 4. Format Anggaran Finansial dengan Pewarnaan Dinamis (Skala Prioritas)
        TextColumn::make('anggaran_event')
            ->label('Anggaran')
            ->money('IDR', locale: 'id_ID')
            ->sortable()
            ->alignment(Alignment::Right)
            ->fontFamily('mono') // Angka sejajar lurus secara vertikal layaknya laporan keuangan
            ->weight('bold')
            // Mengubah warna teks berdasarkan besaran anggaran (misal: di atas 50 jt terlihat standout)
            ->color(fn ($state) => $state >= 50000000 ? 'success' : 'primary'),

        // 5. Menampilkan Kuota Peserta Menggunakan Komponen Badge agar Terlihat Menonjol
        TextColumn::make('kuota_peserta')
            ->label('Kuota')
            ->badge() // Diubah menjadi badge agar lebih informatif
            ->alignment(Alignment::Center)
            ->fontFamily('mono')
            // Pewarnaan kondisional: Merah jika kuota sangat sedikit/terbatas (misal di bawah 20)
            ->color(fn ($state) => $state <= 20 ? 'danger' : 'info')
            ->icon(fn ($state) => $state <= 20 ? 'heroicon-m-exclamation-triangle' : 'heroicon-m-users')
            ->suffix(' Orang'),

        // 6. Deskripsi Event Rapi dengan Batasan Karakter & Smart Tooltip (Mencegah Kolom Melar)
        TextColumn::make('deskripsi_event')
            ->label('Deskripsi Kegiatan')
            ->limit(35) // Memotong teks panjang di tabel utama
            ->placeholder('Tidak ada deskripsi tambahan')
            ->color('gray.500')
            // Cukup arahkan kursor (hover) di atas baris untuk membaca teks penuh tanpa membuka detail form
            ->tooltip(fn ($state) => $state) 
            ->toggleable(isToggledHiddenByDefault: true),

        // 7. Audit Log Waktu Pembaruan Sistem (Monospace Format)
        TextColumn::make('updated_at')
            ->label('Update Terakhir')
            ->dateTime('d M Y, H:i') // Dilengkapi jam menit (Contoh: 04 Jun 2026, 11:36)
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
