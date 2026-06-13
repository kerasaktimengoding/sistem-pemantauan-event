<?php

namespace App\Filament\Resources\Wilayahs\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\Alignment;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\DeleteAction;
use Filament\Support\Enums\FontFamily;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\TextInput;

class WilayahsTable
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

                // 2. Kode Wilayah (Format Monospace & Copyable)
                TextColumn::make('kode_wilayah')
                    ->label('Kode Wilayah')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->copyMessage('Kode wilayah berhasil disalin')
                    ->fontFamily(FontFamily::Mono)
                    ->weight(FontWeight::Bold)
                    ->color('primary'),

                // 3. Nama Wilayah & Relasi Desa (PERBAIKAN: ->numeric() Dihapus)
                TextColumn::make('kecamatan.nama_kecamatan')
                    ->label('Nama Wilayah')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::SemiBold)
                    ->color('gray')
                    // Menggabungkan informasi desa di bawah nama kecamatan menggunakan text helper
                    ->description(fn($record) => "🏡 Desa: " . ($record->desa?->nama_desa ?? '-')),

                // 4. Luas Wilayah & Populasi (Dikelompokkan Berdampingan dengan Alignment Kanan)
                TextColumn::make('luas_wilayah')
                    ->label('Luas Wilayah')
                    ->numeric(decimalPlaces: 2) // Memformat otomatis desimal (Contoh: 150,50)
                    ->suffix(' km²')
                    ->sortable()
                    ->alignment(Alignment::Right),

                TextColumn::make('jumlah_penduduk')
                    ->label('Populasi')
                    ->numeric() // Filament v3 otomatis memformat ribuan (Contoh: 15.250) sesuai locale project
                    ->sortable()
                    ->alignment(Alignment::Right)
                    ->color('info')
                    ->weight(FontWeight::Medium),

                // 5. Potensi Ekonomi (Pemanfaatan Badge Warna Kontras)
                TextColumn::make('potensi_ekonomi')
                    ->label('Potensi Ekonomi')
                    ->badge()
                    ->color(fn(string $state): string => match (strtolower(trim($state))) {
                        'pertanian', 'perkebunan' => 'success',
                        'perdagangan', 'jasa' => 'warning',
                        'industri', 'pariwisata' => 'info',
                        'maritim', 'perikanan' => 'primary',
                        default => 'gray',
                    })
                    ->icon(fn(string $state): string => match (strtolower(trim($state))) {
                        'pertanian', 'perkebunan' => 'heroicon-m-building-storefront',
                        default => 'heroicon-m-academic-cap', // Ikon bawaan opsional
                    })
                    ->searchable(),

                // 6. Kode Pos (Desain Badge Netral di Tengah)
                TextColumn::make('kode_pos')
                    ->label('Kode Pos')
                    ->searchable()
                    ->badge()
                    ->color('gray')
                    ->fontFamily(FontFamily::Mono)
                    ->alignment(Alignment::Center),

                // 7. Batas Wilayah & Geografis (Sembunyi default dengan optimasi Tooltip)
                TextColumn::make('batas_utara')
                    ->label('Batas Utara')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('batas_selatan')
                    ->label('Batas Selatan')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('keterangan_geografis')
                    ->label('Geografis')
                    ->limit(30)
                    ->tooltip(fn($record) => $record->keterangan_geografis) // Muncul teks lengkap saat hover cursor
                    ->toggleable(isToggledHiddenByDefault: true),

                // 8. Timestamps (Sembunyi secara default)
                TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d M Y H:i')
                    ->color('gray')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
                Filter::make('rentang_populasi')
                    ->label('Jumlah Populasi (Jiwa)')
                    ->form([
                        TextInput::make('populasi_terkecil')
                            ->label('Populasi Terkecil (Min)')
                            ->numeric()
                            ->placeholder('Contoh: 1000'),
                        TextInput::make('populasi_terbesar')
                            ->label('Populasi Terbesar (Max)')
                            ->numeric()
                            ->placeholder('Contoh: 10000'),
                    ])
                    ->query(function (\Illuminate\Database\Eloquent\Builder $query, array $data): \Illuminate\Database\Eloquent\Builder {
                        return $query
                            // Logika Populasi Sedikit (Minimal): Mencari yang >= input populasi_terkecil
                            ->when(
                                $data['populasi_terkecil'],
                                fn(\Illuminate\Database\Eloquent\Builder $query, $value): \Illuminate\Database\Eloquent\Builder => $query->where('jumlah_penduduk', '>=', $value)
                            )
                            // Logika Populasi Banyak (Maksimal): Mencari yang <= input populasi_terbesar
                            ->when(
                                $data['populasi_terbesar'],
                                fn(\Illuminate\Database\Eloquent\Builder $query, $value): \Illuminate\Database\Eloquent\Builder => $query->where('jumlah_penduduk', '<=', $value)
                            );
                    })
                    // Menampilkan badge indikator aktif di atas tabel sesuai format lokal Indonesia
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];

                        if ($data['populasi_terkecil'] ?? null) {
                            $indicators[] = 'Populasi Min: ' . number_format($data['populasi_terkecil'], 0, ',', '.') . ' Jiwa';
                        }

                        if ($data['populasi_terbesar'] ?? null) {
                            $indicators[] = 'Populasi Max: ' . number_format($data['populasi_terbesar'], 0, ',', '.') . ' Jiwa';
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
