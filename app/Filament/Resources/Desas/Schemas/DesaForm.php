<?php

namespace App\Filament\Resources\Desas\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Group;
use Filament\Forms\Components\Select;
use Illuminate\Support\Str;

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
                            TextInput::make('kode_desa')
                                ->label('Kode Desa / Kelurahan')
                                ->required()
                                ->unique('desas', 'kode_desa', ignoreRecord: true)
                                ->default(fn() => 'DESA-' . date('d'). date('m') . date('Y') . '-' . strtoupper(Str::random(5)))
                                ->validationMessages([
                                    'unique' => 'Kode wilayah/desa ini sudah terdaftar dalam database.',
                                ])
                                ->placeholder('Contoh: 63.03.01.2001')
                                ->columnSpan(2),

                            TextInput::make('nama_desa')
                                ->label('Nama Desa / Kelurahan')
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
                Section::make('Pimpinan Desa (Pembakal / Kades)')
                    ->description('Informasi pejabat pembakal atau kepala desa yang saat ini aktif menjabat.')
                    ->icon('heroicon-m-user-circle')
                    ->schema([
                        Group::make([
                            TextInput::make('nama_pembakal')
                                ->label('Nama Lengkap Pembakal')
                                ->placeholder('Masukkan nama beserta gelar pembakal')
                                ->default(null)
                                ->columnSpan(2),

                            TextInput::make('no_hp_pembakal')
                                ->label('No. HP / WhatsApp Pembakal')
                                ->tel()
                                ->placeholder('Contoh: 0812xxxxxxxx')
                                ->default(null)
                                ->columnSpan(2),
                        ])->columns(4),
                    ]),

                // SECTION 3: KONTAK, ALAMAT, & LOGISTIK
                Section::make('Kontak & Alamat Kantor Desa')
                    ->description('Detail lokasi fisik kantor desa dan kode pos wilayah.')
                    ->icon('heroicon-m-map-pin')
                    ->schema([
                        TextInput::make('alamat_kantor_desa')
                            ->label('Alamat Kantor Desa')
                            ->required()
                            ->placeholder('Nama jalan, nomor, RT/RW, lingkungan')
                            ->columnSpanFull(),

                        TextInput::make('kode_pos')
                            ->label('Kode Pos')
                            ->required()
                            ->length(5)
                            ->placeholder('Contoh: 70614')
                            ->columnSpan(1),
                    ])->columns(3),

                // SECTION 4: KOORDINAT GEOGRAFIS (PEMETAAN)
                Section::make('Titik Koordinat Geografis')
                    ->description('Data koordinat peta untuk keperluan pemetaan wilayah desa.')
                    ->icon('heroicon-m-globe-asia-australia')
                    ->schema([
                        Group::make([
                            TextInput::make('latitude')
                                ->label('Garisan Lintang (Latitude)')
                                ->numeric()
                                ->placeholder('Contoh: -3.414341')
                                ->default(null)
                                ->columnSpan(2),

                            TextInput::make('longitude')
                                ->label('Garisan Bujur (Longitude)')
                                ->numeric()
                                ->placeholder('Contoh: 114.846532')
                                ->default(null)
                                ->columnSpan(2),
                        ])->columns(4),
                    ]),

                // SECTION 5: STATUS AKTIVITAS WILAYAH
                Section::make('Status Wilayah')
                    ->description('Pengaturan status keaktifan data administrasi wilayah desa.')
                    ->icon('heroicon-m-check-circle')
                    ->schema([
                        Toggle::make('is_active')
                            ->label('Status Desa Aktif')
                            ->helperText('Matikan opsi ini jika wilayah desa mengalami peleburan atau penonaktifan administratif.')
                            ->default(true)
                            ->inline(false)
                            ->onColor('success')
                            ->offColor('danger'),
                    ]),
            ]);
    }
}
