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
use Filament\Support\Enums\FontFamily;

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
                TextColumn::make('kecamatan.nama_kecamatan')
                    ->label('Nama Wilayah')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::SemiBold)
                    ->color('gray')
                    // Menggabungkan informasi desa di bawah nama kecamatan menggunakan text helper
                   ->description(fn($record) => "🏡 " . ($record->desa?->jenis === 'kelurahan' ? "Kel. {$record->desa?->nama_desa}" : "Desa {$record->desa?->nama_desa}")),
                // 5. PENGGABUNGAN DATA KONTAK: No. HP (Bisa di-copy/klik) + Sub-deskripsi Email (Bisa di-klik)
                TextColumn::make('no_hp')
                    ->label('Kontak')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Nomor kontak berhasil disalin')
                    ->icon('heroicon-m-phone')
                    ->iconColor('success')
                    ->weight(FontWeight::Medium)
                    // Trik Interaktif: Jika diklik di desktop/HP akan langsung mengarah ke chat WhatsApp/panggilan
                    ->url(fn($record) => $record->no_hp ? "https://wa.me/" . preg_replace('/[^0-9]/', '', $record->no_hp) : null)
                    ->openUrlInNewTab()
                    // Email diletakkan di bawah nomor HP sebagai teks sekunder yang juga bisa diklik langsung untuk kirim email
                    ->description(fn($record) => $record->email ? "✉️ " . $record->email : null)
                    ->extraAttributes([
                        'title' => 'Klik untuk chat WhatsApp'
                    ]),

                // 6. Status Pegawai (Warna Kontras Tinggi + Ikon Penanda)
                TextColumn::make('status_pegawai')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match (strtolower(trim($state))) {
                        'aktif' => 'success',
                        'non-aktif', 'tidak aktif' => 'danger',
                        'cuti' => 'warning',
                        default => 'secondary',
                    })
                    ->icon(fn(string $state): string => match (strtolower(trim($state))) {
                        'aktif' => 'heroicon-m-check-circle',
                        'non-aktif', 'tidak aktif' => 'heroicon-m-x-circle',
                        'cuti' => 'heroicon-m-clock',
                        default => 'heroicon-m-minus-circle',
                    })
                    ->sortable()
                    ->alignment(Alignment::Center),

                // 7. Tanggal Masuk Kerja (Format Kustom Indonesia)
                TextColumn::make('tanggal_masuk')
                    ->label('Mulai Bekerja')
                    ->date('d F Y') // Contoh hasil: 04 Juni 2026
                    ->sortable()
                    ->color('gray')
                    ->toggleable(isToggledHiddenByDefault: true),

                // 8. Data Rahasia/Audit (Disembunyikan secara default, bisa dibuka via kolom penyesuai)
                TextColumn::make('nik')
                    ->label('NIK')
                    ->searchable()
                    ->fontFamily(FontFamily::Mono)
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
