<?php

namespace App\Filament\Resources\Wilayahs\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Textarea;

class WilayahForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
               // SECTION 1: IDENTITAS & HIRARKI
            Section::make('Penetapan Wilayah')
                ->description('Pilih desa untuk menentukan kecamatan secara otomatis.')
                ->schema([
                    TextInput::make('kode_wilayah')
                        ->label('Kode BPS / Kemendagri')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->placeholder('Contoh: 63.03.xx.xxxx'),

                    Group::make([
                        // 1. Pilih Desa Terlebih Dahulu
                        Select::make('desa_id')
                            ->label('Pilih Desa')
                            ->relationship('desa', 'nama_desa')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->live() // Memantau perubahan input secara real-time
                            ->afterStateUpdated(function ($state, callable $set) {
                                // Mencari data desa berdasarkan ID yang dipilih
                                $desa = \App\Models\Desa::find($state);
                                if ($desa) {
                                    // Otomatis mengisi kolom kecamatan_id
                                    $set('kecamatan_id', $desa->kecamatan_id);
                                }
                            }),

                        // 2. Kecamatan Terisi Otomatis
                        Select::make('kecamatan_id')
                            ->label('Kecamatan Induk')
                            ->relationship('kecamatan', 'nama_kecamatan')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->disabled() // Dimatikan agar tidak diubah manual (sesuai permintaan)
                            ->dehydrated() // Tetap mengirim data ke database saat simpan
                            ->helperText('Otomatis terisi berdasarkan desa yang dipilih.'),
                    ])->columns(2),
                ]),

            // BAGIAN 2: DATA PENDUKUNG (SESUAI MIGRATION)
            Section::make('Informasi Tambahan')
                ->description('Detail fisik dan profil sosial ekonomi wilayah.')
                ->schema([
                    Group::make([
                        TextInput::make('luas_wilayah')
                            ->label('Luas Wilayah (km²)')
                            ->numeric()
                            ->suffix('km²')
                            ->placeholder('0.00'),

                        TextInput::make('kode_pos')
                            ->label('Kode Pos')
                            ->required()
                            ->length(5)
                            ->placeholder('70xxx'),
                    ])->columns(2),

                    Group::make([
                        TextInput::make('jumlah_penduduk')
                            ->label('Total Populasi Desa')
                            ->numeric()
                            ->default(0)
                            ->helperText('Otomatis diambil dari data desa, tapi bisa diedit manual jika diperlukan.'),
                            // jumlah penduduk diambil dari desa, tapi tetap bisa diedit manual jika diperlukan 

                        TextInput::make('potensi_ekonomi')
                            ->label('Potensi Ekonomi Utama')
                            ->placeholder('Contoh: Pertanian, Perdagangan'),
                    ])->columns(2),

                    Group::make([
                        TextInput::make('batas_utara')
                            ->label('Batas Wilayah Utara'),

                        TextInput::make('batas_selatan')
                            ->label('Batas Wilayah Selatan'),
                    ])->columns(2),

                    Textarea::make('keterangan_geografis')
                        ->label('Keterangan Geografis')
                        ->placeholder('Contoh: Dataran rendah, bantaran sungai...')
                        ->rows(3)
                        ->columnSpanFull(),
                ]),
            ]);
    }
}
