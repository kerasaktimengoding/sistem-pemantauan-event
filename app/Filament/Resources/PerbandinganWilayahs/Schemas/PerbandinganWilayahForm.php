<?php

namespace App\Filament\Resources\PerbandinganWilayahs\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Utilities\Get;
use App\Models\RekapHarga;
use Illuminate\Support\Str;

class PerbandinganWilayahForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Parameter Perbandingan')
                    ->schema([
                        Group::make([
                            TextInput::make('kode_perbandingan')
                                ->label('Kode Perbandingan')
                                ->required()
                                ->default(fn() => 'CMP-' . date('Y') . '-' . strtoupper(Str::random(5))),

                            Select::make('komoditas_id')
                                ->label('Komoditas')
                                ->relationship('komoditas', 'nama_komoditas')
                                ->required()
                                ->live(), // Wajib live agar wilayah bisa baca ID komoditas
                        ])->columns(2),
                    ]),

                Section::make('Komparasi Harga')
                    ->schema([
                        // --- Wilayah 1 ---
                        Group::make([
                            Select::make('wilayah_1_id')
                                ->label('Wilayah Pertama')
                                ->relationship('wilayah1', 'nama_wilayah')
                                ->required()
                                ->live()
                                ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                    $komoditasId = $get('komoditas_id');
                                    if (!$state || !$komoditasId)
                                        return;

                                    $rekap = RekapHarga::where('wilayah_id', $state)
                                        ->where('komoditas_id', $komoditasId)
                                        ->latest()->first();

                                    if ($rekap) {
                                        $set('harga_wilayah_1', $rekap->harga_rata_rata);
                                        $set('pesan_error_1', null); // Hapus pesan jika ada
                                    } else {
                                        $set('harga_wilayah_1', 0);
                                        $set('pesan_error_1', '⚠️ Data harga belum tersedia untuk wilayah & komoditas ini.');
                                    }

                                    // Update Selisih
                                    $h1 = (float) ($rekap->harga_rata_rata ?? 0);
                                    $h2 = (float) ($get('harga_wilayah_2') ?? 0);
                                    $set('selisih_harga', abs($h1 - $h2));
                                }),

                            TextInput::make('harga_wilayah_1')
                                ->label('Harga Wilayah 1')
                                ->numeric()
                                ->prefix('Rp')
                                ->live(onBlur: true)
                                // Gunakan state dinamis untuk helperText
                                ->helperText(function (Get $get) {
                                    return $get('pesan_error_1') ?? 'Masukkan harga manual jika data tidak ditemukan.';
                                })
                                // PENTING: Jangan masukkan logika Closure di atribut yang tidak mendukungnya
                                ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                    $h2 = (float) ($get('harga_wilayah_2') ?? 0);
                                    $set('selisih_harga', abs((float) $state - $h2));
                                }),
                        ])->columns(2),

                        // --- Wilayah 2 ---
                        Group::make([
                            Select::make('wilayah_2_id')
                                ->label('Wilayah Kedua')
                                ->relationship('wilayah2', 'nama_wilayah')
                                ->required()
                                ->live()
                                ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                    $komoditasId = $get('komoditas_id');
                                    if (!$state || !$komoditasId)
                                        return;

                                    $rekap = RekapHarga::where('wilayah_id', $state)
                                        ->where('komoditas_id', $komoditasId)
                                        ->latest()->first();

                                    if ($rekap) {
                                        $set('harga_wilayah_2', $rekap->harga_rata_rata);
                                        $set('pesan_error_2', null);
                                    } else {
                                        $set('harga_wilayah_2', 0);
                                        $set('pesan_error_2', '⚠️ Data harga belum tersedia untuk wilayah & komoditas ini.');
                                    }

                                    // Update Selisih
                                    $h1 = (float) ($get('harga_wilayah_1') ?? 0);
                                    $h2 = (float) ($rekap->harga_rata_rata ?? 0);
                                    $set('selisih_harga', abs($h1 - $h2));
                                }),

                            TextInput::make('harga_wilayah_2')
                                ->label('Harga Wilayah 2')
                                ->numeric()->prefix('Rp')
                                ->helperText(function (Get $get) {
                                    return $get('pesan_error_1') ?? 'Masukkan harga manual jika data tidak ditemukan.';
                                })
                                ->helperText(function (Get $get) {
                                    return $get('pesan_error_2') ?? 'Masukkan harga manual jika data tidak ditemukan.';
                                })
                        ])->columns(2),
                    ]),

                Section::make('Hasil Analisis')
                    ->schema([
                        TextInput::make('selisih_harga')
                            ->label('Selisih / Disparitas Harga')
                            ->numeric()->prefix('Rp')->readOnly()->columnSpanFull(),

                        Textarea::make('keterangan')
                            ->label('Analisis / Keterangan')
                            ->rows(3)->columnSpanFull(),
                    ]),
            ]);
    }
}
