<?php

namespace App\Filament\Resources\Desas\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\FontFamily;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\TextInput;

class DesasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->persistColumnsInSession()
            ->persistFiltersInSession()
            ->columns([
                // 1. Nomor Urut (Row Index)
                TextColumn::make('No')
                    ->rowIndex()
                    ->label('No.')
                    ->alignment(Alignment::Center),

                // 2. Kode Desa (Desain badge mini info)
                TextColumn::make('kode_desa')
                    ->label('Kode Desa')
                    ->searchable()
                    ->sortable()
                    ->fontFamily(FontFamily::Mono)
                    ->copyable()
                    ->copyMessage('Kode Desa berhasil disalin')
                    ->color('gray'),

                // 3. Nama Desa & Kecamatan Relasi (Digabung agar ringkas & informatif)
                TextColumn::make('nama_desa')
                    ->label('Informasi Wilayah')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Bold)
                    ->color('primary')
                    ->description(fn($record) => "Kecamatan: " . ($record->kecamatan?->nama_kecamatan ?? '-')),

                // 4. Nama Pembakal & No HP (Digabung menggunakan description + Ikon)
                TextColumn::make('nama_pembakal')
                    ->label('Pimpinan / Pembakal')
                    ->searchable()
                    ->icon('heroicon-m-user')
                    ->iconColor('success')
                    ->weight(FontWeight::Medium)
                    ->description(fn($record) => "📞 " . ($record->no_hp_pembakal ?? '-')),

                // 5. Alamat Kantor Desa (Dibatasi panjangnya + Tooltip)
                TextColumn::make('alamat_kantor_desa')
                    ->label('Alamat Kantor')
                    ->searchable()
                    ->limit(30)
                    ->tooltip(fn($record) => $record->alamat_kantor_desa)
                    ->color('gray'),

                // 6. Kode Pos (Tengah & Font Monospace)
                TextColumn::make('kode_pos')
                    ->label('Kode Pos')
                    ->searchable()
                    ->alignment(Alignment::Center)
                    ->fontFamily(FontFamily::Mono)
                    ->badge()
                    ->color('warning'),

                // 7. Koordinat Geografis (Latitude & Longitude digabung menjadi satu kolom)
                TextColumn::make('luas_wilayah')
                    ->label('Luas Wilayah')
                    ->numeric(decimalPlaces: 2, locale: 'id') // Format: 1.250,50
                    ->suffix(' Km²')
                    ->sortable()
                    ->alignment(Alignment::Right)
                    ->color('info')
                    ->weight('semibold'),

                // 8. Status Keaktifan (Menggunakan Badge berwarna cerah alih-alih Icon biasa)
                TextColumn::make('is_active')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        '1' => 'success',
                        '0' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        '1' => 'Aktif',
                        '0' => 'Non-Aktif',
                        default => 'Tidak Diketahui',
                    })
                    ->sortable(),

                // 9. Timestamps (Disembunyikan secara default, bisa dibuka via toggle kolom)
                TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Diperbarui Pada')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
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
