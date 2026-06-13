<?php

namespace App\Filament\Resources\Pasars\Tables;

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

class PasarsTable
{
    public static function configure(Table $table): Table
    {
        return $table
        ->persistColumnsInSession()
            ->persistFiltersInSession()
            ->columns([
                // 1. Nomor Urut Desain Minimalis
                TextColumn::make('No')
                    ->rowIndex()
                    ->label('No.')
                    ->width('50px')
                    ->alignment(Alignment::Center)
                    ->color('gray'),

                // 2. PUSAT INFORMASI: Nama Pasar, Kode, dan Alamat Lengkap digabung!
                TextColumn::make('nama_pasar')
                    ->label('Detail Pasar')
                    ->weight(FontWeight::Bold)
                    ->size('md')
                    ->color('primary-600') // Memberikan sentuhan warna utama aplikasi pada nama pasar
                    ->searchable(query: function ($query, string $search) {
                        // Smart Search: Kotak pencarian otomatis memeriksa nama, kode, dan alamat sekaligus
                        $query->where(function ($q) use ($search) {
                            $q->where('nama_pasar', 'like', "%{$search}%")
                                ->orWhere('kode_pasar', 'like', "%{$search}%")
                                ->orWhere('alamat_pasar', 'like', "%{$search}%");
                        });
                    })
                    ->sortable()
                    // Baris Atas: Kode Pasar berformat Tag Monospace agar eye-catching
                    ->description(fn($record) => "🔑 KODE: " . ($record->kode_pasar ?? '-'), position: 'above')
                    // Baris Bawah: Alamat Pasar dibatasi agar pas dan rapi, jika dipotong otomatis muncul tooltip bawaan
                    ->description(fn($record) => $record->alamat_pasar ? "📍 " . str($record->alamat_pasar)->limit(65) : 'Belum ada alamat lengkap', position: 'below'),

                // 3. Kolom Wilayah: Integrasi Kecamatan & Desa dengan Struktur Rapi
                TextColumn::make('kecamatan.nama_kecamatan')
                    ->label('Nama Wilayah')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::SemiBold)
                    ->color('gray')
                    // Menggabungkan informasi desa di bawah nama kecamatan menggunakan text helper
                    ->description(fn($record) => "🏡 Desa: " . ($record->desa?->nama_desa ?? '-')),


                // 4. Status Operasional dengan Badge Solid Kontras & Ikon Heroicons v3
                TextColumn::make('status_pasar')
                    ->label('Status Operasional')
                    ->badge()
                    ->color(fn(string $state): string => match (strtolower(trim($state))) {
                        'aktif' => 'success',
                        'non-aktif', 'tutup' => 'danger',
                        'renovasi', 'perbaikan' => 'warning',
                        default => 'gray',
                    })
                    ->icon(fn(string $state): string => match (strtolower(trim($state))) {
                        'aktif' => 'heroicon-m-building-storefront',
                        'non-aktif', 'tutup' => 'heroicon-m-x-circle',
                        'renovasi', 'perbaikan' => 'heroicon-m-wrench-screwdriver',
                        default => 'heroicon-m-question-mark-circle',
                    })
                    ->alignment(Alignment::Center),

                // 5. Jejak Waktu yang Interaktif & Bersih (Human-Readable)
                TextColumn::make('updated_at')
                    ->label('Sinkronisasi')
                    ->since() // Mengubah tanggal kaku menjadi "3 hari yang lalu", "2 jam yang lalu", dsb.
                    ->dateTimeTooltip('d M Y H:i:s') // Saat di-hover, tanggal asli dan jam detailnya tetap muncul lewat tooltip
                    ->color('gray')
                    ->size('sm')
                    ->toggleable(isToggledHiddenByDefault: false), // Default dimunculkan karena ukurannya sekarang sangat compact
            ])

            ->filters([
                //
                SelectFilter::make('status_pasar')
                ->label('Status Pasar')
                 ->options([
                        'aktif' => 'Aktif',
                        'non-aktif' => 'Non-Aktif',
                        'renovasi' => 'Renovasi',
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
