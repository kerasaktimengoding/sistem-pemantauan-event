<?php

namespace App\Filament\Resources\TrenHargas\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TrenHargaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
               Section::make('Parameter Tren Harga')
                    ->description('Tentukan komoditas, wilayah, dan periode waktu yang dianalisis.')
                    ->schema([
                        Group::make([
                            Select::make('komoditas_id')
                                ->label('Komoditas')
                                ->relationship('komoditas', 'nama_komoditas')
                                ->searchable()
                                ->preload()
                                ->required(),

                            Select::make('wilayah_id')
                                ->label('Wilayah (Kecamatan & Desa)')
                                ->relationship('wilayah', 'nama_wilayah')
                                ->searchable()
                                ->preload()
                                ->required(),
                        ])->columns(2),

                        DatePicker::make('periode_tren')
                            ->label('Periode Analisis')
                            ->required()
                            ->native(false)
                            ->displayFormat('F Y')
                            ->placeholder('Pilih Bulan dan Tahun'),
                    ]),

                // Section 2: Perbandingan Harga
                Section::make('Perbandingan & Selisih')
                    ->description('Masukan harga awal dan akhir untuk melihat perubahan.')
                    ->schema([
                        Group::make([
                            TextInput::make('harga_awal')
                                ->label('Harga Awal Periode')
                                ->numeric()
                                ->prefix('Rp')
                                ->required()
                                ->placeholder('0.00'),

                            TextInput::make('harga_akhir')
                                ->label('Harga Akhir Periode')
                                ->numeric()
                                ->prefix('Rp')
                                ->required()
                                ->placeholder('0.00'),

                            TextInput::make('persentase_perubahan')
                                ->label('Persentase Perubahan')
                                ->numeric()
                                ->suffix('%')
                                ->required()
                                ->placeholder('0.00'),
                        ])->columns(3),
                    ]),

                // Section 3: Kesimpulan Tren
                Section::make('Kesimpulan Analisis')
                    ->schema([
                        ToggleButtons::make('arah_tren')
                            ->label('Arah Pergerakan Harga')
                            ->options([
                                'Naik' => 'Naik',
                                'Turun' => 'Turun',
                                'Stabil' => 'Stabil',
                            ])
                            ->colors([
                                'Naik' => 'danger',   // Merah biasanya untuk kenaikan harga (inflasi)
                                'Turun' => 'success',  // Hijau untuk penurunan harga
                                'Stabil' => 'info',    // Biru untuk stabil
                            ])
                            ->icons([
                                'Naik' => 'heroicon-o-arrow-trending-up',
                                'Turun' => 'heroicon-o-arrow-trending-down',
                                'Stabil' => 'heroicon-o-arrow-path',
                            ])
                            ->default('Stabil')
                            ->inline()
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
