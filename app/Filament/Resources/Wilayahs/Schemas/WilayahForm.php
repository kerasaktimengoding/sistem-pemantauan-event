<?php

namespace App\Filament\Resources\Wilayahs\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class WilayahForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Section Identitas Wilayah
                Section::make('Informasi Utama Wilayah')
                    ->description('Masukan detail kode dan nama wilayah administratif.')
                    ->schema([
                        Group::make([
                            TextInput::make('kode_wilayah')
                                ->label('Kode Wilayah')
                                ->required()
                                ->unique('wilayah', 'kode_wilayah', ignoreRecord: true)
                                ->placeholder('Contoh: 63.03.xx.xxxx')
                                ->validationMessages([
                                    'unique' => 'Kode wilayah ini sudah terdaftar.',
                                ]),

                            TextInput::make('nama_wilayah')
                                ->label('Nama Wilayah')
                                ->required()
                                ->placeholder('Masukkan Nama Kecamatan atau Desa'),
                        ])->columns(2),
                    ]),

                // Section Klasifikasi & Lokasi
                Section::make('Klasifikasi & Lokasi')
                    ->description('Tentukan tipe wilayah dan kode pos area tersebut.')
                    ->schema([
                        Group::make([
                            Select::make('tipe_wilayah')
                                ->label('Tipe Wilayah')
                                ->options([
                                    'Kecamatan' => 'Kecamatan',
                                    'Desa' => 'Desa',
                                    'Kelurahan' => 'Kelurahan',
                                ])
                                ->required()
                                ->native(false),

                            TextInput::make('kode_pos')
                                ->label('Kode Pos')
                                ->required()
                                ->numeric()
                                ->length(5)
                                ->placeholder('Contoh: 706xx'),
                        ])->columns(2),
                    ]),
            ]);
    }
}
