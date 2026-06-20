<?php

namespace App\Filament\Resources\HasilPelatihans\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class HasilPelatihanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
               Section::make('Data Hasil Pelatihan')
                    ->description('Masukan nilai evaluasi peserta pelatihan.')
                    ->schema([
                        Group::make([
                            TextInput::make('kode_hasil_pelatihan')
                                ->label('Kode Hasil')
                                ->required()
                                ->maxLength(20)
                                ->unique('hasil_pelatihans', 'kode_hasil_pelatihan', ignoreRecord: true)
                                ->validationMessages([
                                    'unique' => 'Kode Hasil Pelatihan ini sudah ada',
                                    'required' => 'Kode Hasil Pelatihan wajib diisi',
                                ])  
                                ->placeholder('Contoh: HSL-2024-001'),

                            Select::make('peserta_event_id')
                                ->label('Nama Peserta')
                                ->relationship('pesertaevent', 'nama_peserta')
                                ->searchable()
                                ->preload()
                                ->required(),
                        ])->columns(2),
                    ]),

                // Section 2: Penilaian (Skor)
                Section::make('Evaluasi Nilai')
                    ->description('Masukan skor pre-test dan post-test untuk menentukan nilai akhir.')
                    ->schema([
                        Group::make([
                            TextInput::make('nilai_pretest')
                                ->label('Skor Pre-Test')
                                ->numeric()
                                ->minValue(0)
                                ->maxValue(100)
                                ->required()
                                ->placeholder('0.00'),

                            TextInput::make('nilai_posttest')
                                ->label('Skor Post-Test')
                                ->numeric()
                                ->minValue(0)
                                ->maxValue(100)
                                ->required()
                                ->placeholder('0.00'),

                            TextInput::make('nilai_akhir')
                                ->label('Nilai Akhir')
                                ->numeric()
                                ->minValue(0)
                                ->maxValue(100)
                                ->required()
                                ->placeholder('0.00')
                                ->helperText('Rata-rata atau akumulasi nilai evaluasi.'),
                        ])->columns(3),
                    ]),

                // Section 3: Kelulusan & Catatan
                Section::make('Status Kelulusan')
                    ->schema([
                        ToggleButtons::make('status_kelulusan')
                            ->label('Keputusan Akhir')
                            ->options([
                                'Lulus' => 'Lulus',
                                'Tidak Lulus' => 'Tidak Lulus',
                                'Remedial' => 'Remedial',
                            ])
                            ->colors([
                                'Lulus' => 'success',
                                'Tidak Lulus' => 'danger',
                                'Remedial' => 'warning',
                            ])
                            ->icons([
                                'Lulus' => 'heroicon-o-academic-cap',
                                'Tidak Lulus' => 'heroicon-o-x-circle',
                                'Remedial' => 'heroicon-o-arrow-path',
                            ])
                            ->default('Lulus')
                            ->inline(),

                        Textarea::make('catatan')
                            ->label('Catatan Evaluasi')
                            ->rows(3)
                            ->placeholder('Masukkan masukan atau alasan status kelulusan...')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
