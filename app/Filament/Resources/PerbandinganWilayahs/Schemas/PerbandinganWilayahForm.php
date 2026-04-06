<?php

namespace App\Filament\Resources\PerbandinganWilayahs\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PerbandinganWilayahForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Parameter Perbandingan')
                    ->description('Tentukan komoditas dan dua wilayah yang akan dibandingkan harganya.')
                    ->schema([
                        Group::make([
                            TextInput::make('kode_perbandingan')
                                ->label('Kode Perbandingan')
                                ->required()
                                ->maxLength(20)
                                ->unique('perbandingan_wilayahs', 'kode_perbandingan', ignoreRecord: true)
                                ->placeholder('Contoh: CMP-2024-001'),

                            Select::make('komoditas_id')
                                ->label('Komoditas yang Dibandingkan')
                                ->relationship('komoditas', 'nama_komoditas')
                                ->searchable()
                                ->preload()
                                ->required(),
                        ])->columns(2),
                    ]),

                // Section 2: Data Harga Antar Wilayah
                Section::make('Komparasi Harga')
                    ->description('Masukan data harga dari kedua wilayah untuk melihat selisihnya.')
                    ->schema([
                        // Baris Wilayah 1
                        Group::make([
                            Select::make('wilayah_1_id')
                                ->label('Wilayah Pertama')
                                ->relationship('wilayah1', 'nama_wilayah') // Pastikan relasi di Model sudah benar
                                ->searchable()
                                ->required(),

                            TextInput::make('harga_wilayah_1')
                                ->label('Harga di Wilayah 1')
                                ->numeric()
                                ->prefix('Rp')
                                ->required()
                                ->placeholder('0.00'),
                        ])->columns(2),

                        // Baris Wilayah 2
                        Group::make([
                            Select::make('wilayah_2_id')
                                ->label('Wilayah Kedua')
                                ->relationship('wilayah2', 'nama_wilayah') // Pastikan relasi di Model sudah benar
                                ->searchable()
                                ->required(),

                            TextInput::make('harga_wilayah_2')
                                ->label('Harga di Wilayah 2')
                                ->numeric()
                                ->prefix('Rp')
                                ->required()
                                ->placeholder('0.00'),
                        ])->columns(2),
                    ]),

                // Section 3: Hasil Analisis Selisih
                Section::make('Hasil Analisis Selisih')
                    ->schema([
                        TextInput::make('selisih_harga')
                            ->label('Selisih / Disparitas Harga')
                            ->numeric()
                            ->prefix('Rp')
                            ->required()
                            ->helperText('Hasil pengurangan harga wilayah 1 dan wilayah 2.')
                            ->columnSpanFull(),

                        Textarea::make('keterangan')
                            ->label('Analisis / Keterangan')
                            ->rows(3)
                            ->placeholder('Masukkan penjelasan mengapa terjadi selisih harga (misal: kendala distribusi)...')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
