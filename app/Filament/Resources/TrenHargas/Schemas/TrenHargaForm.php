<?php

namespace App\Filament\Resources\TrenHargas\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

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
                            TextInput::make('kode_tren')
                                ->label('Kode Tren Harga')
                                ->required()
                                ->maxLength(20)
                                ->unique('tren_hargas', 'kode_tren', ignoreRecord: true)
                                ->default(fn() => 'TRN-' . date('d') . date('m') . date('Y') . '-' . strtoupper(Str::random(5)))
                                ->validationMessages([
                                    'unique' => 'Kode Tren Harga ini sudah ada',
                                    'required' => 'Kode Kehadiran wajib diisi',
                                ])
                                ->placeholder('Contoh: PRS-202403-001'),

                            Select::make('komoditas_id')
                                ->label('Komoditas')
                                ->relationship('komoditas', 'nama_komoditas')
                                ->searchable()
                                ->preload()
                                ->required(),


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
                            // ])->columns(2),
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
