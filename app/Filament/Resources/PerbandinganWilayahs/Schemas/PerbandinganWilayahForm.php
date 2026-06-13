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
                            Select::make('desa_id')
                                ->label('desa Pertama')
                                ->relationship('desa1', 'nama_desa')
                                ->required()
                                ->live()
                                ->helperText('Otomatis terisi jika desa & komoditas tersedia')
                                ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                    $komoditasId = $get('komoditas_id');
                                    if (!$state || !$komoditasId)
                                        return;

                                    $rekap = RekapHarga::where('desa_id', $state)
                                        ->where('komoditas_id', $komoditasId)
                                        ->latest()->first();

                                    if ($rekap) {
                                        $set('harga_desa_1', $rekap->harga_rata_rata);
                                        $set('pesan_error_1', null); // Hapus pesan jika ada
                                    } else {
                                        $set('harga_desa_1', 0);
                                        $set('pesan_error_1', '⚠️ Data harga belum tersedia untuk desa & komoditas ini.');
                                    }

                                    // Update Selisih
                                    $h1 = (float) ($rekap->harga_rata_rata ?? 0);
                                    $h2 = (float) ($get('harga_desa_2') ?? 0);
                                    $set('selisih_harga', abs($h1 - $h2));
                                }),

                            TextInput::make('harga_desa_1')
                                ->label('Harga desa 1')
                                ->numeric()
                                ->prefix('Rp')
                                ->live(onBlur: true)
                                // Gunakan state dinamis untuk helperText
                                ->helperText(function (Get $get) {
                                    return $get('pesan_error_1') ?? 'Masukkan harga manual jika data tidak ditemukan.';
                                })
                                // PENTING: Jangan masukkan logika Closure di atribut yang tidak mendukungnya
                                ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                    $h2 = (float) ($get('harga_desa_2') ?? 0);
                                    $set('selisih_harga', abs((float) $state - $h2));
                                }),
                        ])->columns(2),

                        // --- desa 2 ---
                        Group::make([
                            Select::make('desa_2_id')
                                ->label('desa Kedua')
                                ->relationship('desa2', 'nama_desa')
                                ->required()
                                ->live()
                                ->helperText('Otomatis terisi jika desa & komoditas tersedia')
                                ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                    $komoditasId = $get('komoditas_id');
                                    if (!$state || !$komoditasId)
                                        return;

                                    $rekap = RekapHarga::where('desa_id', $state)
                                        ->where('komoditas_id', $komoditasId)
                                        ->latest()->first();

                                    if ($rekap) {
                                        $set('harga_desa_2', $rekap->harga_rata_rata);
                                        $set('pesan_error_2', null);
                                    } else {
                                        $set('harga_desa_2', 0);
                                        $set('pesan_error_2', '⚠️ Data harga belum tersedia untuk desa & komoditas ini.');
                                    }

                                    // Update Selisih
                                    $h1 = (float) ($get('harga_desa_1') ?? 0);
                                    $h2 = (float) ($rekap->harga_rata_rata ?? 0);
                                    $set('selisih_harga', abs($h1 - $h2));
                                }),

                            TextInput::make('harga_desa_2')
                                ->label('Harga desa 2')
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
                            ->numeric()->prefix('Rp')->readOnly()->columnSpanFull()
                            ->helperText('Otomatis terisi jika kedua harga desa tersedia'),

                        Textarea::make('keterangan')
                            ->label('Analisis / Keterangan')
                            ->rows(3)->columnSpanFull(),
                    ]),
            ]);
    }
}
