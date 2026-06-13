<?php

namespace App\Filament\Resources\Kecamatans\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\DeleteAction;
use Filament\Support\Enums\Alignment;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\TextInput;

class KecamatansTable
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

                // 2. Kode Kecamatan (Dibuat mencolok dengan badge abu-abu tipis)
                TextColumn::make('kode_kecamatan')
                    ->label('Kode Wilayah')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('gray')
                    ->weight('bold'),

                // 3. Nama Kecamatan + Deskripsi Nama Desa di bawahnya
                TextColumn::make('nama_kecamatan')
                    ->label('Kecamatan & Kelurahan/Desa')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->color('primary')
                    ->icon('heroicon-m-building-office-2')
                    ->iconColor('primary')
                    ->size('lg'),

                // 4. Informasi Pimpinan (Nama Camat & NIP digabung menggunakan description)
                TextColumn::make('nama_camat')
                    ->label('Pejabat Camat')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-m-user-circle')
                    ->iconColor('gray')
                    ->weight('medium')
                    ->placeholder('Belum Ada Pejabat')
                    // Menampilkan NIP tepat di bawah nama camat agar hemat kolom dan rapi
                    ->description(fn($record) => $record->nip_camat ? "NIP. {$record->nip_camat}" : "NIP: -"),

                // 5. Kontak Kantor (Telepon & Email digabung)
                TextColumn::make('no_telp')
                    ->label('Hubungi Kantor')
                    ->searchable()
                    ->placeholder('Tidak Ada Telp')
                    ->icon('heroicon-m-phone')
                    ->iconColor('success')
                    // Menampilkan email tepat di bawah nomor telepon
                    ->description(fn($record) => $record->email_kecamatan ?? 'Email: -'),

                // 6. Alamat Kantor Kecamatan (Dilengkapi Tooltip agar tidak memotong baris berantakan)
                TextColumn::make('alamat_kantor')
                    ->label('Alamat Kantor')
                    ->searchable()
                    ->limit(35)
                    ->tooltip(fn($state) => $state) // Arahkan kursor untuk melihat alamat lengkap resmi
                    ->icon('heroicon-m-map-pin')
                    ->iconColor('danger')
                    ->color('gray'),

                // 7. Luas Wilayah (Rata Kanan + Format Indonesia + Warna Info)
                TextColumn::make('luas_wilayah')
                    ->label('Luas Wilayah')
                    ->numeric(decimalPlaces: 2, locale: 'id') // Format: 1.250,50
                    ->suffix(' Km²')
                    ->sortable()
                    ->alignment(Alignment::Right)
                    ->color('info')
                    ->weight('semibold'),

                // 8. Jumlah Penduduk (Dibuat otomatis berwarna Merah jika padat / di atas 5.000 jiwa)
                TextColumn::make('jumlah_penduduk')
                    ->label('Jumlah Penduduk')
                    ->numeric(locale: 'id') // Format ribuan lokal Indonesia (Contoh: 15.420)
                    ->suffix(' Jiwa')
                    ->sortable()
                    ->alignment(Alignment::Right)
                    ->weight('bold')
                    // Fitur Warna Kondisional: Jika penduduk > 5000 warna merah (danger), jika tidak warna hijau (success)
                    ->color(fn($state) => $state > 5000 ? 'danger' : 'success'),

                // 9. Keterangan / Analisis Wilayah
                TextColumn::make('keterangan')
                    ->label('Catatan Wilayah')
                    ->searchable()
                    ->limit(25)
                    ->tooltip(fn($state) => $state)
                    ->placeholder('-')
                    ->toggleable(isToggledHiddenByDefault: true),

                // 10. Timestamps Log Sistem
                TextColumn::make('created_at')
                    ->label('Sistem Dibuat')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
                // Filter Kustom untuk Rentang Luas Wilayah (Terkecil & Terbesar)
                Filter::make('rentang_luas_wilayah')
                    ->label('Luas Wilayah (km²)')
                    ->form([
                        TextInput::make('luas_terkecil')
                            ->label('Luas Terkecil (Min)')
                            ->numeric()
                            ->placeholder('Contoh: 10'),
                        TextInput::make('luas_terbesar')
                            ->label('Luas Terbesar (Max)')
                            ->numeric()
                            ->placeholder('Contoh: 500'),
                    ])
                    ->query(function (\Illuminate\Database\Eloquent\Builder $query, array $data): \Illuminate\Database\Eloquent\Builder {
                        return $query
                            ->when(
                                $data['luas_terkecil'],
                                fn(\Illuminate\Database\Eloquent\Builder $query, $value) => $query->where('luas_wilayah', '>=', $value)
                            )
                            ->when(
                                $data['luas_terbesar'],
                                fn(\Illuminate\Database\Eloquent\Builder $query, $value) => $query->where('luas_wilayah', '<=', $value)
                            );
                    })
                    // Menampilkan indikator badge di atas tabel ketika filter aktif
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];

                        if ($data['luas_terkecil'] ?? null) {
                            $indicators[] = 'Minimal: ' . number_format($data['luas_terkecil'], 0, ',', '.') . ' Km²';
                        }

                        if ($data['luas_terbesar'] ?? null) {
                            $indicators[] = 'Maksimal: ' . number_format($data['luas_terbesar'], 0, ',', '.') . ' Km²';
                        }

                        return $indicators;
                    }),
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
