<?php

namespace App\Filament\Resources\KehadiranEvents\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Forms\Components\ToggleButtons;
use Illuminate\Support\Str;

class KehadiranEventForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
               Section::make('Data Presensi Peserta')
                    ->description('Catat kehadiran peserta pada hari pelaksanaan event.')
                    ->schema([
                        Group::make([
                            TextInput::make('kode_kehadiran')
                                ->label('Kode Kehadiran')
                                ->required()
                                ->maxLength(20)
                                ->unique('kehadiran_events', 'kode_kehadiran', ignoreRecord: true)
                                ->validationMessages([
                                    'unique' => 'Kode Kehadiran ini sudah ada',
                                    'required' => 'Kode Kehadiran wajib diisi',
                                ])
                                ->default(fn() => 'PRS-' . date('d') . '.' . date('m') . '.' . date('Y') . '-' . strtoupper(Str::random(5)))
                                ->placeholder('Contoh: PRS-202403-001'),

                            Select::make('peserta_event_id')
                                ->label('Nama Peserta')
                                ->relationship('pesertaevent', 'nama_peserta')
                                ->searchable()
                                ->preload()
                                ->required()
                                ->helperText('Cari peserta berdasarkan nama yang terdaftar.'),
                        ])->columns(2),

                        Group::make([
                            DateTimePicker::make('waktu_kehadiran')
                                ->label('Waktu Kedatangan')
                                ->required()
                                ->default(now())
                                ->native(false)
                                ->displayFormat('d/m/Y H:i')
                                ->columnSpan(1),

                            ToggleButtons::make('status_kehadiran')
                                ->label('Status Kehadiran')
                                ->options([
                                    'Hadir' => 'Hadir',
                                    'Sakit' => 'Sakit',
                                    'Izin' => 'Izin',
                                    'Alpa' => 'Alpa',
                                ])
                                ->colors([
                                    'Hadir' => 'success',
                                    'Sakit' => 'warning',
                                    'Izin' => 'info',
                                    'Alpa' => 'danger',
                                ])
                                ->icons([
                                    'Hadir' => 'heroicon-o-check-circle',
                                    'Sakit' => 'heroicon-o-beaker',
                                    'Izin' => 'heroicon-o-envelope',
                                    'Alpa' => 'heroicon-o-x-circle',
                                ])
                                ->default('Hadir')
                                ->inline()
                                ->columnSpan(1),
                        ])->columns(2),
                    ]),

                // Section 2: Catatan Tambahan
                Section::make('Informasi Tambahan')
                    ->schema([
                        Textarea::make('catatan')
                            ->label('Catatan / Keterangan')
                            ->rows(3)
                            ->placeholder('Masukkan keterangan tambahan jika diperlukan (misal: alasan izin)...')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
