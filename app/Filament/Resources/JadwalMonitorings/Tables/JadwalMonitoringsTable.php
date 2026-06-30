<?php

namespace App\Filament\Resources\JadwalMonitorings\Tables;

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

class JadwalMonitoringsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->persistColumnsInSession()
            ->persistFiltersInSession()
            ->columns([

                // 1. Nomor Urut Ringkas & Minimalis
                TextColumn::make('No')
                    ->rowIndex()
                    ->label('No.')
                    ->width('50px')
                    ->alignment(Alignment::Center)
                    ->color('gray'),

                // 2. Kode Tugas - Format Badge Monospace Khusus Dokumen Resmi
                TextColumn::make('kode_jadwal')
                    ->label('Kode Tugas')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('gray')
                    ->fontFamily('mono') // Membuat kode terlihat seragam dan mudah dibaca
                    ->weight(FontWeight::Bold),

                // 3. Rencana Pelaksanaan - Dilengkapi Indikator Waktu Berwarna Utama
                TextColumn::make('tanggal_rencana')
                    ->label('Rencana Pelaksanaan')
                    ->date('d M Y')
                    ->sortable()
                    ->weight(FontWeight::Bold)
                    ->color('primary-600')
                    ->icon('heroicon-m-calendar-days')
                    ->iconColor('primary-500')
                    // Baris Bawah: Menampilkan Nomor Surat Tugas dengan ikon berkas yang elegan
                    ->description(
                        fn($record) => $record->nomor_surat_tugas
                        ? "📄 No. Surat: {$record->nomor_surat_tugas}"
                        : "⚠️ Surat Tugas: Belum Diterbitkan"
                    )
                    // Fitur Pencarian Cerdas: Memungkinkan pencarian berdasarkan Nomor Surat Tugas langsung
                    ->searchable(query: function ($query, string $search) {
                        $query->where('nomor_surat_tugas', 'like', "%{$search}%");
                    }),

                // 4. Target Lokasi - Penggabungan Nama Pasar, Kecamatan, & Pencarian Multi-Relasi
                TextColumn::make('pasar.nama_pasar')
                    ->label('Lokasi Pasar Target')
                    ->weight(FontWeight::SemiBold)
                    ->color('gray.800')
                    ->icon('heroicon-m-map-pin')
                    ->iconColor('danger-500')
                    ->placeholder('Pasar Tidak Terdaftar')
                    // Baris Bawah: Format wilayah menggunakan badge tipis/teks pendukung yang rapi
                    ->description(fn($record) => "🗺️ Wilayah: " . ($record->pasar?->kecamatan?->nama_kecamatan ?? '-'))
                    // Smart Deep Search: Kotak pencarian global bisa langsung mendeteksi nama pasar maupun nama kecamatannya
                    ->searchable(query: function ($query, string $search) {
                        $query->whereHas('pasar', function ($q) use ($search) {
                            $q->where('nama_pasar', 'like', "%{$search}%")
                                ->orWhereHas('kecamatan', fn($qk) => $qk->where('nama_kecamatan', 'like', "%{$search}%"));
                        });
                    }),


                // 5. Petugas Lapangan - Penggabungan Nama Pelaksana, NIP, & Pencarian Mendalam
                TextColumn::make('pegawai.nama_pegawai')
                    ->label('Petugas Lapangan')
                    ->weight(FontWeight::SemiBold)
                    ->color('gray.800')
                    ->icon('heroicon-m-user')
                    ->iconColor('info-500')
                    ->placeholder('Belum Ditunjuk')
                    ->description(
                        fn($record) => $record->pegawai?->nip
                        ? "🪪 NIP. {$record->pegawai->nip}"
                        : "🪪 NIP: -"
                    )
                    // Smart Deep Search: Cari nama petugas atau NIP langsung dari kolom pencarian utama
                    ->searchable(query: function ($query, string $search) {
                        $query->whereHas('pegawai', function ($q) use ($search) {
                            $q->where('nama_pegawai', 'like', "%{$search}%")
                                ->orWhere('nip', 'like', "%{$search}%");
                        });
                    }),

                // 6. Status Kemajuan - Dilengkapi Perpaduan Warna Kontras dan Ikon Dinamis
                TextColumn::make('status_monitoring')
                    ->label('Status Kemajuan')
                    ->badge()
                    ->weight(FontWeight::Bold)
                    ->alignment(Alignment::Center)
                    // Transformasi visual dinamis: Mencocokkan warna background badge
                    ->color(fn(string $state): string => match (strtolower(trim($state))) {
                        'selesai', 'success', 'approved' => 'success',
                        'proses', 'ongoing', 'on progress' => 'warning',
                        'pending', 'draft' => 'gray',
                        'batal', 'rejected', 'failed' => 'danger',
                        default => 'primary',
                    })
                    // PREMIUM ADDITION: Menyematkan ikon dinamis di dalam badge sesuai dengan status data lapangan
                    ->icon(fn(string $state): string => match (strtolower(trim($state))) {
                        'selesai', 'success', 'approved' => 'heroicon-m-check-circle',
                        'proses', 'ongoing', 'on progress' => 'heroicon-m-arrow-path',
                        'pending', 'draft' => 'heroicon-m-clock',
                        'batal', 'rejected', 'failed' => 'heroicon-m-x-circle',
                        default => 'heroicon-m-question-mark-circle',
                    })
                    ->formatStateUsing(fn(string $state) => strtoupper($state)),

                // 7. Catatan Temuan - Dilengkapi Pemotongan Teks Simetris & Tooltip Instan
                TextColumn::make('catatan_petugas')
                    ->label('Hasil Temuan / Catatan')
                    ->limit(30)
                    ->tooltip(fn($state) => $state)
                    ->placeholder('✨ Tidak ada catatan khusus lapangan')
                    ->color('gray.500')
                    // Membantu pencarian data berdasarkan kata kunci yang ditulis petugas pada hasil temuan
                    ->searchable(),

                // 8. Timestamps Log System (Tersedia di balik tombol konfigurasi kolom grid)
                TextColumn::make('created_at')
                    ->label('Waktu Input')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Pembaruan Terakhir')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
                SelectFilter::make('status_monitoring')
                    ->label('Status Monitoring')
                    ->options([
                        'selesai' => 'selesai',
                        'proses' => 'proses',
                        'pending' => 'pending',
                        'batal' => 'Tidak Aktif',
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
