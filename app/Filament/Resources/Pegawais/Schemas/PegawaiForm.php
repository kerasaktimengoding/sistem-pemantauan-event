<?php

namespace App\Filament\Resources\Pegawais\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class PegawaiForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nip')
                    ->required(),
                TextInput::make('nik')
                    ->required(),
                TextInput::make('nama_pegawai')
                    ->required(),
                TextInput::make('jenis_kelamin')
                    ->required(),
                TextInput::make('tempat_lahir')
                    ->required(),
                DatePicker::make('tanggal_lahir')
                    ->required(),
                Textarea::make('alamat')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('no_hp')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                TextInput::make('jabatan_id')
                    ->required()
                    ->numeric(),
                TextInput::make('wilayah_id')
                    ->required()
                    ->numeric(),
                TextInput::make('status_pegawai')
                    ->required(),
                DatePicker::make('tanggal_masuk')
                    ->required(),
            ]);
    }
}
