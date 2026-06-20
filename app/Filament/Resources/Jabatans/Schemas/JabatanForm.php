<?php

namespace App\Filament\Resources\Jabatans\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class JabatanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
             
                Section::make('Informasi Jabatan')
                    ->description('Masukan detail identitas jabatan struktural atau fungsional.') 
                    ->schema([
                        Group::make([
                            TextInput::make('kode_jabatan')
                                ->label('Kode Jabatan')
                                ->required() 
                                ->maxLength(20) 
                                ->unique('jabatans', 'kode_jabatan', ignoreRecord: true)
                                ->placeholder('Contoh: JAB-001')
                                ->default(fn() => 'JAB-' . date('d').'.' . date('m').'.' . date('Y') . '-' . strtoupper(Str::random(5)))
                                ->validationMessages([
                                    'unique' => 'Kode jabatan ini sudah terdaftar.',
                                ]),

                            TextInput::make('nama_jabatan')
                                ->label('Nama Jabatan')
                                ->required() 
                                ->maxLength(100) 
                                ->placeholder('Contoh: Kepala Bidang Perdagangan'),
                        ])->columns(2),
                    ]),

                // Section Tugas & Tanggung Jawab
                Section::make('Uraian Tugas')
                    ->description('Jelaskan tugas pokok dan fungsi dari jabatan ini.') 
                    ->schema([
                        Textarea::make('tugas_pokok')
                            ->label('Tugas Pokok')
                            ->required() 
                            ->rows(5)
                            ->placeholder('Sebutkan poin-poin tugas utama jabatan...')
                            ->columnSpanFull(),
                    ]),
                Section::make('Wewenang')
                    ->description('Jelaskan wewenang yang dimiliki oleh pejabat pada jabatan ini.') 
                    ->schema([
                        Textarea::make('wewenang')
                            ->label('Wewenang')
                            ->required() 
                            ->rows(5)
                            ->placeholder('Sebutkan wewenang yang dimiliki oleh pejabat...')
                            ->columnSpanFull(),
                    ]),

                // Section Status
                Section::make('Status Jabatan')
                    ->schema([
                        ToggleButtons::make('status_jabatan')
                            ->label('Status Aktif')
                            ->options([
                                'Aktif' => 'Aktif',
                                'Non-Aktif' => 'Non-Aktif',
                            ]) 
                            ->colors([
                                'Aktif' => 'success',
                                'Non-Aktif' => 'danger',
                            ])
                            ->icons([
                                'Aktif' => 'heroicon-o-check-badge',
                                'Non-Aktif' => 'heroicon-o-x-circle',
                            ])
                            ->default('Aktif') 
                            ->inline(),
                    ]),
            ]);
    }
}
