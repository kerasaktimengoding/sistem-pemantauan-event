<?php

namespace App\Filament\Resources\Kecamatans\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class KecamatanInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
         
->components([
    // 1. Kode Kecamatan (Dibuat mencolok dengan badge abu-abu tipis)
    TextEntry::make('kode_kecamatan')
        ->label('Kode Wilayah')
        ->badge()
        ->color('gray')
        ->weight('bold'),

    // 2. Nama Kecamatan
    TextEntry::make('nama_kecamatan')
        ->label('Kecamatan')
        ->weight('bold')
        ->color('primary')
        ->icon('heroicon-m-building-office-2')
        ->iconColor('primary'),

    // 3. Informasi Pimpinan (Nama Camat & NIP digabung menggunakan description)
    TextEntry::make('nama_camat')
        ->label('Pejabat Camat')
        ->icon('heroicon-m-user-circle')
        ->iconColor('gray')
        ->weight('medium')
        ->placeholder('Belum Ada Pejabat')
      ->helperText(fn ($record) => $record->nip_camat ? "NIP. {$record->nip_camat}" : "NIP: -"),

    // 4. Kontak Kantor (Telepon & Email digabung menggunakan description)
    TextEntry::make('no_telp')
        ->label('Hubungi Kantor')
        ->placeholder('Tidak Ada Telp')
        ->icon('heroicon-m-phone')
        ->iconColor('success'),
        // ->description(fn ($record) => $record->email_kecamatan ? "Email: {$record->email_kecamatan}" : 'Email: -'),
TextEntry::make('email_kecamatan')
    ->label('Email')
    ->placeholder('-')
    ->icon('heroicon-m-envelope')
    ->iconColor('success'),
    // 5. Alamat Kantor Kecamatan
    TextEntry::make('alamat_kantor')
        ->label('Alamat Kantor')
        ->placeholder('-')
        ->icon('heroicon-m-map-pin')
        ->iconColor('danger')
        ->color('gray'),

    // 6. Luas Wilayah (Format Indonesia + Warna Info)
    TextEntry::make('luas_wilayah')
        ->label('Luas Wilayah')
        ->numeric(decimalPlaces: 2, locale: 'id') // Format: 1.250,50
        ->suffix(' Km²')
        ->placeholder('-')
        ->color('info')
        ->weight('semibold'),

    // 7. Jumlah Penduduk (Otomatis berwarna Merah jika di atas 5.000 jiwa)
    TextEntry::make('jumlah_penduduk')
        ->label('Jumlah Penduduk')
        ->numeric(locale: 'id') // Format ribuan Indonesia: 15.420
        ->suffix(' Jiwa')
        ->placeholder('0')
        ->weight('bold')
        ->color(fn ($state) => $state > 5000 ? 'danger' : 'success'),

    // 8. Keterangan / Catatan Wilayah
    TextEntry::make('keterangan')
        ->label('Catatan Wilayah')
        ->placeholder('-')
        ->color('gray'),

    // 9. Timestamps - Log Sistem Dibuat
    TextEntry::make('created_at')
        ->label('Sistem Dibuat')
        ->dateTime('d M Y, H:i') // Contoh: 04 Jun 2026, 13:45
        ->placeholder('-')
        ->color('gray'),

    // 10. Timestamps - Log Sistem Diperbarui
    TextEntry::make('updated_at')
        ->label('Pembaruan Terakhir')
        ->dateTime('d M Y, H:i')
        ->placeholder('-')
        ->color('gray'),
]);
    }
}
