<?php

namespace App\Filament\Resources\Pedagangs\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;

class PedagangForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identitas Pedagang')
                    ->description('Informasi dasar dan legalitas pedagang.')
                    ->schema([
                        Group::make([
                            TextInput::make('nik')
                                ->label('NIK')
                                ->required()
                                ->numeric()
                                ->length(16)
                                ->placeholder('Masukkan 16 digit NIK')
                                ->unique('pedagangs', 'nik', ignoreRecord: true)
                                ->validationMessages([
                                    'unique' => 'NIK ini sudah terdaftar',
                                ]),

                            TextInput::make('kode_pedagang')
                                ->label('Kode Pedagang')
                                ->required()
                                ->maxLength(20)
                                ->unique('pedagangs', 'kode_pedagang', ignoreRecord: true)
                                ->default(fn() => 'PDG-' . date('d') . '.' . date('m') . '.' . date('Y') . '-' . strtoupper(Str::random(5)))
                                ->placeholder('Contoh: PDG-001'),
                        ])->columns(2),

                        TextInput::make('nama_pedagang')
                            ->label('Nama Lengkap Pedagang')
                            ->required()
                            ->maxLength(100)
                            ->placeholder('Masukkan nama sesuai KTP'),
                    ]),

                // Section 2: Kontak & Lokasi Usaha
                Section::make('Kontak & Domisili')
                    ->description('Informasi tempat usaha dan cara menghubungi pedagang.')
                    ->schema([
                        Group::make([
                            TextInput::make('no_hp')
                                ->label('Nomor HP / WA')
                                ->tel()
                                ->required()
                                ->maxLength(15),


                        ])->columns(2),



                        //    Select::make('pasar_id')
                        //         ->label('Pilih Pasar')
                        //         ->relationship('pasar', 'nama_pasar')
                        //         ->searchable()
                        //         ->preload()
                        //         ->required()
                        //         ->live() // Memantau perubahan input secara real-time
                        //         ->afterStateUpdated(function ($state, callable $set) {
                        //             // Mencari data pasar berdasarkan ID yang dipilih
                        //             $pasar = \App\Models\Pasar::find($state);
                        //             if ($pasar) {
                        //                 // Otomatis mengisi kolom desa_id
                        //                 $set('desa_id', $pasar->desa_id);
                        //             }
                        //         }),


                        // Select::make('tempat_id')
                        // ->label('Pilih Tempat')
                        // ->relationship('tempat', 'nama_tempat')
                        // ->searchable()
                        // ->preload()
                        // ->required()
                        // ->live() // Memantau perubahan input secara real-time
                        // ->afterStateUpdated(function ($state, callable $set) {
                        //     // Mencari data tempat berdasarkan ID yang dipilih
                        //     $tempat = \App\Models\Tempat::find($state);
                        //     if ($tempat) {
                        //         // Otomatis mengisi kolom desa_id
                        //         $set('desa_id', $tempat->desa_id);
                        //     }
                        // }),
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


                        // 2. Kecamatan Terisi Otomatis
                        Select::make('kecamatan_id')
                            ->label('Kecamatan Induk')
                            ->relationship('kecamatan', 'nama_kecamatan')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->disabled()
                            ->dehydrated()
                            ->helperText('Otomatis terisi berdasarkan desa yang dipilih.'),
                        Textarea::make('alamat')
                            ->label('Alamat Lengkap')
                            ->required()
                            ->rows(3)
                            ->placeholder('Masukkan alamat lengkap tempat tinggal atau usaha...'),
                    ]),

                // Section 3: Status
                Section::make('Status Keanggotaan')
                    ->schema([
                        ToggleButtons::make('status_pedagang')
                            ->label('Status Pedagang')
                            ->options([
                                'Aktif' => 'Aktif',
                                'Non-Aktif' => 'Non-Aktif',
                                'Tersuspend' => 'Tersuspend',
                            ])
                            ->colors([
                                'Aktif' => 'success',
                                'Non-Aktif' => 'danger',
                                'Tersuspend' => 'warning',
                            ])
                            ->icons([
                                'Aktif' => 'heroicon-o-check-badge',
                                'Non-Aktif' => 'heroicon-o-x-circle',
                                'Tersuspend' => 'heroicon-o-exclamation-triangle',
                            ])
                            ->default('Aktif')
                            ->inline(),
                    ]),
            ]);
    }
}
