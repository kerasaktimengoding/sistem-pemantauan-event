<?php

namespace App\Filament\Resources\InputHargas\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Support\Enums\Alignment;
use Filament\Actions\DeleteAction;
use Filament\Support\Enums\FontWeight;

class InputHargasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // 1. Nomor Urut Otomatis (Rapi & Presisi)
                TextColumn::make('No')
                    ->rowIndex()
                    ->label('No.')
                    ->width('50px')
                    ->alignment(Alignment::Center),

                // 2. Waktu & Kode Transaksi (Gabungan Informasi Waktu & Dokumen)
                TextColumn::make('tanggal_input')
                    ->label('Waktu & Kode Transaksi')
                    ->date('d M Y') // Format Indonesia: 04 Jun 2026
                    ->sortable()
                    ->searchable()
                    ->weight(FontWeight::Bold)
                    ->color('primary')
                    ->icon('heroicon-m-calendar-days')
                    ->iconColor('primary')
                    ->fontFamily('mono') // Font monospace agar ID Transaksi di bawahnya tersusun rapi
                    // Menampilkan Kode Input Tepat di bawah Tanggal agar menghemat ruang monitor
                    ->description(fn($record) => "ID: " . ($record->kode_input_harga ?? '-')),

                // 3. Komoditas Produk + Satuan Ukur (Pencarian Pintar Multi-Kolom)
                TextColumn::make('komoditas.nama_komoditas')
                    ->label('Komoditas Produk')
                    // Memperluas jangkauan pencarian agar bisa mendeteksi nama komoditas ATAU satuannya sekaligus
                    ->searchable(query: function ($query, string $search) {
                        $query->whereHas('komoditas', function ($q) use ($search) {
                            $q->where('nama_komoditas', 'like', "%{$search}%")
                                ->orWhere('satuan', 'like', "%{$search}%");
                        });
                    })
                    ->sortable()
                    ->weight(FontWeight::Bold)
                    ->size('md') // Menggunakan ukuran teks medium yang stabil di Filament v3
                    ->icon('heroicon-m-shopping-bag')
                    ->iconColor('warning')
                    ->description(fn($record) => "Satuan Takaran: " . ($record->komoditas->satuan ?? '-')),

                // 4. Nominal Harga Jual Resmi (Desain Finansial Eksklusif)
                TextColumn::make('harga')
                    ->label('Harga Jual Resmi')
                    ->money('IDR', locale: 'id_ID') // Otomatis berformat rupiah: Rp 15.000
                    ->sortable()
                    ->alignment(Alignment::Right) // Rata kanan wajib untuk data numerik/keuangan
                    ->color('success') // Hijau segar melambangkan angka komersial
                    ->weight(FontWeight::ExtraBold) // Ketebalan maksimal agar langsung memikat mata admin
                    ->fontFamily('mono')
                    ->description(fn($record) => $record->pedagang ? " Pedagang: " . $record->pedagang->nama_pedagang : ""),
                    
                    // Karakter angka sejajar vertikal memudahkan komparasi harga antar baris

                // 5. Lokasi Pasar & Wilayah (Pencarian Lintas Relasi)
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
                    ->sortable()
                    ->weight(FontWeight::Medium)
                    ->icon('heroicon-m-map-pin')
                    ->iconColor('danger')
                    ->description(fn($record) => $record->desa ? "📍 Desa: " . $record->desa->nama_desa : "🏢 Seluruh Kecamatan"),

                // 6. Petugas Enumerator (Fleksibel & Informatif)
                TextColumn::make('pegawai.nama_pegawai')
                    ->label('Petugas Enumerator')
                    ->searchable()
                    ->weight(FontWeight::Medium)
                    ->icon('heroicon-m-user')
                    ->iconColor('gray')
                    ->placeholder('Bukan Pegawai Tetap') // Teks pembantu jika relasi pegawai kosong/null
                    ->toggleable(isToggledHiddenByDefault: false), // Default ditampilkan untuk mempermudah audit akurasi data

                // 7. Asal Sumber Data (Komponen Badge Solid, Tegas, & Anti-Error Case)
                TextColumn::make('sumber_data')
                    ->label('Asal Sumber')
                    ->searchable()
                    ->badge()
                    ->weight(FontWeight::Bold)
                    ->alignment(Alignment::Center)
                    // Logika pewarnaan dinamis yang kebal dari kesalahan ketik huruf besar/kecil (case-insensitive)
                    ->color(function (string $state): string {
                        $cleanedState = strtolower(trim($state));
                        return match (true) {
                            in_array($cleanedState, ['dinas', 'pemerintah', 'resmi']) => 'info',     // Biru modern
                            in_array($cleanedState, ['pedagang', 'pasar', 'primer']) => 'warning', // Jingga tegas
                            in_array($cleanedState, ['masyarakat', 'online']) => 'success',        // Hijau kontras
                            default => 'gray',                                                     // Abu-abu netral
                        };
                    })
                    // Format otomatis menjadi uppercase di dalam badge agar layout terlihat kokoh layaknya aplikasi SaaS
                    ->formatStateUsing(fn(string $state) => strtoupper(trim($state))),

                // 8. Catatan Lapangan (Lapisan Proteksi Tata Letak dengan Tooltip)
                TextColumn::make('keterangan')
                    ->label('Catatan Lapangan')
                    ->limit(35) // Memotong teks panjang agar baris tabel tidak melar ke bawah
                    ->tooltip(fn($state) => $state) // Cukup arahkan kursor (hover) untuk memunculkan teks utuh secara interaktif
                    ->placeholder('Tidak ada catatan')
                    ->color('gray')
                    // ->style('') // Teks dibuat miring khusus untuk catatan lapangan agar membedakan dari data utama
                    ->toggleable(isToggledHiddenByDefault: true), // Disembunyikan bawaan agar monitor tablet lapangan tidak sesak
            ])
            ->defaultSort('tanggal_input', 'desc')
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
