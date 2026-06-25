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
                            ->label('Alamat Domisili')
                            ->required()
                            ->rows(3)
                            ->columnSpanFull(),

                        Select::make('desa_id')
                            ->label(function (Get $get) {
                                // Jika sudah dipilih, kita bisa cek tipenya untuk mempercantik label
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
                                // Opsi Tambahan: Menampilkan nama dengan format "Desa X" atau "Kel. Y" di dalam daftar pilihan
                                modifyQueryUsing: fn($query) => $query->orderBy('jenis', 'asc')->orderBy('nama_desa', 'asc')
                            )
                            // Menampilkan teks "Kel." atau "Desa" langsung di list dropdown agar user tidak bingung
                            ->getOptionLabelFromRecordUsing(fn($record) => $record->jenis === 'kelurahan' ? "Kel. {$record->nama_desa}" : "Desa {$record->nama_desa}")
                            ->searchable()
                            ->preload()
                            ->required()
                            ->live() // Memantau perubahan input secara real-time
                            ->afterStateUpdated(function ($state, Set $set) {
                                if ($state) {
                                    // Mencari data di tabel desas berdasarkan ID yang dipilih
                                    $desa = \App\Models\Desa::find($state);
                                    if ($desa) {
                                        // Otomatis mengisi kolom kecamatan_id
                                        $set('kecamatan_id', $desa->kecamatan_id);
                                    }
                                } else {
                                    $set('kecamatan_id', null);
                                }
                            }),

                        // Luar Kabupaten Banjar isi alamat sendiri 

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

                    ]),

                // Section 3: Status Pekerjaan
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
