<?php

namespace App\Filament\Resources\Pedagangs\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

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
                                ->unique('pedagangs', 'nik', ignoreRecord: true),

                            TextInput::make('kode_pedagang')
                                ->label('Kode Pedagang')
                                ->required()
                                ->maxLength(20)
                                ->unique('pedagangs', 'kode_pedagang', ignoreRecord: true)
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

                            Select::make('jenis_tempat')
                                ->label('Jenis Tempat Usaha')
                                ->options([
                                    'Kios' => 'Kios',
                                    'Lapak' => 'Lapak',
                                    'Toko' => 'Toko',
                                    'Tenda' => 'Tenda',
                                ])
                                ->required()
                                ->native(false),
                        ])->columns(2),

                        Select::make('wilayah_id')
                            ->label('Wilayah Asal/Usaha')
                            ->relationship('wilayah', 'nama_wilayah')
                            ->searchable()
                            ->preload()
                            ->required(),

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
