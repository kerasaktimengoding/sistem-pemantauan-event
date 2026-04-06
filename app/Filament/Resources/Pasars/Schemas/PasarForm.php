<?php

namespace App\Filament\Resources\Pasars\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PasarForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Pasar')
                    ->description('Masukan detail nama dan kode unik pasar.')
                    ->schema([
                        Group::make([
                            TextInput::make('kode_pasar')
                                ->label('Kode Pasar')
                                ->required()
                                ->maxLength(20)
                                ->unique('pasars', 'kode_pasar', ignoreRecord: true)
                                ->placeholder('Contoh: PSR-001')
                                ->validationMessages([
                                    'unique' => 'Kode pasar ini sudah terdaftar.',
                                ]),

                            TextInput::make('nama_pasar')
                                ->label('Nama Pasar')
                                ->required()
                                ->maxLength(100)
                                ->placeholder('Contoh: Pasar Bauntung Batuah'),
                        ])->columns(2),
                    ]),

                // Section 2: Lokasi & Alamat
                Section::make('Lokasi & Alamat')
                    ->description('Detail letak geografis dan alamat lengkap pasar.')
                    ->schema([
                        Select::make('wilayah_id')
                            ->label('Wilayah (Kecamatan/Desa)')
                            ->relationship('wilayah', 'nama_wilayah')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->columnSpanFull(),

                        Textarea::make('alamat_pasar')
                            ->label('Alamat Lengkap')
                            ->required()
                            ->rows(3)
                            ->placeholder('Masukkan alamat lengkap pasar...')
                            ->columnSpanFull(),
                    ]),

                // Section 3: Status Operasional
                Section::make('Status Pasar')
                    ->schema([
                        ToggleButtons::make('status_pasar')
                            ->label('Status Operasional')
                            ->options([
                                'Aktif' => 'Aktif',
                                'Non-Aktif' => 'Non-Aktif',
                                'Renovasi' => 'Renovasi',
                            ])
                            ->colors([
                                'Aktif' => 'success',
                                'Non-Aktif' => 'danger',
                                'Renovasi' => 'warning',
                            ])
                            ->icons([
                                'Aktif' => 'heroicon-o-building-storefront',
                                'Non-Aktif' => 'heroicon-o-x-circle',
                                'Renovasi' => 'heroicon-o-wrench-screwdriver',
                            ])
                            ->default('Aktif')
                            ->inline(),
                    ]), 
            ]);
    }
}
