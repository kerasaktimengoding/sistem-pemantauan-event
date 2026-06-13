<?php

namespace App\Filament\Resources\PesertaEvents\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\Alignment;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\DeleteAction;
use Filament\Tables\Filters\SelectFilter;

class PesertaEventsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->persistColumnsInSession()
            ->persistFiltersInSession()
            ->columns([
                // 1. Nomor Urut Otomatis
                TextColumn::make('No')
                    ->rowIndex()
                    ->label('No.')
                    ->width('50px')
                    ->alignment(Alignment::Center),

                // 2. Nama Peserta + NIK (Poin Utama Identitas)
                TextColumn::make('nama_peserta')
                    ->label('Nama Peserta / NIK')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->size('lg') // Visual Anchor agar mata langsung tertuju ke nama peserta
                    ->icon('heroicon-m-user')
                    ->iconColor('primary')
                    // Menampilkan NIK dengan font kecil berwarna abu-abu tepat di bawah nama
                    ->description(fn($record) => "NIK: " . ($record->nik ?? '-')),

                // 3. Nama Usaha + Jenis Produk (Penggabungan Profil Bisnis)
                TextColumn::make('nama_usaha')
                    ->label('Profil Usaha & Produk')
                    ->searchable()
                    ->sortable()
                    ->weight('medium')
                    ->color('primary') // Berwarna biru lembut agar membedakan identitas orang & bisnis
                    ->icon('heroicon-m-building-storefront')
                    ->iconColor('warning')
                    // Menampilkan jenis produk dagangan di bawah nama komersial usaha
                    ->description(fn($record) => "Produk: " . ($record->jenis_produk ?? '-')),

                // 4. Relasi Event Yang Diikuti (Desain Badge Melengkung yang Elegan)
                TextColumn::make('event.nama_event')
                    ->label('Agenda Kegiatan')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('info') // Warna biru informasi premium
                    ->icon('heroicon-m-sparkles')
                    ->weight('bold')
                    ->formatStateUsing(fn(string $state) => strtoupper($state)), // Kapital tegas di dalam badge

                // 5. Kontak WhatsApp Interaktif (Didesain Sangat Fungsional)
                TextColumn::make('no_hp')
                    ->label('Kontak WhatsApp')
                    ->searchable()
                    ->icon('heroicon-m-chat-bubble-left-right') // Menggunakan icon chat yang modern
                    ->iconColor('success')
                    ->color('success')
                    ->weight('medium')
                    ->fontFamily('mono') // Karakter angka sejajar vertikal lurus ke bawah
                    ->copyable() // Sekali klik, nomor otomatis tersalin ke clipboard sistem!
                    ->copyMessage('Nomor WhatsApp berhasil disalin')
                    ->copyMessageDuration(1500),

                // 6. Lokasi Wilayah Geografis (Hemat Kolom dengan Fitur Gabungan Alamat)
                TextColumn::make('wilayah.nama_wilayah')
                    ->label('Wilayah Asal')
                    ->searchable()
                    ->sortable()
                    ->weight('medium')
                    ->icon('heroicon-m-map-pin')
                    ->iconColor('danger')
                    ->placeholder('Luar Wilayah')
                    ->toggleable(isToggledHiddenByDefault: false),

                // 7. Status Partisipasi Berwarna Dinamis + Ikon Indikator Kehadiran
                TextColumn::make('status_partisipasi')
                    ->label('Status')
                    ->badge()
                    ->alignment(Alignment::Center)
                    ->color(fn(string $state): string => match ($state) {
                        'Terdaftar' => 'info',      // Biru tipis
                        'Hadir' => 'success',       // Hijau sukses
                        'Batal' => 'danger',        // Merah peringatan
                        default => 'gray',          // Abu-abu netral
                    })
                    ->icon(fn(string $state): string => match ($state) {
                        'Terdaftar' => 'heroicon-m-clipboard-document-check',
                        'Hadir' => 'heroicon-m-check-badge',
                        'Batal' => 'heroicon-m-x-circle',
                        default => 'heroicon-m-question-mark-circle',
                    }),

                // 8. Waktu Registrasi Masuk Sistem (Menggunakan Format Tanggal Jam yang Rapi)
                TextColumn::make('tanggal_registrasi')
                    ->label('Waktu Mendaftar')
                    ->dateTime('d M Y, H:i') // Contoh output: 04 Jun 2026, 10:08
                    ->fontFamily('mono')
                    ->color('gray')
                    ->size('sm')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true), // Disembunyikan secara default agar layout lega
            ])
            ->filters([
                //
                SelectFilter::make('status_partisipasi')
                    ->label('Status Partisipasi')
                    ->options([
                        'terdaftar' => 'terdaftar',
                        'hadir' => 'hadir',
                        'batal' => 'batal',

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
