<?php

namespace App\Filament\Resources\Komoditas\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class KomoditasForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
               Section::make('Informasi Komoditas')
                    ->description('Detail nama dan kode unik barang.') 
                    ->schema([
                        Group::make([
                            TextInput::make('kode_komoditas')
                                ->label('Kode Komoditas')
                                ->required()
                                ->maxLength(20)
                                ->unique('komoditas', 'kode_komoditas', ignoreRecord: true)
                                ->placeholder('Contoh: BAPOK-001'),

                            TextInput::make('nama_komoditas')
                                ->label('Nama Komoditas')
                                ->required()
                                ->maxLength(100)
                                ->placeholder('Contoh: Beras Premium'),
                        ])->columns(2),
                    ]),

                // Section Klasifikasi & Satuan
                Section::make('Klasifikasi & Satuan')
                    ->description('Tentukan pengelompokan dan satuan ukur komoditas.') 
                    ->schema([
                        Group::make([
                            Select::make('kategori_id')
                                ->label('Kategori')
                                ->relationship('kategori', 'nama_kategori') 
                                ->searchable()
                                ->preload()
                                ->required()
                                ->createOptionForm([
                                    TextInput::make('kode_kategori')->required(),
                                    TextInput::make('nama_kategori')->required(),
                                ]),

                            Select::make('satuan_id')
                                ->label('Satuan')
                                ->relationship('satuan', 'nama_satuan') 
                                ->searchable()
                                ->preload()
                                ->required()
                                ->createOptionForm([
                                    TextInput::make('kode_satuan')->required(),
                                    TextInput::make('nama_satuan')->required(),
                                ]),
                        ])->columns(2),
                    ]),

                // Section Deskripsi & Status
                Section::make('Detail Tambahan')
                    ->schema([
                        Textarea::make('deskripsi')
                            ->label('Deskripsi Barang')
                            ->rows(3)
                            ->placeholder('Tambahkan keterangan spesifikasi barang...'),

                        ToggleButtons::make('status_komoditas')
                            ->label('Status Komoditas')
                            ->options([
                                'Aktif' => 'Aktif',
                                'Non-Aktif' => 'Non-Aktif',
                            ])
                            ->colors([
                                'Aktif' => 'success',
                                'Non-Aktif' => 'danger',
                            ])
                            ->icons([
                                'Aktif' => 'heroicon-o-check-circle',
                                'Non-Aktif' => 'heroicon-o-x-circle',
                            ])
                            ->default('Aktif')
                            ->inline(),
                    ]),
            ]);
    }
}
