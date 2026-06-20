<?php

namespace App\Filament\Resources\RekapHargas\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class RekapHargaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Rekapitulasi')
                    ->description('Tentukan parameter wilayah, komoditas, dan periode yang akan direkap.')
                    ->schema([
                        Group::make([
                            TextInput::make('kode_rekap_harga')
                                ->label('Kode Rekap')
                                ->required()
                                ->maxLength(20)
                                ->unique('rekap_hargas', 'kode_rekap_harga', ignoreRecord: true)
                                ->validationMessages([
                                    'unique' => 'Kode Rekap Harga ini sudah ada',
                                    'required' => 'Kode Rekap Harga wajib diisi',
                                ])
                                ->default(fn() => 'RHP-' . date('d') . '.' . date('m') . '.' . date('Y') . '-' . strtoupper(Str::random(5)))
                                ->placeholder('Contoh: RHP-202403-01'),

                            DatePicker::make('periode_rekap')
                                ->label('Periode Rekap')
                                ->required()
                                ->native(false)
                                ->displayFormat('F Y'), // Menampilkan Nama Bulan dan Tahun
                        ])->columns(2),

                        Group::make([
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
                        ])->columns(2),

                        
                                // BUAT AGAR BISA SELAIN DESA YAITU TEMPAT, PEDAGANG
                                
                        // Select::make('pasar_id')
                        // ->label('Pilih Pasar')
                        // ->options(function (callable $get) {
                        //     // Ambil desa_id dari input "Pilih Desa"
                        //     $desaId = $get('desa_id');
                        //     $tempatId = $get('tempat_id');

                            
                        //     // Jika tidak ada desa_id, kembalikan array kosong
                        //     if (!$desaId) {
                        //         return [];
                        //     }
                            
                        //     // Ambil pasar berdasarkan desa_id
                        //     return \App\Models\Pasar::where('desa_id', $desaId)
                        //         ->pluck('nama_pasar', 'id')
                        //         ->toArray();
                        // })
                        // ->required()
                        // ->live(), // Tambahkan live() agar dropdown update saat desa berubah


                





                    ]),

                // Section 2: Hasil Analisis Statistik Harga
                Section::make('Hasil Analisis Harga')
                    ->description('Ringkasan statistik harga berdasarkan data yang masuk pada periode ini.')
                    ->schema([
                        Group::make([
                            TextInput::make('harga_rata_rata')
                                ->label('Harga Rata-Rata')
                                ->numeric()
                                ->prefix('Rp')
                                ->required()
                                ->placeholder('0.00'),

                            TextInput::make('harga_maksimum')
                                ->label('Harga Tertinggi')
                                ->numeric()
                                ->prefix('Rp')
                                ->required()
                                ->placeholder('0.00'),

                            TextInput::make('harga_minimum')
                                ->label('Harga Terendah')
                                ->numeric()
                                ->prefix('Rp')
                                ->required()
                                ->placeholder('0.00'),
                        ])->columns(3),
                    ]),
            ]);
    }
}
