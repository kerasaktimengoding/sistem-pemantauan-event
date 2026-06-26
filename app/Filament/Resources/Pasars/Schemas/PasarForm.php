<?php

namespace App\Filament\Resources\Pasars\Schemas;

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

class PasarForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Pasar')
                    ->description('Masukan detail nama dan kode unik pasar.')
                    ->schema([
                        Group::make([
                            TextInput::make('kode_pasar')
                                ->label('Kode Pasar')
                                ->required()
                                ->maxLength(20)
                                ->unique('pasars', 'kode_pasar', ignoreRecord: true)
                                ->placeholder('Contoh: PSR-001')
                                ->default(fn() => 'PSR-' . date('d') . '.' . date('m') . '.' . date('Y') . '-' . strtoupper(Str::random(5)))
                                ->validationMessages([
                                    'unique' => 'Kode pasar ini sudah terdaftar.',
                                ]),

                            TextInput::make('nama_pasar')
                                ->label('Nama Pasar')
                                ->required()
                                ->maxLength(100)
                                ->placeholder('Contoh: Pasar Bauntung Batuah'),
                        ])->columns(2),
                    ]),

                // Section 2: Lokasi & Alamat
                Section::make('Lokasi & Alamat')
                    ->description('Detail letak geografis dan alamat lengkap pasar.')
                    ->schema([
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
                            ->disabled() // Dimatikan agar tidak diubah manual (sesuai permintaan)
                            ->dehydrated() // Tetap mengirim data ke database saat simpan
                            ->helperText('Otomatis terisi berdasarkan desa yang dipilih.'),

                        Textarea::make('alamat_pasar')
                            ->label('Alamat Lengkap')
                            ->required()
                            ->rows(3)
                            ->placeholder('Masukkan alamat lengkap pasar...')
                            ->columnSpanFull(),
                    ]),

                // Section 3: Status Operasional
                Section::make('Status Pasar')
                    ->schema([
                        ToggleButtons::make('status_pasar')
                            ->label('Status Operasional')
                            ->options([
                                'Aktif' => 'Aktif',
                                'Non-Aktif' => 'Non-Aktif',
                                'Renovasi' => 'Renovasi',
                            ])
                            ->colors([
                                'Aktif' => 'success',
                                'Non-Aktif' => 'danger',
                                'Renovasi' => 'warning',
                            ])
                            ->icons([
                                'Aktif' => 'heroicon-o-building-storefront',
                                'Non-Aktif' => 'heroicon-o-x-circle',
                                'Renovasi' => 'heroicon-o-wrench-screwdriver',
                            ])
                            ->default('Aktif')
                            ->inline(),
                    ]),
            ]);
    }
}
