<?php

namespace App\Filament\Resources\InputHargas\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class InputHargaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Referensi Data Monitoring')
                    ->description('Pilih entitas terkait untuk pendataan harga.')
                    ->schema([
                        Group::make([
                            TextInput::make('kode_input_harga')
                                ->label('Kode Transaksi')
                                ->required()
                                ->maxLength(20)
                                ->unique('input_hargas', 'kode_input_harga', ignoreRecord: true)
                                ->default(fn() => 'TRK-' . date('d') . '.' . date('m') . '.' . date('Y') . '-' . strtoupper(Str::random(5)))
                                ->validationMessages([
                                    'unique' => 'Kode Input Harga ini sudah ada',
                                ])
                                ->disabled()
                                ->dehydrated()
                                ->placeholder('Contoh: TRK-20240301-01'),

                            DatePicker::make('tanggal_input')
                                ->label('Tanggal Pengambilan Data')
                                ->required()
                                ->default(now())
                                ->native(false),
                        ])->columns(2),

                        Group::make([
                            Select::make('komoditas_id')
                                ->label('Komoditas / Barang')
                                ->relationship('komoditas', 'nama_komoditas')
                                ->searchable()
                                ->preload()
                                ->required(),

                            // Untuk Pedagang
                            Select::make('pedagang_id')
                                ->label('Responden / Pedagang')
                                ->relationship('pedagang', 'nama_pedagang')
                                ->searchable()
                                ->preload()
                                ->required()
                                // 🌟 Tambahkan ini agar pedagang dengan nama sama bisa dibedakan lewat kode/NIK
                                ->getOptionLabelFromRecordUsing(fn($record) => "{$record->nama_pedagang} - {$record->kode_pedagang}"),
                        ])->columns(2),

                        // --- SECTION PULL DATA OTOMATIS BERDASARKAN PASAR ---
                        Group::make([
                            Select::make('pasar_id')
                                ->label('Lokasi Pasar')
                                ->relationship('pasar', 'nama_pasar')
                                ->searchable()
                                ->preload()
                                ->required()
                                // 🌟 Tambahkan ini untuk menampilkan kode pasar agar tidak bingung
                                ->getOptionLabelFromRecordUsing(fn($record) => "{$record->nama_pasar} ({$record->kode_pasar})")
                                ->live()
                                ->afterStateUpdated(function ($state, callable $set) {
                                    $pasar = \App\Models\Pasar::find($state);
                                    $set('desa_id', $pasar?->desa_id);
                                    $set('kecamatan_id', $pasar?->kecamatan_id);
                                }),
                            Select::make('desa_id')
                                ->label('Desa / Kelurahan')
                                ->relationship('desa', 'nama_desa')
                                ->searchable()
                                ->preload()
                                ->required()
                                ->disabled() // Di-disable agar murni mengikuti pasar induknya
                                ->dehydrated() // Tetap dikirim ke database saat simpan
                                ->helperText('Otomatis mengikuti lokasi pasar yang dipilih.'),

                            Select::make('kecamatan_id')
                                ->label('Kecamatan Induk')
                                ->relationship('kecamatan', 'nama_kecamatan')
                                ->searchable()
                                ->preload()
                                ->required()
                                ->disabled()
                                ->dehydrated()
                                ->helperText('Otomatis mengikuti lokasi pasar yang dipilih.'),
                        ])->columns(3), // Diubah ke 3 kolom agar Pasar, Desa, dan Kecamatan sejajar rapi
                    ]),
                // Section 2: Detail Harga & Petugas
                Section::make('Detail Harga & Sumber Data')
                    ->schema([
                        Group::make([
                            TextInput::make('harga')
                                ->label('Nominal Harga')
                                ->numeric()
                                ->prefix('Rp')
                                ->required()
                                ->placeholder('0.00'),

                            Select::make('pegawai_id')
                                ->label('Petugas Enumerator')
                                ->relationship('pegawai', 'nama_pegawai')
                                ->searchable()
                                ->preload()
                                ->required(),
                        ])->columns(2),

                        TextInput::make('sumber_data')
                            ->label('Sumber Data')
                            ->required()
                            ->placeholder('Contoh: Survei Lapangan / Wawancara')
                            ->maxLength(50),

                        Textarea::make('keterangan')
                            ->label('Catatan Tambahan')
                            ->rows(3)
                            ->placeholder('Masukkan keterangan jika ada perubahan harga yang signifikan...')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
