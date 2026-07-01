<?php

namespace App\Filament\Resources\Pegawais\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\Alignment;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\DeleteAction;
use Filament\Tables\Filters\SelectFilter;
use Filament\Support\Enums\FontWeight;
class PegawaisTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->persistColumnsInSession()
            ->persistFiltersInSession()
            ->columns([
                // 1. Nomor Urut (Rapi & Center)
                TextColumn::make('No')
                    ->rowIndex()
                    ->label('No.')
                    ->width('50px')
                    ->alignment(Alignment::Center),

                // 2. Nama Pegawai & NIP (Kombinasi Tipografi Bold - Muted)
                TextColumn::make('nama_pegawai')
                    ->label('Pegawai')
                    ->searchable(['nama_pegawai', 'nip', 'nik']) // Sekaligus bisa cari berdasarkan NIP/NIK
                    ->sortable()
                    ->weight(FontWeight::Bold)
                    ->color('gray')
                    ->description(fn($record) => "🪪 NIP: " . ($record->nip ?? '-')),
                
                // 3. Jenis Kelamin (Badge Kompak dengan Ikon Gender)
                TextColumn::make('jenis_kelamin')
                    ->label('L/P')
                    ->badge()
                    ->color(fn(string $state): string => match (strtolower(trim($state))) {
                        'laki-laki', 'l' => 'info',
                        'perempuan', 'p' => 'success',
                        default => 'secondary',
                    })
                    ->icon(fn(string $state): string => match (strtolower(trim($state))) {
                        'laki-laki', 'l' => 'heroicon-m-user',
                        'perempuan', 'p' => 'heroicon-m-user-circle',
                        default => 'heroicon-m-question-mark-circle',
                    })
                    ->alignment(Alignment::Center),

                // 4. PENGGABUNGAN DATA PENUGASAN: Jabatan Utama + Sub-deskripsi Wilayah Tugas
                TextColumn::make('jabatan.nama_jabatan')
                    ->label('Jabatan & Wilayah')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::SemiBold)
                    ->icon('heroicon-m-briefcase')
                    ->iconColor('primary'),
                // Menampilkan wilayah tugas tepat di bawah nama jabatan untuk menghemat kolom
                // ->description(fn ($record) => "📍 " . ($record->wilayah->nama_wilayah ?? 'Belum Ditentukan')),
                // 5. 🌟 WILAYAH PENUGASAN/ASAL (Kombinasi Dalam & Luar Kabupaten)
                TextColumn::make('wilayah_asal')
                    ->label('Wilayah Asal')
                    // Memodifikasi state tampilan berdasarkan status luar/dalam kabupaten
                    ->getStateUsing(function ($record) {
                        if ($record->is_luar_kabupaten) {
                            return 'Luar Kabupaten Banjar';
                        }
                        return $record->kecamatan ? $record->kecamatan->nama_kecamatan : 'Belum Ditentukan';
                    })
                    ->weight(FontWeight::SemiBold)
                    ->color(fn($record) => $record->is_luar_kabupaten ? 'warning' : 'gray')
                    ->icon(fn($record) => $record->is_luar_kabupaten ? 'heroicon-m-globe-alt' : 'heroicon-m-map-pin')
                    ->description(function ($record) {
                        // Deskripsi kondisional: Jika luar, tampilkan alamat luar. Jika dalam, tampilkan Desa/Kelurahan
                        if ($record->is_luar_kabupaten) {
                            return "📍 " . ($record->alamat_luar ?? '-');
                        }

                        if ($record->desa) {
                            $tipe = $record->desa->jenis === 'kelurahan' ? 'Kel.' : 'Desa';
                            return "🏡 {$tipe} {$record->desa->nama_desa}";
                        }

                        return '-';
                    }),

                // 6. Kontak (HP & Email)
                TextColumn::make('no_hp')
                    ->label('Kontak')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Nomor kontak berhasil disalin')
                    ->icon('heroicon-m-phone')
                    ->iconColor('success')
                    ->weight(FontWeight::Medium)
                    ->url(fn($record) => $record->no_hp ? "https://wa.me/" . preg_replace('/[^0-9]/', '', $record->no_hp) : null)
                    ->openUrlInNewTab()
                    ->description(fn($record) => $record->email ? "✉️ " . $record->email : null)
                    ->extraAttributes([
                        'title' => 'Klik untuk chat WhatsApp'
                    ]),

                // 7. Status Pegawai
                TextColumn::make('status_pegawai')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match (strtolower(trim($state))) {
                        'pns' => 'primary',
                        'pppk' => 'info',
                        'honorer' => 'warning',
                        'aktif' => 'success',
                        'cuti', 'non-aktif' => 'danger',
                        default => 'secondary',
                    })
                    ->sortable()
                    ->alignment(Alignment::Center),

                // 8. Tanggal Masuk Kerja (Disembunyikan)
                TextColumn::make('tanggal_masuk')
                    ->label('Mulai Bekerja')
                    ->date('d F Y')
                    ->sortable()
                    ->color('gray')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
                SelectFilter::make('status_pegawai')
                    ->label('Status Pegawai')
                    ->options([
                        'PNS' => 'PNS',
                        'PPPK' => 'PPPK',
                        'Honorer' => 'Honorer',
                        'Aktif' => 'Aktif',
                        'Cuti' => 'Cuti',
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
