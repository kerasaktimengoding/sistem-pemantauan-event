<?php

namespace App\Filament\Resources\Tempats\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\ToggleButtons;
use Illuminate\Support\Str;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;

class TempatForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Utama Tempat Usaha')
                    ->description('Kelola detail identitas, ukuran, dan status operasional tempat usaha pedagang.')
                    ->icon('heroicon-m-building-storefront')
                    ->schema([
                        Grid::make(3)->schema([
                            TextInput::make('kode_tempat_usaha')
                                ->label('Kode Tempat Usaha')
                                ->placeholder('Contoh: KIOS-A01')
                                ->required()
                                ->default(fn() => 'KOS-' . date('d') . '.' . date('m') . '.' . date('Y') . '-' . strtoupper(Str::random(5)))
                                ->unique('tempats', 'kode_tempat_usaha', ignoreRecord: true)
                                ->dehydrated()
                                ->validationMessages([
                                    'unique' => 'Kode tempat usaha sudah terdaftar di sistem.',
                                    'required' => 'Kode tempat usaha wajib diisi.',
                                ]),

                            TextInput::make('nomor_tempat')
                                ->label('Nomor Tempat / Blok')
                                ->placeholder('Contoh: No. 12 / Blok B')
                                ->required()
                                ->validationMessages([
                                    'required' => 'Nomor atau lokasi blok wajib diisi.',
                                ]),

                            TextInput::make('luas_tempat')
                                ->label('Luas Ukuran Tempat')
                                ->placeholder('Contoh: 3x4 atau 12')
                                ->numeric()
                                ->suffix(' m²') // Menambahkan satuan luas premium di ujung kanan inputan
                                ->helperText('Masukkan dalam angka meter persegi.'),
                        ]),

                        Grid::make(2)->schema([
                            Select::make('jenis_tempat')
                                ->label('Jenis Tempat Usaha')
                                ->options([
                                    'Toko' => 'Toko',
                                    'Kios' => 'Kios',
                                    'Los' => 'Los',
                                    'Lapak' => 'Lapak',
                                    'Grosir_Agen' => 'Grosir / Agen',
                                    'Swalayan' => 'Swalayan',
                                    'Tenda' => 'Tenda',
                                ])
                                ->native(false)
                                ->preload()
                                ->required()
                                ->validationMessages([
                                    'required' => 'Silahkan pilih salah satu jenis tempat usaha.',
                                ]),

                            // Mengubah status tempat konvensional menjadi Toggle Button interaktif berwarna
                            ToggleButtons::make('status_tempat')
                                ->label('Status Ketersediaan / Operasional')
                                ->options([
                                    'Buka' => 'Buka',
                                    'Tutup' => 'Tutup',
                                    'Kosong' => 'Kosong',
                                    'Dijual' => 'Dijual',
                                    'Disewakan' => 'Disewakan',
                                ])
                                ->colors([
                                    'Buka' => 'success',      // Hijau
                                    'Tutup' => 'danger',      // Merah
                                    'Kosong' => 'gray',       // Abu-abu
                                    'Dijual' => 'warning',    // Jingga
                                    'Disewakan' => 'info',    // Biru
                                ])
                                ->icons([
                                    'Buka' => 'heroicon-m-check-circle',
                                    'Tutup' => 'heroicon-m-x-circle',
                                    'Kosong' => 'heroicon-m-minus-circle',
                                    'Dijual' => 'heroicon-m-currency-dollar',
                                    'Disewakan' => 'heroicon-m-arrow-path',
                                ])
                                ->default('Kosong')
                                ->inline() // Menyusun tombol secara horizontal agar hemat ruang vertikal
                                ->required(),
                        ]),
                    ]),

                Section::make('Relasi Pasar & Kepemilikan Pedagang')
                    ->description('Tentukan lokasi unit pasar serta data pedagang pemilik/penyewa.')
                    ->icon('heroicon-m-arrows-right-left')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('pasar_id')
                                ->label('Lokasi Pasar Induk')
                                ->relationship('pasar', 'nama_pasar') // Menghubungkan ke relasi 'pasar' dan mengambil kolom 'nama_pasar'
                                ->searchable()
                                ->preload()
                                ->required()

                                // Pilihan Terbaik: Menggunakan createOptionForm langsung di dalam Select
                                ->createOptionForm([
                                    TextInput::make('nama_pasar')
                                        ->label('Nama Pasar')
                                        ->placeholder('Contoh: Pasar Bauntung')
                                        ->required()
                                        ->maxLength(255),

                                    TextInput::make('alamat_pasar')
                                        ->label('Alamat Pasar')
                                        ->placeholder('Contoh: Jl. Pangeran Suriansyah')
                                        ->required()
                                        ->maxLength(255),
                                ])

                                ->validationMessages([
                                    'required' => 'Lokasi pasar induk wajib ditentukan.',
                                ]),

                            Select::make('pedagang_id')
                                ->label('Nama Pedagang (Pemilik/Penyewa)')
                                ->relationship('pedagang', 'nama_pedagang') // Asumsi field nama pedagang adalah nama_pedagang
                                ->searchable()
                                ->preload()
                                ->required()
                                ->createOptionForm([ // Fitur pop-up tambah pedagang instan
                                    TextInput::make('nama_pedagang')->required(),
                                    TextInput::make('nik_pedagang')->required()->numeric(),
                                    TextInput::make('nomor_hp')->required(),
                                ])
                                ->validationMessages([
                                    'required' => 'Identitas pedagang wajib dipilih.',
                                ]),
                        ]),
                    ]),

                Section::make('Cakupan Wilayah & Kontak Darurat')
                    ->description('Sinkronisasi otomatis wilayah administratif desa/kecamatan tempat usaha berada.')
                    ->icon('heroicon-m-map-pin')
                    ->schema([
                        Grid::make(3)->schema([
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

                            Select::make('kecamatan_id')
                                ->label('Kecamatan Induk')
                                ->relationship('kecamatan', 'nama_kecamatan')
                                ->searchable()
                                ->preload()
                                ->required()
                                ->disabled()
                                ->dehydrated()
                                ->helperText('Otomatis terisi berdasarkan desa yang dipilih.'),


                            TextInput::make('nomor_hp')
                                ->label('Nomor HP / WhatsApp Operasional')
                                ->tel() // Mengubah ke tipe telephone input khusus mobile browser
                                ->placeholder('Contoh: 081234567xxx')
                                ->required()
                                ->prefix('+62') // Memberikan penanda kode negara Indonesia di awal kolom input
                                ->validationMessages([
                                    'required' => 'Nomor HP operasional wajib diisi untuk koordinasi lapangan.',
                                ]),
                        ]),

                        Textarea::make('keterangan')
                            ->label('Catatan Tambahan Mengenai Tempat Usaha')
                            ->placeholder('Masukkan deskripsi kondisi bangunan, fasilitas kelistrikan, atau riwayat sewa lainnya di sini...')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
