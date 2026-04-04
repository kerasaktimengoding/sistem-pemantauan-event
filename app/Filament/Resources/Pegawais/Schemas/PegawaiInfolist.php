<?php

namespace App\Filament\Resources\Pegawais\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PegawaiInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('nip'),
                TextEntry::make('nik'),
                TextEntry::make('nama_pegawai'),
                TextEntry::make('jenis_kelamin'),
                TextEntry::make('tempat_lahir'),
                TextEntry::make('tanggal_lahir')
                    ->date(),
                TextEntry::make('alamat')
                    ->columnSpanFull(),
                TextEntry::make('no_hp'),
                TextEntry::make('email')
                    ->label('Email address'),
                TextEntry::make('jabatan_id')
                    ->numeric(),
                TextEntry::make('wilayah_id')
                    ->numeric(),
                TextEntry::make('status_pegawai'),
                TextEntry::make('tanggal_masuk')
                    ->date(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
