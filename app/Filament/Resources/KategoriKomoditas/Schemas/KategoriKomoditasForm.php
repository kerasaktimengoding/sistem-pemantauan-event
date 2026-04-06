<?php

namespace App\Filament\Resources\KategoriKomoditas\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class KategoriKomoditasForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Kategori Komoditas')
                    ->description('Kelola data kategori untuk pengelompokan barang/komoditas.') 
                    ->schema([
                        Group::make([
                            TextInput::make('kode_kategori')
                                ->label('Kode Kategori')
                                ->required()
                                ->maxLength(20) 
                                ->unique('kategori_komoditas', 'kode_kategori', ignoreRecord: true)
                                ->placeholder('Contoh: KTG-001')
                                ->validationMessages([
                                    'unique' => 'Kode kategori ini sudah digunakan.',
                                ])
                                ->columnSpan(2),

                            TextInput::make('nama_kategori')
                                ->label('Nama Kategori')
                                ->required()
                                ->maxLength(100) 
                                ->placeholder('Contoh: Bahan Pokok')
                                ->columnSpan(2),
                        ])->columns(4), // Membuat tampilan berjajar jika layar lebar
                    ]),
            ]);
    }
}
