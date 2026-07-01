<?php

namespace App\Filament\Resources\Pegawais\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Forms\Components\Toggle;

class PegawaiForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Data Pribadi Pegawai')
                    ->description('Informasi dasar identitas pegawai.')
                    ->schema([
                        Group::make([
                            TextInput::make('nip')
                                ->label('NIP')
                                ->required()
                                ->numeric()
                                ->length(16)
                                ->unique('pegawais', 'nip', ignoreRecord: true)
                                ->validationMessages([
                                    'unique' => 'NIP ini sudah terdaftar',
                                ])
                                ->placeholder('Masukkan NIP'),

                            TextInput::make('nik')
                                ->label('NIK')
                                ->required()
                                ->numeric()
                                ->unique('pegawais', 'nik', ignoreRecord: true)
                                ->validationMessages([
                                    'unique' => 'NIK ini sudah terdaftar',
                                ])
                                ->length(16)
                                ->placeholder('Masukkan 16 digit NIK'),
                        ])->columns(2),

                        TextInput::make('nama_pegawai')
                            ->label('Nama Lengkap')
                            ->required()
                            ->maxLength(100),

                        Group::make([
                            Radio::make('jenis_kelamin')
                                ->label('Jenis Kelamin')
                                ->options([
                                    'Laki-laki' => 'Laki-laki',
                                    'Perempuan' => 'Perempuan',
                                ])
                                ->inline()
                                ->required(),

                            TextInput::make('tempat_lahir')
                                ->label('Tempat Lahir')
                                ->required(),

                            DatePicker::make('tanggal_lahir')
                                ->label('Tanggal Lahir')
                                ->required()
                                ->native(false),
                        ])->columns(3),
                    ]),

              // Section 2: Kontak & Domisili
                Section::make('Kontak & Alamat')
                    ->description('Informasi cara menghubungi pegawai dan tempat tinggal.')
                    ->schema([
                        Group::make([
                            TextInput::make('no_hp')
                                ->label('Nomor HP/WA')
                                ->tel()
                                ->required(),

                            TextInput::make('email')
                                ->label('Alamat Email')
                                ->email()
                                ->required(),
                        ])->columns(2),

                        Textarea::make('alamat')
                            ->label('Alamat Domisili (Jalan/RT/RW)')
                            ->required()
                            ->rows(3)
                            ->columnSpanFull(),

                        // 🌟 FITUR UTAMA: Sakelar penentu wilayah pegawai
                        Toggle::make('is_luar_kabupaten')
                            ->label('Pegawai Berasal dari Luar Kabupaten Banjar?')
                            ->default(false)
                            ->live() // Memantau perubahan secara real-time
                            ->afterStateUpdated(function ($state, Set $set) {
                                // Jika diaktifkan sebagai pegawai luar daerah, reset data wilayah internal
                                if ($state === true) {
                                    $set('desa_id', null);
                                    $set('kecamatan_id', null);
                                }
                            })
                            ->columnSpanFull(),

                        // Dropdown Desa (Hanya muncul jika is_luar_kabupaten bernilai FALSE)
                        Select::make('desa_id')
                            ->label(function (Get $get) {
                                $desaId = $get('desa_id');
                                if ($desaId) {
                                    $desa = \App\Models\Desa::find($desaId);
                                    return $desa && $desa->jenis === 'kelurahan' ? 'Kelurahan Terpilih' : 'Desa Terpilih';
                                }
                                return 'Pilih Desa / Kelurahan';
                            })
                            ->relationship(
                                name: 'desa',
                                titleAttribute: 'nama_desa',
                                modifyQueryUsing: fn($query) => $query->orderBy('jenis', 'asc')->orderBy('nama_desa', 'asc')
                            )
                            ->getOptionLabelFromRecordUsing(fn($record) => $record->jenis === 'kelurahan' ? "Kel. {$record->nama_desa}" : "Desa {$record->nama_desa}")
                            ->searchable()
                            ->preload()
                            ->live()
                            ->visible(fn (Get $get) => ! $get('is_luar_kabupaten')) // Sembunyi jika luar kabupaten
                            ->required(fn (Get $get) => ! $get('is_luar_kabupaten')) // Wajib diisi jika dalam kabupaten
                            ->afterStateUpdated(function ($state, Set $set) {
                                if ($state) {
                                    $desa = \App\Models\Desa::find($state);
                                    if ($desa) {
                                        $set('kecamatan_id', $desa->kecamatan_id);
                                    }
                                } else {
                                    $set('kecamatan_id', null);
                                }
                            }),

                        // Dropdown Kecamatan (Hanya muncul jika is_luar_kabupaten bernilai FALSE)
                        Select::make('kecamatan_id')
                            ->label('Kecamatan Induk')
                            ->relationship('kecamatan', 'nama_kecamatan')
                            ->searchable()
                            ->preload()
                            ->disabled() 
                            ->dehydrated() 
                            ->visible(fn (Get $get) => ! $get('is_luar_kabupaten')) // Sembunyi jika luar kabupaten
                            ->required(fn (Get $get) => ! $get('is_luar_kabupaten')) // Wajib diisi jika dalam kabupaten
                            ->helperText('Otomatis terisi berdasarkan desa yang dipilih.'),

                        // Input Alamat Luar (Hanya muncul jika is_luar_kabupaten bernilai TRUE)
                        Textarea::make('alamat_luar')
                            ->label('Detail Wilayah Luar Kabupaten (Contoh: Kota Banjarbaru, Kalsel)')
                            ->placeholder('Tuliskan nama Kabupaten/Kota dan Provinsi asal pegawai...')
                            ->visible(fn (Get $get) => $get('is_luar_kabupaten')) // Tampil jika luar kabupaten
                            ->required(fn (Get $get) => $get('is_luar_kabupaten')) // Wajib diisi jika luar kabupaten
                            ->dehydrated(fn (Get $get) => $get('is_luar_kabupaten')) // Hanya kirim ke DB jika aktif
                            ->rows(2)
                            ->maxLength(255)
                            ->columnSpanFull(),
                    ]),

                // Section 3: Status Kepegawaian
                Section::make('Informasi Kepegawaian')
                    ->description('Detail jabatan dan status aktif pegawai.')
                    ->schema([
                        Group::make([
                            Select::make('jabatan_id')
                                ->label('Jabatan')
                                ->relationship('jabatan', 'nama_jabatan')
                                ->searchable()
                                ->preload()
                                ->required(),

                            Select::make('status_pegawai')
                                ->label('Status Pegawai')
                                ->options([
                                    'PNS' => 'PNS',
                                    'PPPK' => 'PPPK',
                                    'Honorer' => 'Honorer',
                                    'Aktif' => 'Aktif',
                                ])
                                ->required()
                                ->native(false),

                            DatePicker::make('tanggal_masuk')
                                ->label('Tanggal Mulai Tugas')
                                ->required()
                                ->native(false),
                        ])->columns(3),
                    ]),
            ]);
    }
}
