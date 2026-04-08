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
use Illuminate\Support\Str;

class PesertaEventForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                //
               // Section 1: Identitas Pribadi
Section::make('Identitas Pribadi')
    ->description('Masukan data diri lengkap peserta sesuai KTP.')
    ->schema([
        Group::make([
            // PERBAIKAN 1: Nama field diganti menjadi 'kode_peserta_event' sesuai migration
            TextInput::make('kode_peserta_event')
                ->label('Kode Registrasi')
                ->required()
                ->maxLength(20)
                // PERBAIKAN 2: Gunakan unique yang benar mengarah ke kolom kode_peserta_event
                ->unique('peserta_events', 'kode_peserta_event', ignoreRecord: true)
                ->default(fn () => 'REG-' . strtoupper(Str::random(6)))
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
            // PERBAIKAN 3: Jika Radio error (mungkin lupa import), gunakan Select atau pastikan import Radio
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
                    'Hadir' => 'heroicon-o-check-circle', // PERBAIKAN 4: Ikon yang lebih tepat
                    'Batal' => 'heroicon-o-x-circle',
                ])
                ->default('Terdaftar')
                ->inline(),
        ])->columns(2),
    ]),
            ]);
    }
}
