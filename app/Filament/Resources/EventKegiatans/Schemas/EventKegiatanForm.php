<?php

namespace App\Filament\Resources\EventKegiatans\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class EventKegiatanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('kode_event')
                    ->required(),
                TextInput::make('nama_event')
                    ->required(),
                TextInput::make('jenis_event')
                    ->required(),
                TextInput::make('wilayah_id')
                    ->required()
                    ->numeric(),
                DatePicker::make('tanggal_mulai')
                    ->required(),
                DatePicker::make('tanggal_selesai')
                    ->required(),
                TextInput::make('lokasi_event')
                    ->required(),
                TextInput::make('status_event')
                    ->required(),
            ]);
    }
}
