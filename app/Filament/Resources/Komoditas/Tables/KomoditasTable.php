<?php

namespace App\Filament\Resources\Komoditas\Tables;

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

class KomoditasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->persistColumnsInSession()
            ->persistFiltersInSession()
            ->columns([
                // 1. Nomor Urut Ringkas & Rapi
                TextColumn::make('No')
                    ->rowIndex()
                    ->label('No.')
                    ->width('50px')
                    ->alignment(Alignment::Center)
                    ->color('gray'),

                // 2. Kode Komoditas dengan Penanda Visual & Copyable
                TextColumn::make('kode_komoditas')
                    ->label('Kode')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->copyMessage('Kode komoditas berhasil disalin')
                    ->copyMessageDuration(1500)
                    ->weight(FontWeight::Bold)
                    ->fontFamily('mono')
                    ->color('primary')
                    ->icon('heroicon-m-square-2-stack') // Menambahkan ikon tumpukan kotak kecil
                    ->iconColor('gray'),

                // 3. PUSAT INFORMASI: Nama, Kategori, Satuan, dan Deskripsi digabung!
                TextColumn::make('nama_komoditas')
                    ->label('Informasi Komoditas')
                    ->searchable(query: function ($query, string $search) {
                        // Smart Search: Bisa mencari berdasarkan nama, deskripsi, maupun kategori sekaligus
                        $query->where(function ($q) use ($search) {
                            $q->where('nama_komoditas', 'like', "%{$search}%")
                                ->orWhere('deskripsi', 'like', "%{$search}%")
                                ->orWhere('kategori', 'like', "%{$search}%");
                        });
                    })
                    ->sortable()
                    ->weight(FontWeight::SemiBold)
                    ->size('md')
                    ->color('gray.800')
                    // Baris Pertama Deskripsi: Kategori & Satuan dengan format Badge Mini teks
                    ->description(fn($record) => "📦 Kategori: " . ($record->kategori ?? '-') . " | ⚖️ Satuan: " . ($record->satuan ?? '-'), position: 'above')
                    // Baris Kedua Deskripsi: Deskripsi panjang dibatasi agar tidak merusak layout
                    ->description(fn($record) => $record->deskripsi ? '💡 ' . str($record->deskripsi)->limit(70) : 'Tidak ada deskripsi tambahan', position: 'below'),

                // 4. Status dengan Badge Kontras & Ikon Dinamis
                TextColumn::make('status_komoditas')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match (strtolower(trim($state))) {
                        'aktif' => 'success',
                        'terbatas' => 'warning',
                        'non-aktif', 'matikan' => 'danger',
                        default => 'gray',
                    })
                    ->icon(fn(string $state): string => match (strtolower(trim($state))) {
                        'aktif' => 'heroicon-m-check-circle',
                        'terbatas' => 'heroicon-m-exclamation-triangle',
                        'non-aktif', 'matikan' => 'heroicon-m-x-circle',
                        default => 'heroicon-m-question-mark-circle',
                    })
                    ->alignment(Alignment::Center),

                // 5. Audit Trail: Jejak Waktu yang Bersih
                TextColumn::make('updated_at')
                    ->label('Update Terakhir')
                    ->dateTime('d M Y, H:i')
                    ->since() // Mengubah menjadi format "2 days ago" atau "3 jam yang lalu" agar lebih interaktif
                    ->label('Pembaruan')
                    ->color('gray')
                    ->size('sm')
                    ->toggleable(isToggledHiddenByDefault: false), // Dimunculkan secara default karena format 'since' sangat bersih
            ])
            ->filters([
                //
                SelectFilter::make('kategori')
                    ->label('Kategori')
                    ->options([
                        'beras' => 'beras',
                        'bahan_pokok' => 'bahan_pokok',
                        'sayur' => 'sayur',
                        'buah' => 'buah',
                        'bumbu_dapur' => 'bumbu_dapur',
                        'protein_hewani' => 'protein_hewani',
                        'protein_nabati' => 'protein_nabati',
                        'minyak_lemak' => 'minyak_lemak',
                        'gula' => 'gula',
                        'olahan_pangan' => 'olahan_pangan',
                        'sembako_lain' => 'sembako_lain',
                        'non_pangan' => 'non_pangan',

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
