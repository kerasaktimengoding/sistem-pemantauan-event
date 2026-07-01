<?php

namespace App\Filament\Resources\PerbandinganWilayahs\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Get;

use Filament\Forms\Components\DatePicker;

class PerbandinganWilayahForm
{
    public static function configure(Schema $schema): Schema
    {
       // 🌟 1. LOGIKA UTAMA: PERHITUNGAN HARGA & PERSENTASE DISPARITAS
    $hitungKomparasiPasar = function (callable $get, callable $set) {
        $komoditasId = $get('komoditas_id');
        $periode = $get('periode_rekap');
        $pasar1Id = $get('pasar_id');
        $pasar2Id = $get('pasar_2_id');

        // Pastikan parameter minimal (Komoditas & Periode) sudah diisi
        if (!$komoditasId || !$periode) return;

        try {
            $date = \Carbon\Carbon::parse($periode);

            // --- AMBIL RATA-RATA HARGA PASAR 1 ---
            $harga1 = 0;
            if ($pasar1Id) {
                $query1 = \App\Models\InputHarga::where('komoditas_id', $komoditasId)
                    ->where('pasar_id', $pasar1Id)
                    ->whereMonth('tanggal_input', $date->month)
                    ->whereYear('tanggal_input', $date->year)
                    ->avg('harga'); 
                
                $harga1 = round($query1 ?? 0, 0);
                $set('harga_pasar_1', $harga1);
                $set('pesan_error_1', $harga1 > 0 ? null : '⚠️ Data harga Pasar 1 tidak ditemukan pada periode ini.');
            } else {
                $set('harga_pasar_1', 0);
            }

            // --- AMBIL RATA-RATA HARGA PASAR 2 ---
            $harga2 = 0;
            if ($pasar2Id) {
                $query2 = \App\Models\InputHarga::where('komoditas_id', $komoditasId)
                    ->where('pasar_id', $pasar2Id)
                    ->whereMonth('tanggal_input', $date->month)
                    ->whereYear('tanggal_input', $date->year)
                    ->avg('harga');
                
                $harga2 = round($query2 ?? 0, 0);
                $set('harga_pasar_2', $harga2);
                $set('pesan_error_2', $harga2 > 0 ? null : '⚠️ Data harga Pasar 2 tidak ditemukan pada periode ini.');
            } else {
                $set('harga_pasar_2', 0);
            }

            // --- KALKULASI SELISIH NOMINAL & PERSEN ---
            if ($harga1 > 0 && $harga2 > 0) {
                $selisihNominal = abs($harga1 - $harga2);
                $set('selisih_harga', $selisihNominal);

                // Formula rasio disparitas berbasis nilai dasar tertinggi
                $hargaMaks = max($harga1, $harga2);
                $persen = round(($selisihNominal / $hargaMaks) * 100, 2);
                $set('persen_selisih', $persen . ' %');
            } else {
                $set('selisih_harga', 0);
                $set('persen_selisih', '0 %');
            }

        } catch (\Exception $e) {
            // Log error jika dibutuhkan untuk debugging senior thesis
        }
    };

    return $schema
        ->components([
            // SECTION 1: PARAMETER UTAMA
            Section::make('Informasi Parameter')
                ->schema([
                    Group::make([
                        TextInput::make('kode_perbandingan')
                            ->label('Kode Perbandingan')
                            ->required()
                            ->default(fn() => 'CMP-' . date('d.m.Y') . '-' . strtoupper(\Illuminate\Support\Str::random(5)))
                            ->maxLength(20)
                            ->unique('perbandingan_wilayahs', 'kode_perbandingan', ignoreRecord: true)
                            ->validationMessages([
                                'unique' => 'Kode Perbandingan ini sudah ada',
                                'required' => 'Kode Perbandingan wajib diisi',
                            ])
                            ->placeholder('Contoh: CMP-202403-001'),

                        DatePicker::make('periode_rekap')
                            ->label('Periode Rekap')
                            ->required()
                            ->native(false)
                            ->displayFormat('F Y')
                            ->live()
                            ->default(now())
                            ->afterStateUpdated($hitungKomparasiPasar),

                        Select::make('komoditas_id')
                            ->label('Komoditas')
                            ->relationship('komoditas', 'nama_komoditas')
                            ->required()
                            ->live()
                            ->afterStateUpdated(function (callable $set, callable $get) use ($hitungKomparasiPasar) {
                                // Otomatis mengosongkan pasar lama saat komoditas berubah agar data tetap valid
                                $set('pasar_id', null);
                                $set('pasar_2_id', null);
                                $set('harga_pasar_1', 0);
                                $set('harga_pasar_2', 0);
                                $set('selisih_harga', 0);
                                $set('persen_selisih', '0 %');
                                $set('pesan_error_1', null);
                                $set('pesan_error_2', null);
                                
                                $hitungKomparasiPasar($get, $set);
                            }),
                    ])->columns(3),
                ]),

            // SECTION 2: KOMPARASI HARGA PASAR
            Section::make('Komparasi Harga Antar Pasar Induk')
                ->schema([
                    // --- SELEKSI PASAR 1 ---
                    Group::make([
                        Select::make('pasar_id')
                            ->label('Pasar Pertama (Pasar 1)')
                            ->relationship(
                                name: 'pasar1', 
                                titleAttribute: 'nama_pasar',
                                modifyQueryUsing: function (\Illuminate\Database\Eloquent\Builder $query, callable $get) {
                                    $komoditasId = $get('komoditas_id');
                                    if ($komoditasId) {
                                        // Dropdown Bersyarat: Hanya memuat pasar yang memiliki riwayat input harga komoditas terkait
                                        $pasarIds = \App\Models\InputHarga::where('komoditas_id', $komoditasId)
                                            ->pluck('pasar_id')->toArray();
                                        $query->whereIn('id', $pasarIds);
                                    }
                                }
                            )
                            ->searchable()
                            ->preload()
                            ->required()
                            ->live()
                            ->afterStateUpdated($hitungKomparasiPasar),

                        TextInput::make('harga_pasar_1')
                            ->label('Harga Rata-Rata Pasar 1')
                            ->numeric()
                            ->prefix('Rp')
                            ->readOnly()
                            ->helperText(function (callable $get) {
                                return $get('pesan_error_1') ?? 'Dihitung otomatis berdasarkan database monitoring.';
                            }),
                    ])->columns(2),

                    // --- SELEKSI PASAR 2 ---
                    Group::make([
                        Select::make('pasar_2_id')
                            ->label('Pasar Kedua (Pasar 2)')
                            ->relationship(
                                name: 'pasar2', 
                                titleAttribute: 'nama_pasar',
                                modifyQueryUsing: function (\Illuminate\Database\Eloquent\Builder $query, callable $get) {
                                    $komoditasId = $get('komoditas_id');
                                    if ($komoditasId) {
                                        $pasarIds = \App\Models\InputHarga::where('komoditas_id', $komoditasId)
                                            ->pluck('pasar_id')->toArray();
                                        $query->whereIn('id', $pasarIds);
                                    }
                                }
                            )
                            ->searchable()
                            ->preload()
                            ->required()
                            ->live()
                            ->afterStateUpdated($hitungKomparasiPasar),

                        TextInput::make('harga_pasar_2')
                            ->label('Harga Rata-Rata Pasar 2')
                            ->numeric()
                            ->prefix('Rp')
                            ->readOnly()
                            ->helperText(function (callable $get) {
                                return $get('pesan_error_2') ?? 'Dihitung otomatis berdasarkan database monitoring.';
                            }),
                    ])->columns(2),
                ]),

            // SECTION 3: HASIL DISPARITAS
            Section::make('Hasil Analisis Disparitas')
                ->schema([
                    Group::make([
                        TextInput::make('selisih_harga')
                            ->label('Selisih / Disparitas Harga (Rp)')
                            ->numeric()
                            ->prefix('Rp')
                            ->readOnly()
                            ->helperText('Selisih nominal absolut antara Pasar 1 dan Pasar 2.'),

                        TextInput::make('persen_selisih')
                            ->label('Selisih Persentase (%)')
                            ->readOnly()
                            ->placeholder('0 %')
                            ->helperText('Rasio disparitas harga yang mencerminkan tingkat kestabilan wilayah.'),
                    ])->columns(2),

                    Textarea::make('keterangan')
                        ->label('Analisis Kesimpulan Instansi')
                        ->rows(3)
                        ->placeholder('Contoh: Terjadi disparitas tinggi untuk komoditas cabai akibat kendala distribusi pasokan...')
                        ->columnSpanFull(),
                ]),
        ]);
    }
}
