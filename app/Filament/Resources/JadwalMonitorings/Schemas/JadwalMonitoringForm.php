<?php

namespace App\Filament\Resources\JadwalMonitorings\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Group;
use Filament\Forms\Components\ToggleButtons;
use Illuminate\Support\Str;

class JadwalMonitoringForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
           ->components([
                
                // SECTION 1: INFORMASI JADWAL
                Section::make('Informasi Utama Jadwal')
                    ->description('Isi kode jadwal dan tentukan tanggal rencana monitoring.')
                    ->icon('heroicon-m-calendar-days')
                    ->schema([
                        Group::make([
                            TextInput::make('kode_jadwal')
                                ->label('Kode Jadwal')
                                ->required()
                                ->unique('jadwal_monitorings', 'kode_jadwal', ignoreRecord: true)
                                ->validationMessages([
                                    'unique' => 'Kode jadwal sudah terdaftar, gunakan kode lain.',
                                ])
                                ->default(fn() => 'JMW-' . date('d').'.' . date('m').'.' . date('Y') . '-' . strtoupper(Str::random(5)))
                                ->placeholder('Contoh: JMW-2026-001')
                                ->columnSpan(2),

                            DatePicker::make('tanggal_rencana')
                                ->label('Tanggal Rencana Monitoring')
                                ->required()
                                ->native(false) // Menggunakan kalender pop-up khas Filament yang lebih rapi
                                ->displayFormat('d F Y')
                                // ->closeOnDateSelect()
                                ->columnSpan(2),
                        ])->columns(4),
                    ]),

                // SECTION 2: LOKASI & PETUGAS (RELATIONSHIP)
                Section::make('Lokasi & Petugas Pelaksana')
                    ->description('Pilih pasar yang dipantau beserta pegawai yang bertugas.')
                    ->icon('heroicon-m-map-pin')
                    ->schema([
                        // Pilih Pasar beserta form tambah cepat jika data belum ada
                        Select::make('pasar_id')
                            ->label('Pasar / Lokasi')
                            ->relationship('pasar', 'nama_pasar') // Diubah ke nama_pasar agar user tidak bingung memilih ID
                            ->searchable()
                            ->preload()
                            ->required()
                            ->columnSpan(2)
                            ->createOptionForm([
                                TextInput::make('nama_pasar')
                                    ->label('Nama Pasar Baru')
                                    ->required(),
                                TextInput::make('lokasi_pasar')
                                    ->label('Alamat / Lokasi Pasar'),
                            ]),

                        // Pilih Pegawai beserta form tambah cepat jika data belum ada
                        Select::make('pegawai_id')
                            ->label('Pegawai / Petugas Lapangan')
                            ->relationship('pegawai', 'nama_pegawai') // Diubah ke nama_pegawai agar user tidak bingung memilih ID
                            ->searchable()
                            ->preload()
                            ->required()
                            ->columnSpan(2)
                            ->createOptionForm([
                                TextInput::make('nip')
                                    ->label('NIP')
                                    ->required()
                                    ->numeric(),
                                TextInput::make('nama')
                                    ->label('Nama Lengkap')
                                    ->required(),
                            ]),
                    ])->columns(4),

                // SECTION 3: LEGALITAS & STATUS
                Section::make('Legalitas & Status Monitoring')
                    ->description('Masukkan nomor surat tugas resmi dan status berjalannya pemantauan.')
                    ->icon('heroicon-m-document-check')
                    ->schema([
                        TextInput::make('nomor_surat_tugas')
                            ->label('Nomor Surat Tugas')
                            ->placeholder('Contoh: 090/123/ST-DISKOPUKMP/2026')
                            ->columnSpan(2),

                        // Menggunakan ToggleButtons agar opsi status tampak seperti badge klik yang estetik
                        ToggleButtons::make('status_monitoring')
                            ->label('Status Monitoring')
                            ->options([
                                'Pending' => 'Pending',
                                'Proses' => 'Proses',
                                'Selesai' => 'Selesai',
                                'Batal' => 'Batal',
                            ])
                            ->colors([
                                'Pending' => 'warning',
                                'Proses' => 'info',
                                'Selesai' => 'success',
                                'Batal' => 'danger',
                            ])
                            ->icons([
                                'Pending' => 'heroicon-m-clock',
                                'Proses' => 'heroicon-m-arrow-path',
                                'Selesai' => 'heroicon-m-check-circle',
                                'Batal' => 'heroicon-m-x-circle',
                            ])
                            ->default('Pending')
                            ->required()
                            ->inline()
                            ->columnSpan(2),
                    ])->columns(4),

                // SECTION 4: CATATAN TAMBAHAN
                Section::make('Hasil / Catatan Lapangan')
                    ->description('Tulis temuan atau catatan penting dari petugas setelah monitoring selesai.')
                    ->icon('heroicon-m-pencil-square')
                    ->schema([
                        Textarea::make('catatan_petugas')
                            ->label('Catatan Petugas')
                            ->placeholder('Tuliskan detail kondisi pasar, harga komoditas, atau kendala di sini...')
                            ->rows(4)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
