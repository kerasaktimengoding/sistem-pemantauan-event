<?php

namespace App\Filament\Resources\EventKegiatans\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\Alignment;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\DeleteAction;

class EventKegiatansTable
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

                // 2. Nama Kegiatan + Kode Event (Ditingkatkan dengan Icon & Ukuran Teks Dominan)
                TextColumn::make('nama_event')
                    ->label('Nama Kegiatan')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->size('lg') // Ukuran teks diperbesar agar menjadi jangkar pandangan mata (Visual Anchor)
                    ->icon('heroicon-m-sparkles')
                    ->iconColor('primary')
                    ->description(fn($record) => "Kode Event: " . ($record->kode_event ?? '-')),

                // 3. Jenis Event (Ditingkatkan dari Gray Polos menjadi Dinamis Berwarna)
                TextColumn::make('jenis_event')
                    ->label('Jenis Agenda')
                    ->badge()
                    ->searchable()
                    ->color(fn(string $state): string => match ($state) {
                        'Sosialisasi', 'Penyuluhan' => 'info',      // Biru
                        'Rapat', 'Koordinasi' => 'warning',        // Jingga
                        'Inspeksi', 'Sidak', 'Monitoring' => 'danger', // Merah
                        'Festival', 'Pasar Murah' => 'success',    // Hijau
                        default => 'gray',
                    })
                    ->formatStateUsing(fn(string $state) => strtoupper($state)),

                // 4. Rentang Tanggal Kegiatan (Ditingkatkan dengan Icon Waktu agar Scannable)
                TextColumn::make('tanggal_mulai')
                    ->label('Periode Pelaksanaan')
                    ->date('d M Y')
                    ->sortable()
                    ->weight('medium')
                    ->icon('heroicon-m-calendar-days')
                    ->iconColor('success')
                    ->description(fn($record) => $record->tanggal_selesai
                        ? "s/d " . \Carbon\Carbon::parse($record->tanggal_selesai)->format('d M Y')
                        : 'Satu Hari Selesai'),

                // 5. Lokasi & Wilayah (Digabungkan agar Menghemat Ruang Horizontal Tabel)
                TextColumn::make('wilayah.nama_wilayah')
                    ->label('Lokasi & Wilayah')
                    ->searchable()
                    ->sortable()
                    ->weight('medium')
                    ->icon('heroicon-m-map-pin')
                    ->iconColor('danger')
                    // Menampilkan alamat detail tepat di bawah nama wilayah
                    ->description(fn($record) => "Detail: " . \Illuminate\Support\Str::limit($record->lokasi_event ?? '-', 35)),

               
                // 8. Status Event (Kode Anda Sudah Bagus, Kita Sempurnakan Konsistensinya)
                TextColumn::make('status_event')
                    ->label('Status')
                    ->badge()
                    ->alignment(Alignment::Center)
                    ->color(fn(string $state): string => match ($state) {
                        'Terjadwal' => 'info',
                        'Berlangsung' => 'warning',
                        'Selesai' => 'success',
                        'Dibatalkan' => 'danger',
                        default => 'gray',
                    })
                    ->icon(fn(string $state): string => match ($state) {
                        'Terjadwal' => 'heroicon-m-calendar',
                        'Berlangsung' => 'heroicon-m-arrow-path',
                        'Selesai' => 'heroicon-m-check-badge',
                        'Dibatalkan' => 'heroicon-m-x-circle',
                        default => 'heroicon-m-clock',
                    }),

                // 9. Deskripsi Singkat Event (Menggunakan Tooltip Agar Tidak Merusak Baris Tabel)
               
            ])
            ->defaultSort('tanggal_mulai', 'desc')
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
