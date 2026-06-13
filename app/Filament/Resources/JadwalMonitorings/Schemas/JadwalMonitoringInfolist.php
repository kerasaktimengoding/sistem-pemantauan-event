<?php

namespace App\Filament\Resources\JadwalMonitorings\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\FontFamily;
use Filament\Schemas\Components\Section;

class JadwalMonitoringInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Jadwal Monitoring')
                    ->columns(2)
                    ->schema([

                        TextEntry::make('kode_jadwal')
                            ->label('Kode Tugas')
                            ->badge()
                            ->color('gray')
                            ->fontFamily(FontFamily::Mono)
                            ->weight(FontWeight::Bold)
                            ->copyable()
                            ->copyMessage('Kode tugas berhasil disalin')
                            ->placeholder('-'),

                        TextEntry::make('status_monitoring')
                            ->label('Status Kemajuan')
                            ->badge()
                            ->weight(FontWeight::Bold)
                            ->color(fn($state): string => match (strtolower(trim((string) $state))) {
                                'selesai', 'success', 'approved' => 'success',
                                'proses', 'ongoing', 'on progress' => 'warning',
                                'pending', 'draft' => 'gray',
                                'batal', 'rejected', 'failed' => 'danger',
                                default => 'primary',
                            })
                            ->icon(fn($state): string => match (strtolower(trim((string) $state))) {
                                'selesai', 'success', 'approved' => 'heroicon-m-check-circle',
                                'proses', 'ongoing', 'on progress' => 'heroicon-m-arrow-path',
                                'pending', 'draft' => 'heroicon-m-clock',
                                'batal', 'rejected', 'failed' => 'heroicon-m-x-circle',
                                default => 'heroicon-m-question-mark-circle',
                            })
                            ->formatStateUsing(fn($state) => strtoupper((string) $state))
                            ->placeholder('-'),

                        TextEntry::make('tanggal_rencana')
                            ->label('Rencana Pelaksanaan')
                            ->date('d M Y')
                            ->weight(FontWeight::Bold)
                            ->color('primary')
                            ->icon('heroicon-m-calendar-days')
                            ->iconColor('primary')
                            ->helperText(
                                fn($record) => $record->nomor_surat_tugas
                                ? "📄 No. Surat: {$record->nomor_surat_tugas}"
                                : '⚠️ Surat Tugas: Belum Diterbitkan'
                            )
                            ->columnSpanFull()
                            ->placeholder('-'),

                        TextEntry::make('pasar.nama_pasar')
                            ->label('Lokasi Pasar Target')
                            ->weight(FontWeight::SemiBold)
                            ->color('gray')
                            ->icon('heroicon-m-map-pin')
                            ->iconColor('danger')
                            ->helperText(fn($record) => '🗺️ Wilayah: ' . ($record->pasar?->kecamatan?->nama_kecamatan ?? '-'))
                            ->columnSpanFull()
                            ->placeholder('Pasar Tidak Terdaftar'),

                        TextEntry::make('pegawai.nama_pegawai')
                            ->label('Petugas Lapangan')
                            ->weight(FontWeight::SemiBold)
                            ->color('gray')
                            ->icon('heroicon-m-user')
                            ->iconColor('info')
                            ->helperText(
                                fn($record) => $record->pegawai?->nip_pegawai
                                ? "🪪 NIP. {$record->pegawai->nip_pegawai}"
                                : '🪪 NIP: -'
                            )
                            ->columnSpanFull()
                            ->placeholder('Belum Ditunjuk'),

                        TextEntry::make('catatan_petugas')
                            ->label('Hasil Temuan / Catatan')
                            ->limit(100)
                            ->tooltip(fn($state) => $state)
                            ->color('gray')
                            ->columnSpanFull()
                            ->placeholder('✨ Tidak ada catatan khusus lapangan'),

                        TextEntry::make('created_at')
                            ->label('Waktu Input')
                            ->dateTime('d M Y, H:i')
                            ->icon('heroicon-m-calendar')
                            ->iconColor('gray')
                            ->color('gray')
                            ->placeholder('-'),

                        TextEntry::make('updated_at')
                            ->label('Pembaruan Terakhir')
                            ->dateTime('d M Y, H:i')
                            ->icon('heroicon-m-clock')
                            ->iconColor('gray')
                            ->color('gray')
                            ->placeholder('-'),

                    ]),
            ]);
    }
}
