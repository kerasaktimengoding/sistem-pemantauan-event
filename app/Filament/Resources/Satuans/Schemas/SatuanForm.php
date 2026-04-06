<?php

namespace App\Filament\Resources\Satuans\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SatuanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
              Section::make('Master Data Satuan')
                    ->description('Kelola satuan ukuran untuk komoditas (misal: Kg, Liter, Bungkus).')
                    ->schema([
                        Group::make([
                            TextInput::make('kode_satuan')
                                ->label('Kode Satuan')
                                ->required()
                                ->maxLength(20) 
                                ->unique('satuans', 'kode_satuan', ignoreRecord: true)
                                ->placeholder('Contoh: KG, LTR, PCS')
                                ->validationMessages([
                                    'unique' => 'Kode satuan ini sudah ada dalam sistem.',
                                ])
                                ->columnSpan(2),

                            TextInput::make('nama_satuan')
                                ->label('Nama Satuan')
                                ->required()
                                ->maxLength(50) 
                                ->placeholder('Contoh: Kilogram, Liter, Pcs')
                                ->columnSpan(2),
                        ])->columns(4),
                    ]),
            ]);
    }
}
