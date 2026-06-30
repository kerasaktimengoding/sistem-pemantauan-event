<?php

namespace App\Filament\Resources\InputHargas\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class InputHargaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Referensi Data Monitoring')
                    ->description('Pilih entitas terkait untuk pendataan harga.')
                    ->schema([
                        Group::make([
                            TextInput::make('kode_input_harga')
                                ->label('Kode Transaksi')
                                ->required()
                                ->maxLength(20)
                                ->unique('input_hargas', 'kode_input_harga', ignoreRecord: true)
                                ->default(fn() => 'TRK-' . date('d').'.' . date('m').'.' . date('Y') . '-' . strtoupper(Str::random(5)))
                                ->validationMessages([
                                    'unique' => 'Kode Input Harga ini sudah ada',
                                ])
                                ->disabled()
                                ->dehydrated()
                                ->placeholder('Contoh: TRK-20240301-01'),

                            DatePicker::make('tanggal_input')
                                ->label('Tanggal Pengambilan Data')
                                ->required()
                                ->default(now())
                                ->native(false),
                        ])->columns(2),

                        Group::make([
                            Select::make('komoditas_id')
                                ->label('Komoditas / Barang')
                                ->relationship('komoditas', 'nama_komoditas')
                                ->searchable()
                                ->preload()
                                ->required(),

                            Select::make('pedagang_id')
                                ->label('Responden / Pedagang')
                                ->relationship('pedagang', 'nama_pedagang')
                                ->searchable()
                                ->preload()
                                ->required(),
                        ])->columns(2),

                        Group::make([
                            Select::make('pasar_id')
                                ->label('Lokasi Pasar')
                                ->relationship('pasar', 'nama_pasar')
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
                    ]),

                // Section 2: Detail Harga & Petugas
                Section::make('Detail Harga & Sumber Data')
                    ->schema([
                        Group::make([
                            TextInput::make('harga')
                                ->label('Nominal Harga')
                                ->numeric()
                                ->prefix('Rp')
                                ->required()
                                ->placeholder('0.00'),

                            Select::make('pegawai_id')
                                ->label('Petugas Enumerator')
                                ->relationship('pegawai', 'nama_pegawai')
                                ->searchable()
                                ->preload()
                                ->required(),
                        ])->columns(2),

                        TextInput::make('sumber_data')
                            ->label('Sumber Data')
                            ->required()
                            ->placeholder('Contoh: Survei Lapangan / Wawancara')
                            ->maxLength(50),

                        Textarea::make('keterangan')
                            ->label('Catatan Tambahan')
                            ->rows(3)
                            ->placeholder('Masukkan keterangan jika ada perubahan harga yang signifikan...')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
