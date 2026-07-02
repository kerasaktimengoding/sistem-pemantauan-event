<?php

namespace App\Filament\Resources\Pedagangs\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\Alignment;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\DeleteAction;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Filters\SelectFilter;

class PedagangsTable
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

                // 2. PUSAT IDENTITAS: Nama Pedagang, Kode, dan NIK digabung!
                TextColumn::make('nama_pedagang')
                    ->label('Informasi Pedagang')
                    ->weight(FontWeight::Bold)
                    ->size('md')
                    ->color('primary-600') // Memberikan warna utama sistem pada nama pedagang
                    ->searchable(query: function ($query, string $search) {
                        // Smart Search: Kotak pencarian otomatis menyaring nama, kode, dan NIK sekaligus
                        $query->where(function ($q) use ($search) {
                            $q->where('nama_pedagang', 'like', "%{$search}%")
                                ->orWhere('kode_pedagang', 'like', "%{$search}%")
                                ->orWhere('nik', 'like', "%{$search}%");
                        });
                    })
                    ->sortable()
                    // Baris Atas: Kode Pedagang berformat tag monospace yang rapi
                    ->description(fn($record) => "🔑 KODE: " . ($record->kode_pedagang ?? '-'), position: 'above')
                    // Baris Bawah: NIK disembunyikan di bawah nama agar menghemat ruang horizontal, dilengkapi copyable instan
                    ->description(fn($record) => $record->nik ? "🪪 NIK: " . $record->nik : '⚠️ NIK Belum Direkam', position: 'below'),

                // 3. Integrasi Wilayah: Kecamatan, Desa, dan Alamat Spesifik
                TextColumn::make('kecamatan.nama_kecamatan')
                    ->label('Domisili Wilayah')
                    ->searchable(query: function ($query, string $search) {
                        // Memungkinkan pencarian berdasarkan nama kecamatan, desa, maupun alamat jalan
                        $query->whereHas('kecamatan', fn($q) => $q->where('nama_kecamatan', 'like', "%{$search}%"))
                            ->orWhereHas('desa', fn($q) => $q->where('nama_desa', 'like', "%{$search}%"))
                            ->orWhere('alamat', 'like', "%{$search}%");
                    })
                    ->sortable()
                    ->weight(FontWeight::SemiBold)
                    ->color('gray.800')

                    ->icon('heroicon-m-map-pin') // Ikon pin maps kecil di samping kecamatan
                    ->iconColor('   danger'),
                    // Baris Pertama Deskripsi: Nama Desa,
                // Baris Kedua Deskripsi: Detail Alamat Jalan diletakkan tipis di bawahnya agar menghemat tempat
                // ->description(fn($record) => $record->nama_pasar ? "Pasar " . str($record->nama_pasar)->limit(45) : 'Tidak ada detail alamat 1 ', position: 'above')
                // ->description(fn($record) => $record->alamat ? "📍 " . str($record->alamat)->limit(45) : 'Tidak ada detail alamat', position: 'below'),
                TextColumn::make('pasar.nama_pasar')
                    ->label('Lokasi Pasar & Wilayah')
                    // Memungkinkan pencarian global berdasarkan nama pasar ataupun nama wilayahnya
                    ->searchable(query: function ($query, string $search) {
                        $query->whereHas('pasar', function ($q) use ($search) {
                            $q->where('nama_pasar', 'like', "%{$search}%");
                        })->orWhereHas('wilayah', function ($q) use ($search) {
                            $q->where('nama_wilayah', 'like', "%{$search}%");
                        });
                    })
                    ->tooltip(fn($record) => $record->desa?->jenis === 'kelurahan' ? "Kel. {$record->desa?->nama_desa}" : "Desa {$record->desa?->nama_desa}")
                    // Menggabungkan informasi desa di bawah nama kecamatan menggunakan text helper
                    ->description(fn($record) => "🏡 " . ($record->desa?->jenis === 'kelurahan' ? "Kel. {$record->desa?->nama_desa}" : "Desa {$record->desa?->nama_desa}"
                    ))
                    ->sortable()
                    ->weight(FontWeight::SemiBold)
                    ->color('gray.800')
                    ->icon('heroicon-m-map-pin') // Ikon pin maps kecil di samping kecamatan
                    ->iconColor('danger')
                    // Baris Pertama Deskripsi: Nama Desa
                   
                    // Baris Kedua Deskripsi: Detail Alamat Jalan diletakkan tipis di bawahnya agar menghemat tempat
                     ->description(fn($record) => "🏡 " . ($record->desa?->jenis === 'kelurahan' ? "Kel. {$record->desa?->nama_desa}" : "Desa {$record->desa?->nama_desa}"
                    )),
                // 4. No. WhatsApp Interaktif (Bisa Diklik Langsung Menuju Chat)
                TextColumn::make('no_hp')
                    ->label('Kontak WhatsApp')
                    ->icon('heroicon-m-chat-bubble-left-right') // Ikon chat yang lebih modern
                    ->iconColor('success')
                    ->color('success')
                    ->weight(FontWeight::Medium)
                    ->fontFamily('mono') // Tampilan nomor bergaya monospace agar mudah dibaca kodenya
                    ->copyable()
                    ->copyMessage('Nomor WhatsApp berhasil disalin')
                    ->searchable()
                    // FITUR PREMIUM: Mengubah teks nomor menjadi tautan/link WA aktif otomatis saat diklik!
                    ->url(
                        fn($record) => $record->no_hp
                        ? "https://wa.me/" . preg_replace('/[^0-9]/', '', $record->no_hp)
                        : null,
                        shouldOpenInNewTab: true
                    ),
                 

                // 5. Status Akun dengan Badge Solid Kontras & Ikon Dinamis

                TextColumn::make('tempat.kode_tempat_usaha')
                    ->label('Detail Tempat')
                    // ->formatStateUsing(fn($state) => $state ? $state->kode_tempat_usaha : 'Tidak ada detail tempat')
                    ->badge()
                    ->color('primary')
                    ->weight(FontWeight::SemiBold)
                    ->icon('heroicon-m-map-pin')
                    ->iconColor('danger')
                    ->sortable()
                    ->searchable()
                    ->description(fn($record) => $record->tempat->nomor_tempat ? "Nomor : " . $record->tempat->nomor_tempat : 'Tidak ada nomor tempat', position: 'above')
                    ->description(fn($record) => $record->tempat->jenis_tempat ? "Jenis Tempat: " . $record->tempat->jenis_tempat : 'Tidak ada jenis tempat', position: 'below'),

                TextColumn::make('status_pedagang')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match (strtolower(trim($state))) {
                        'aktif' => 'success',
                        'tersuspend', 'ditangguhkan' => 'warning',
                        'non-aktif', 'pasif' => 'danger',
                        default => 'gray',
                    })
                    ->icon(fn(string $state): string => match (strtolower(trim($state))) {
                        'aktif' => 'heroicon-m-shield-check', // Perisai centang untuk aktif
                        'tersuspend', 'ditangguhkan' => 'heroicon-m-no-symbol', // Simbol dilarang untuk suspend
                        'non-aktif', 'pasif' => 'heroicon-m-minus-circle',
                        default => 'heroicon-m-question-mark-circle',
                    })
                    ->alignment(Alignment::Center),
            ])
            ->filters([
                //
                SelectFilter::make('status_pedagang')
                    ->label('Status Pedagang')
                    ->options([
                        'aktif' => 'Aktif',
                        'tersuspend' => 'Tersuspend',
                        'ditangguhkan' => 'Ditangguhkan',
                        'pasif' => 'Pasif',
                        'non aktif' => 'Non Aktif',
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
