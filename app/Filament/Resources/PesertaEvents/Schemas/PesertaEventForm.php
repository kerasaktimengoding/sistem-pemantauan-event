<?php

namespace App\Filament\Resources\PesertaEvents\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PesertaEventForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                //
                Section::make('Identitas Pribadi')
                    ->description('Masukan data diri lengkap peserta sesuai KTP.')
                    ->schema([
                        Group::make([
                            TextInput::make('event_id')
                                ->label('Kode Registrasi')
                                ->required()
                                ->maxLength(20)
                                ->unique('peserta_events', 'kode_peserta_event', ignoreRecord: true)
                                ->placeholder('Contoh: REG-EVT-001'),

                            Select::make('event_id')
                                ->label('Pilih Kegiatan / Event')
                                ->relationship('event', 'nama_event')
                                ->searchable()
                                ->preload()
                                ->required(),
                        ])->columns(2),

                        Group::make([
                            TextInput::make('nik')
                                ->label('NIK')
                                ->required()
                                ->numeric()
                                ->length(16)
                                ->placeholder('Masukkan 16 digit NIK')
                                ->unique('peserta_events', 'nik', ignoreRecord: true),

                            TextInput::make('nama_peserta')
                                ->label('Nama Lengkap')
                                ->required()
                                ->maxLength(100),
                        ])->columns(2),

                        Group::make([
                            Radio::make('jenis_kelamin')
                                ->label('Jenis Kelamin')
                                ->options([
                                    'Laki-laki' => 'Laki-laki',
                                    'Perempuan' => 'Perempuan',
                                ])
                                ->inline()
                                ->required(),

                            TextInput::make('no_hp')
                                ->label('Nomor HP/WA')
                                ->tel()
                                ->required()
                                ->maxLength(15),
                        ])->columns(2),
                    ]),

                // Section 2: Data Usaha & Lokasi
                Section::make('Informasi Usaha & Domisili')
                    ->description('Detail mengenai usaha yang dimiliki oleh peserta.')
                    ->schema([
                        Group::make([
                            TextInput::make('nama_usaha')
                                ->label('Nama Usaha / Brand')
                                ->required()
                                ->maxLength(100),

                            TextInput::make('jenis_produk')
                                ->label('Jenis Produk')
                                ->required()
                                ->maxLength(100)
                                ->placeholder('Contoh: Makanan Ringan / Kerajinan'),
                        ])->columns(2),

                        Select::make('wilayah_id')
                            ->label('Wilayah (Kecamatan/Desa)')
                            ->relationship('wilayah', 'nama_wilayah')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Textarea::make('alamat')
                            ->label('Alamat Lengkap')
                            ->required()
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),

                // Section 3: Status & Registrasi
                Section::make('Status Kepesertaan')
                    ->schema([
                        Group::make([
                            DatePicker::make('tanggal_registrasi')
                                ->label('Tanggal Daftar')
                                ->required()
                                ->default(now())
                                ->native(false),

                            ToggleButtons::make('status_partisipasi')
                                ->label('Status Partisipasi')
                                ->options([
                                    'Terdaftar' => 'Terdaftar',
                                    'Hadir' => 'Hadir',
                                    'Batal' => 'Batal',
                                ])
                                ->colors([
                                    'Terdaftar' => 'info',
                                    'Hadir' => 'success',
                                    'Batal' => 'danger',
                                ])
                                ->icons([
                                    'Terdaftar' => 'heroicon-o-user',
                                    'Hadir' => 'heroicon-o-check',
                                    'Batal' => 'heroicon-o-check',
                                ])
                                ->default('Terdaftar')
                                ->inline(),
                        ])->columns(2),
                    ]),
            ]);
    }
}
