<?php

namespace App\Filament\Resources\EventKegiatans\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class EventKegiatanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
Section::make('Detail Kegiatan')
                    ->description('Informasi utama mengenai nama dan jenis kegiatan.')
                    ->schema([
                        Group::make([
                            TextInput::make('kode_event')
                                ->label('Kode Event')
                                ->required()
                                ->maxLength(20)
                                ->unique('event_kegiatans', 'kode_event', ignoreRecord: true)
                                ->validationMessages([
                                    'unique' => 'Kode event ini sudah terdaftar',
                                ])
                                ->placeholder('Contoh: EVT-2024-001'),

                            TextInput::make('nama_event')
                                ->label('Nama Kegiatan')
                                ->required()
                                ->maxLength(100)
                                ->placeholder('Contoh: Pelatihan Digital Marketing UMKM'),
                        ])->columns(2),

                        Select::make('jenis_event')
                            ->label('Jenis Kegiatan')
                            ->options([
                                'Pasar Murah' => 'Pasar Murah',
                                'Pelatihan' => 'Pelatihan',
                                'Sosialisasi' => 'Sosialisasi',
                                'Bazar' => 'Bazar',
                            ])
                            ->required()
                            ->native(false),
                    ]),

                // Section 2: Waktu & Lokasi
                Section::make('Waktu & Tempat Pelaksanaan')
                    ->description('Tentukan jadwal dan lokasi spesifik kegiatan.')
                    ->schema([
                        Group::make([
                            DatePicker::make('tanggal_mulai')
                                ->label('Tanggal Mulai')
                                ->required()
                                ->native(false)
                                ->displayFormat('d/m/Y'),

                            DatePicker::make('tanggal_selesai')
                                ->label('Tanggal Selesai')
                                ->required()
                                ->native(false)
                                ->displayFormat('d/m/Y')
                                ->afterOrEqual('tanggal_mulai'),
                        ])->columns(2),

                        Group::make([
                            Select::make('wilayah_id')
                                ->label('Wilayah (Kecamatan)')
                                ->relationship('wilayah', 'nama_wilayah')
                                ->searchable()
                                ->preload()
                                ->required(),

                            TextInput::make('lokasi_event')
                                ->label('Lokasi Spesifik')
                                ->required()
                                ->maxLength(150)
                                ->placeholder('Contoh: Aula Serbaguna Kantor Camat'),
                        ])->columns(2),
                    ]),

                // Section 3: Status Kegiatan
                Section::make('Status Pelaksanaan')
                    ->schema([
                        ToggleButtons::make('status_event')
                            ->label('Status Event')
                            ->options([
                                'Direncanakan' => 'Direncanakan',
                                'Berjalan' => 'Berjalan',
                                'Selesai' => 'Selesai',
                                'Dibatalkan' => 'Dibatalkan',
                            ])
                            ->colors([
                                'Direncanakan' => 'info',
                                'Berjalan' => 'warning',
                                'Selesai' => 'success',
                                'Dibatalkan' => 'danger',
                            ])
                            ->icons([
                                'Direncanakan' => 'heroicon-o-calendar',
                                'Berjalan' => 'heroicon-o-play',
                                'Selesai' => 'heroicon-o-check-circle',
                                'Dibatalkan' => 'heroicon-o-x-circle',
                            ])
                            ->default('Direncanakan')
                            ->inline(),
                    ]),
            ]);
    }
}
