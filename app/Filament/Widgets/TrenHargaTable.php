<?php

namespace App\Filament\Widgets;

use App\Models\TrenHarga;
use Filament\Tables;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\BulkActionGroup;

class TrenHargaTable extends BaseWidget
{
    protected static ?int $sort = 12;
    protected static ?string $heading = 'Data Tren Harga';

    protected int|string|array $columnSpan = '3';



    public function table(Table $table): Table
    {
        return $table
            ->persistColumnsInSession()
            ->persistFiltersInSession()
            ->query(
                TrenHarga::query()
                    ->with([
                        'komoditas',
                        'wilayah',
                    ])
                    ->latest('periode_tren')
            )

            ->columns([
                // 1. Nomor Urut Otomatis dengan Desain Minimalis
                TextColumn::make('No')
                    ->rowIndex()
                    ->label('No.')
                    ->width('50px')
                    ->alignment(Alignment::Center),

                // 2. Periode Tren dengan Modifikasi Tipografi Tebal
                TextColumn::make('periode_tren')
                    ->label('Periode')
                    ->date('M Y') // Menampilkan contoh: "Jun 2026"
                    ->sortable()
                    ->weight(FontWeight::Bold)
                    ->color('gray'),

                // 3. Penyatuan Komoditas & Lokasi Wilayah (Smart Search)
                TextColumn::make('komoditas.nama_komoditas')
                    ->label('Komoditas & Wilayah')
                    ->weight(FontWeight::Bold)
                    ->searchable(query: function ($query, string $search) {
                        $query->whereHas('komoditas', fn($q) => $q->where('nama_komoditas', 'like', "%{$search}%"))
                            ->orWhereHas('desa', fn($q) => $q->where('nama_desa', 'like', "%{$search}%"));
                    })
                    ->sortable()
                    ->description(fn($record) => '📍 Wilayah: ' . ($record->kecamatan->nama_kecamatan ?? '-')),
                TextColumn::make('kecamatan.nama_kecamatan')
                    ->label('Cakupan Wilayah')
                    ->sortable()
                    // Fitur Pencarian Pintar: Cari berdasarkan nama kecamatan ATAU nama desa sekaligus
                    ->searchable(query: function ($query, string $search) {
                        $query->whereHas('kecamatan', function ($q) use ($search) {
                            $q->where('nama_kecamatan', 'like', "%{$search}%");
                        })->orWhereHas('desa', function ($q) use ($search) {
                            $q->where('nama_desa', 'like', "%{$search}%");
                        });
                    })
                    ->weight('medium')
                    ->color('gray.800')
                    // Menyusun visual hierarki: Kecamatan sebagai judul utama, Desa sebagai keterangan tipis di bawahnya
                    ->description(fn($record) => $record->desa ? "📍 Desa: " . $record->desa->nama_desa : "🏢 Seluruh Kecamatan"),
                // 4. Transformasi Finansial: Penggabungan Harga Awal & Harga Akhir
                TextColumn::make('harga_akhir')
                    ->label('Informasi Harga')
                    ->money('IDR', locale: 'id_ID')
                    ->fontFamily('mono') // Angka sejajar lurus ke bawah secara profesional
                    ->weight(FontWeight::Bold)
                    ->alignment(Alignment::Right)
                    ->color('primary')
                    ->description(fn($record) => 'Semula: Rp ' . number_format($record->harga_awal, 0, ',', '.'), position: 'above'),

                // 5. Visualisasi Kondisi Tren Menggunakan Solid Badge & Ikon Mikro
                TextColumn::make('arah_tren')
                    ->label('Kondisi')
                    ->badge()
                    ->alignment(Alignment::Center)
                    ->color(fn(string $state): string => match ($state) {
                        'Naik' => 'danger',
                        'Turun' => 'success',
                        'Stabil' => 'info',
                        default => 'gray',
                    })
                    ->icon(fn(string $state): string => match ($state) {
                        'Naik' => 'heroicon-m-arrow-trending-up',
                        'Turun' => 'heroicon-m-arrow-trending-down',
                        'Stabil' => 'heroicon-m-minus',
                        default => 'heroicon-m-question-mark-circle',
                    }),

                // 6. Selisih Persentase dengan Indikator Geometris UX (Accessibility-Friendly)
                TextColumn::make('persentase_perubahan')
                    ->label('Selisih (%)')
                    ->alignment(Alignment::Right)
                    ->fontFamily('mono')
                    ->weight(FontWeight::Bold)
                    ->color(fn($record) => match ($record->arah_tren) {
                        'Naik' => 'danger',
                        'Turun' => 'success',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn($state, $record) => match ($record->arah_tren) {
                        'Naik' => "▲ {$state}%",
                        'Turun' => "▼ {$state}%",
                        default => "• {$state}%",
                    }),
            ])

            ->filters([
                //
                SelectFilter::make('arah_tren')


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

        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */


        /*
        |--------------------------------------------------------------------------
        | Default Sort
        |--------------------------------------------------------------------------
        */


    }
}