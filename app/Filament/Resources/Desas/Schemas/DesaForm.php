<?php

namespace App\Filament\Resources\Desas\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Group;
use Filament\Forms\Components\Select;
use Illuminate\Support\Str;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;

class DesaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                // SECTION 1: PROFIL & IDENTITAS DESA
                Section::make('Profil Utama Desa / Kelurahan')
                    ->description('Masukkan informasi dasar kode, nama wilayah desa, dan relasi kecamatan.')
                    ->icon('heroicon-m-building-office')
                    ->schema([
                        Group::make([
                            Select::make('jenis')
                                ->options([
                                    'desa' => 'Desa',
                                    'kelurahan' => 'Kelurahan',
                                ])
                                ->default('desa')
                                ->required()
                                ->live() // Memicu perubahan real-time
                                ->label('Jenis Wilayah')
                                ->afterStateUpdated(function (string $state, Set $set) {
                                    // LOGIKA UTAMA: Setiap kali jenis diubah, langsung tembak isi nilai baru ke 'kode_desa'
                                    $tanggal = date('dmY');
                                    $random = strtoupper(Str::random(5));

                                    if ($state === 'kelurahan') {
                                        $set('kode_desa', "KEL-{$tanggal}-{$random}");
                                    } else {
                                        $set('kode_desa', "DESA-{$tanggal}-{$random}");
                                    }
                                })
                                ->columnSpan(4),

                            TextInput::make('kode_desa')
                                ->label(fn(Get $get) => $get('jenis') === 'kelurahan' ? 'Kode Kelurahan' : 'Kode Desa')
                                ->required()
                                ->unique('desas', 'kode_desa', ignoreRecord: true)
                                // Nilai awal saat form pertama kali dibuka (saat belum ada klik perubahan)
                                ->default(function () {
                                    $tanggal = date('dmY');
                                    $random = strtoupper(Str::random(5));
                                    return "DESA-{$tanggal}-{$random}";
                                })
                                ->validationMessages([
                                    'unique' => 'Kode wilayah/desa ini sudah terdaftar dalam database.',
                                ])
                                ->placeholder('Contoh: 63.03.01.2001')
                                ->columnSpan(2),

                            TextInput::make('nama_desa')
                                ->label(fn(Get $get) => $get('jenis') === 'kelurahan' ? 'Nama Kelurahan' : 'Nama Desa')
                                ->required()
                                ->placeholder('Contoh: Antasan Senor')
                                ->columnSpan(2),

                            Select::make('kecamatan_id')
                                ->label('Kecamatan Induk')
                                ->relationship('kecamatan', 'nama_kecamatan')
                                ->required()
                                ->searchable()
                                ->preload()
                                // Fitur opsional: Memungkinkan tambah data kecamatan langsung dari form desa jika belum ada
                                ->createOptionForm([
                                    TextInput::make('kode_kecamatan')->required(),
                                    TextInput::make('nama_kecamatan')->required(),
                                    TextInput::make('alamat_kantor')->required(),
                                    TextInput::make('jumlah_penduduk')->numeric()->required(),
                                ])
                                ->placeholder('Pilih kecamatan induk')
                                ->columnSpanFull(),

                        ])->columns(4),
                    ]),

                // SECTION 2: PIMPINAN DESA (PEMBAKAL / KEPALA DESA)
              // SECTION 2: PIMPINAN WILAYAH
    Section::make(fn(Get $get) => $get('jenis') === 'kelurahan' ? 'Pimpinan Kelurahan (Lurah)' : 'Pimpinan Desa (Pembakal / Kades)')
        ->description('Informasi pejabat yang saat ini aktif menjabat.')
        ->icon('heroicon-m-user-circle')
        ->schema([
            Group::make([
                TextInput::make('nama_pembakal')
                    ->label(fn(Get $get) => $get('jenis') === 'kelurahan' ? 'Nama Lengkap Lurah' : 'Nama Lengkap Pembakal / Kades')
                    ->placeholder('Masukkan nama beserta gelar pimpinan')
                    ->default(null)
                    ->columnSpan(2),

                TextInput::make('no_hp_pembakal')
                    ->label(fn(Get $get) => $get('jenis') === 'kelurahan' ? 'No. HP / WhatsApp Lurah' : 'No. HP / WhatsApp Pembakal')
                    ->tel()
                    ->placeholder('Contoh: 0812xxxxxxxx')
                    ->default(null)
                    ->columnSpan(2),
            ])->columns(4),
        ]),

    // SECTION 3: KONTAK, ALAMAT, & LOGISTIK
    Section::make(fn(Get $get) => $get('jenis') === 'kelurahan' ? 'Kontak & Alamat Kantor Kelurahan' : 'Kontak & Alamat Kantor Desa')
        ->description('Detail lokasi fisik kantor pelayanan wilayah dan kode pos.')
        ->icon('heroicon-m-map-pin')
        ->schema([
            Group::make([
                TextInput::make('alamat_kantor_desa')
                    ->label(fn(Get $get) => $get('jenis') === 'kelurahan' ? 'Alamat Kantor Kelurahan' : 'Alamat Kantor Desa')
                    ->required()
                    ->placeholder('Nama jalan, nomor, RT/RW, lingkungan')
                    ->columnSpan(3),

                TextInput::make('kode_pos')
                    ->label('Kode Pos')
                    ->required()
                    ->length(5)
                    ->placeholder('Contoh: 70614')
                    ->columnSpan(1),
            ])->columns(4),
        ]),

    // SECTION 4: KOORDINAT GEOGRAFIS (PEMETAAN)
    // Section::make('Titik Koordinat Geografis')
    //     ->description('Data koordinat peta untuk keperluan pemetaan wilayah.')
    //     ->icon('heroicon-m-globe-asia-australia')
    //     ->schema([
    //         Group::make([
    //             TextInput::make('latitude')
    //                 ->label('Garisan Lintang (Latitude)')
    //                 ->numeric()
    //                 ->placeholder('Contoh: -3.414341')
    //                 ->default(null)
    //                 ->columnSpan(2),

    //             TextInput::make('longitude')
    //                 ->label('Garisan Bujur (Longitude)')
    //                 ->numeric()
    //                 ->placeholder('Contoh: 114.846532')
    //                 ->default(null)
    //                 ->columnSpan(2),
    //         ])->columns(4),
    //     ]),

    // SECTION 5: STATUS AKTIVITAS WILAYAH
    Section::make('Status Wilayah')
        ->description('Pengaturan status keaktifan data administrasi wilayah.')
        ->icon('heroicon-m-check-circle')
        ->schema([
            Toggle::make('is_active')
                ->label(fn(Get $get) => $get('jenis') === 'kelurahan' ? 'Status Kelurahan Aktif' : 'Status Desa Aktif')
                ->helperText('Matikan opsi ini jika wilayah mengalami peleburan atau penonaktifan administratif.')
                ->default(true)
                ->inline(false)
                ->onColor('success')
                ->offColor('danger'),
        ]),
            ]);
    }
}
