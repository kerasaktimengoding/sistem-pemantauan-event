<?php

namespace App\Filament\Resources\DetailEvents\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;

class DetailEventForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
               Section::make('Kaitan & Identitas Detail')
                    ->description('Hubungkan detail ini dengan event utama dan tentukan kodenya.')
                    ->schema([
                        Group::make([
                            Select::make('event_id')
                                ->label('Pilih Event Utama')
                                ->relationship('event', 'nama_event')
                                ->searchable()
                                ->preload()
                                ->required(),

                            TextInput::make('kode_detail_event')
                                ->label('Kode Detail Event')
                                ->required()
                                ->maxLength(20)
                                ->unique('detail_events', 'kode_detail_event', ignoreRecord: true)
                                ->validationMessages([
                                    'unique' => 'Kode Detail Event ini sudah ada',
                                    'required' => 'Kode Detail Event wajib diisi',
                                ])
                                ->placeholder('Contoh: DET-EVT-001'),
                        ])->columns(2),
                    ]),

                // Section 2: Deskripsi & Narasumber
                Section::make('Konten & Pelaksana')
                    ->description('Informasi mendalam mengenai isi kegiatan dan pengisi acara.')
                    ->schema([
                        Textarea::make('deskripsi_event')
                            ->label('Deskripsi Lengkap Kegiatan')
                            ->required()
                            ->rows(4)
                            ->columnSpanFull()
                            ->placeholder('Jelaskan rincian agenda atau materi kegiatan...'),

                        Group::make([
                            TextInput::make('penyelenggara')
                                ->label('Instansi Penyelenggara')
                                ->required()
                                ->maxLength(100)
                                ->placeholder('Contoh: Bidang Perdagangan DKUMPP'),

                            TextInput::make('narasumber')
                                ->label('Nama Narasumber / Pemateri')
                                ->required()
                                ->maxLength(100)
                                ->placeholder('Masukkan nama lengkap beserta gelar'),
                        ])->columns(2),
                    ]),

                // Section 3: Anggaran & Kapasitas
                Section::make('Anggaran & Kuota')
                    ->description('Manajemen sumber daya keuangan dan kapasitas peserta.')
                    ->schema([
                        Group::make([
                            TextInput::make('anggaran_event')
                                ->label('Anggaran Kegiatan')
                                ->numeric()
                                ->prefix('Rp')
                                ->required()
                                ->placeholder('0.00'),

                            TextInput::make('kuota_peserta')
                                ->label('Kuota Peserta')
                                ->numeric()
                                ->required()
                                ->minValue(1)
                                ->suffix('Orang')
                                ->placeholder('Contoh: 50'),
                        ])->columns(2),
                    ]),
            ]);
    }
}
