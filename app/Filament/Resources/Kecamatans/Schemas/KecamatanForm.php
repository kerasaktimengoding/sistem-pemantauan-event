<?php

namespace App\Filament\Resources\Kecamatans\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Group;
use Filament\Forms\Components\Textarea;

class KecamatanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                // SECTION 1: PROFIL & IDENTITAS KECAMATAN
                Section::make('Profil Utama Kecamatan')
                    ->description('Masukkan informasi dasar kode dan nama wilayah kecamatan.')
                    ->icon('heroicon-m-building-office-2')
                    ->schema([
                        Group::make([
                            TextInput::make('kode_kecamatan')
                                ->label('Kode Kecamatan')
                                ->required()
                                ->unique('kecamatans', 'kode_kecamatan', ignoreRecord: true)
                                ->validationMessages([
                                    'unique' => 'Kode wilayah/kecamatan ini sudah terdaftar.',
                                ])
                                ->placeholder('Contoh: 63.03.01')
                                ->columnSpan(2),

                            TextInput::make('nama_kecamatan')
                                ->label('Nama Kecamatan')
                                ->required()
                                ->placeholder('Contoh: Martapura')
                                ->columnSpan(2),
                        ])->columns(4),
                    ]),

                // SECTION 2: PIMPINAN WILAYAH (CAMAT)
                Section::make('Pimpinan Kecamatan (Camat)')
                    ->description('Informasi pejabat camat yang saat ini aktif menjabat.')
                    ->icon('heroicon-m-user-circle')
                    ->schema([
                        Group::make([
                            TextInput::make('nama_camat')
                                ->label('Nama Lengkap Camat')
                                ->placeholder('Masukkan nama beserta gelar')
                                ->default(null)
                                ->columnSpan(2),

                            TextInput::make('nip_camat')
                                ->label('NIP Camat')
                                ->placeholder('Masukkan 18 digit NIP')
                                ->numeric()
                                ->default(null)
                                ->columnSpan(2),
                        ])->columns(4),
                    ]),

                // SECTION 3: KONTAK & ALAMAT KANTOR
                Section::make('Kontak & Alamat Kantor')
                    ->description('Detail lokasi kantor kecamatan dan saluran komunikasi resmi.')
                    ->icon('heroicon-m-map-pin')
                    ->schema([
                        TextInput::make('alamat_kantor')
                            ->label('Alamat Kantor Kecamatan')
                            ->required()
                            ->placeholder('Nama jalan, nomor, RT/RW, kelurahan/desa')
                            ->columnSpanFull(),

                        TextInput::make('no_telp')
                            ->label('No. Telepon Kantor')
                            ->tel()
                            ->placeholder('Contoh: 0511xxxxxx')
                            ->default(null)
                            ->columnSpan(1),

                        TextInput::make('email_kecamatan')
                            ->label('Email Resmi Kecamatan')
                            ->email()
                            ->placeholder('Contoh: kec.martapura@banjarkab.go.id')
                            ->default(null)
                            ->columnSpan(1),
                    ])->columns(2),

                // SECTION 4: DATA DEMOGRAFI & GEOGRAFIS
                Section::make('Demografi & Geografis')
                    ->description('Data cakupan luas wilayah dan kuantitas penduduk.')
                    ->icon('heroicon-m-chart-bar')
                    ->schema([
                        Group::make([
                            TextInput::make('luas_wilayah')
                                ->label('Luas Wilayah')
                                ->numeric()
                                ->suffix(' Km²') // Memberikan keterangan satuan yang jelas
                                ->placeholder('0.00')
                                ->default(null)
                                ->columnSpan(2),

                            TextInput::make('jumlah_penduduk')
                                ->label('Jumlah Penduduk')
                                ->required()
                                ->numeric()
                                ->suffix(' Jiwa') // Memberikan keterangan satuan yang jelas
                                ->placeholder('0')
                                ->default(0)
                                ->columnSpan(2),
                        ])->columns(4),
                    ]),

                // SECTION 5: CATATAN TAMBAHAN
                Section::make('Informasi Tambahan')
                    ->description('Catatan atau keterangan ringkas mengenai kecamatan terkait.')
                    ->icon('heroicon-m-document-text')
                    ->schema([
                        Textarea::make('keterangan')
                            ->label('Keterangan / Deskripsi')
                            ->placeholder('Tuliskan info tambahan jika ada...')
                            ->rows(3)
                            ->default(null)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
