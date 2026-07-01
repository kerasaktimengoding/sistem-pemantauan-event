<?php

namespace App\Filament\Resources\RekapHargas\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;
use Filament\Forms\Components\ToggleButtons;

class RekapHargaForm
{

    public static function configure(Schema $schema): Schema
    {
        $hitungOtomatis = function (callable $get, callable $set) {
            $komoditasId = $get('inputHarga.komoditas_id') ?? $get('komoditas_id');
            $kecamatanId = $get('inputHarga.kecamatan_id') ?? $get('kecamatan_id');
            $periode = $get('periode_rekap');

            if ($komoditasId && $kecamatanId && $periode) {
                try {
                    $date = \Carbon\Carbon::parse($periode);

                    // --- 1. AMBIL DATA BULAN INI ---
                    $stats = \App\Models\InputHarga::where('komoditas_id', $komoditasId)
                        ->where('kecamatan_id', $kecamatanId)
                        ->whereMonth('tanggal_input', $date->month)
                        ->whereYear('tanggal_input', $date->year)
                        ->selectRaw('AVG(harga) as rata_rata, MAX(harga) as maksimum, MIN(harga) as minimum')
                        ->first();

                    $rataRataBulanIni = round($stats->rata_rata ?? 0, 0);

                    $set('harga_rata_rata', $rataRataBulanIni);
                    $set('harga_maksimum', $stats->maksimum ?? 0);
                    $set('harga_minimum', $stats->minimum ?? 0);

                    // --- 2. AMBIL DATA BULAN SEBELUMNYA UNTUK TREN ---
                    $prevDate = $date->copy()->subMonth(); // Mundur 1 bulan
                    $prevStats = \App\Models\InputHarga::where('komoditas_id', $komoditasId)
                        ->where('kecamatan_id', $kecamatanId)
                        ->whereMonth('tanggal_input', $prevDate->month)
                        ->whereYear('tanggal_input', $prevDate->year)
                        ->selectRaw('AVG(harga) as rata_rata')
                        ->first();

                    $rataRataBulanLalu = round($prevStats->rata_rata ?? 0, 0);

                    // --- 3. TENTUKAN ARAH TREN OTOMATIS ---
                    if ($rataRataBulanLalu > 0) {
                        if ($rataRataBulanIni > $rataRataBulanLalu) {
                            $set('arah_tren', 'Naik');
                        } elseif ($rataRataBulanIni < $rataRataBulanLalu) {
                            $set('arah_tren', 'Turun');
                        } else {
                            $set('arah_tren', 'Stabil');
                        }
                    } else {
                        // Jika bulan lalu tidak ada data, anggap stabil sebagai default
                        $set('arah_tren', 'Stabil');
                    }

                } catch (\Exception $e) {
                    // Error handling
                }
            } else {
                // Reset jika parameter tidak lengkap
                $set('harga_rata_rata', null);
                $set('harga_maksimum', null);
                $set('harga_minimum', null);
                $set('arah_tren', 'Stabil');
            }
        };
        return $schema
            ->components([
                Section::make('Informasi Rekapitulasi')
                    ->description('Tentukan parameter wilayah, komoditas, dan periode yang akan direkap.')
                    ->schema([
                        Group::make([
                            TextInput::make('kode_rekap_harga')
                                ->label('Kode Rekap')
                                ->required()
                                ->maxLength(20)
                                ->unique('rekap_hargas', 'kode_rekap_harga', ignoreRecord: true)
                                ->validationMessages([
                                    'unique' => 'Kode Rekap Harga ini sudah ada',
                                    'required' => 'Kode Rekap Harga wajib diisi',
                                ])
                                ->default(fn() => 'RHP-' . date('d') . '.' . date('m') . '.' . date('Y') . '-' . strtoupper(Str::random(5)))
                                ->placeholder('Contoh: RHP-202403-01'),

                            DatePicker::make('periode_rekap')
                                ->label('Periode Rekap')
                                ->required()
                                ->native(false)
                                ->displayFormat('F Y') // Menampilkan Nama Bulan dan Tahun
                                ->live()
                                ->afterStateUpdated(function (callable $get, callable $set) {
                                    $komoditasId = $get('inputHarga.komoditas_id') ?? $get('komoditas_id');
                                    $kecamatanId = $get('inputHarga.kecamatan_id') ?? $get('kecamatan_id');
                                    $periode = $get('periode_rekap');

                                    if ($komoditasId && $kecamatanId && $periode) {
                                        try {
                                            $date = \Carbon\Carbon::parse($periode);
                                            $stats = \App\Models\InputHarga::where('komoditas_id', $komoditasId)
                                                ->where('kecamatan_id', $kecamatanId)
                                                ->whereMonth('tanggal_input', $date->month)
                                                ->whereYear('tanggal_input', $date->year)
                                                ->selectRaw('AVG(harga) as rata_rata, MAX(harga) as maksimum, MIN(harga) as minimum')
                                                ->first();

                                            $set('harga_rata_rata', round($stats->rata_rata ?? 0, 2));
                                            $set('harga_maksimum', $stats->maksimum ?? 0);
                                            $set('harga_minimum', $stats->minimum ?? 0);
                                        } catch (\Exception $e) {
                                        }
                                    }
                                }),
                        ])->columns(2),

                        Select::make('inputHarga.komoditas_id')
                            ->label('Komoditas')
                            ->relationship('inputHarga.komoditas', 'nama_komoditas')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->live()
                            ->afterStateUpdated($hitungOtomatis),

                        Select::make('inputHarga.desa_id')
                            ->label('Pilih Desa / Kelurahan')
                            ->relationship(
                                name: 'inputHarga.desa',
                                titleAttribute: 'nama_desa',
                                modifyQueryUsing: function (\Illuminate\Database\Eloquent\Builder $query, callable $get) {
                                    // Ambil ID Komoditas yang sedang aktif di form
                                    $komoditasId = $get('inputHarga.komoditas_id') ?? $get('komoditas_id');

                                    if ($komoditasId) {
                                        // Cari desa mana saja yang pernah menginput harga untuk komoditas ini
                                        $desaIds = \App\Models\InputHarga::where('komoditas_id', $komoditasId)
                                            ->pluck('desa_id')
                                            ->toArray();

                                        // Saring pilihan dropdown hanya pada desa-desa tersebut
                                        $query->whereIn('id', $desaIds);
                                    }
                                }
                            )
                            ->searchable()
                            ->preload()
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set, callable $get) use ($hitungOtomatis) {
                                $desa = \App\Models\Desa::find($state);
                                if ($desa) {
                                    $set('inputHarga.kecamatan_id', $desa->kecamatan_id);
                                    $set('kecamatan_id', $desa->kecamatan_id);
                                } else {
                                    $set('inputHarga.kecamatan_id', null);
                                    $set('kecamatan_id', null);
                                }

                                // Jalankan perhitungan setelah kecamatan ter-set otomatis
                                $hitungOtomatis($get, $set);
                            }),
                        // 2. Kecamatan Terisi Otomatis
                        Select::make('inputHarga.kecamatan_id')
                            ->label('Kecamatan Induk')
                            ->relationship('inputHarga.kecamatan', 'nama_kecamatan')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->disabled() // Dimatikan agar tidak diubah manual (sesuai permintaan)
                            ->dehydrated() // Tetap mengirim data ke database saat simpan
                            ->helperText('Otomatis terisi berdasarkan desa yang dipilih.'),
                    ])->columns(2),

                // Section 2: Hasil Analisis Statistik Harga
                Section::make('Hasil Analisis Harga')
                    ->description('Ringkasan statistik harga berdasarkan data yang masuk pada periode ini.')
                    ->schema([
                        Group::make([
                            TextInput::make('harga_rata_rata')
                                ->label('Harga Rata-Rata')
                                ->numeric()
                                ->prefix('Rp')
                                ->required()
                                ->readOnly()
                                ->placeholder('0.00'),

                            TextInput::make('harga_maksimum')
                                ->label('Harga Tertinggi')
                                ->numeric()
                                ->prefix('Rp')
                                ->required()

                                ->readOnly()
                                ->placeholder('0.00'),

                            TextInput::make('harga_minimum')
                                ->label('Harga Terendah')
                                ->numeric()
                                ->prefix('Rp')
                                ->required()
                                ->readOnly()
                                ->placeholder('0.00'),
                        ])->columns(3),

                        Section::make('Kesimpulan Analisis')
                            ->schema([
                                ToggleButtons::make('arah_tren')
                                    ->label('Arah Pergerakan Harga')
                                    ->options([
                                        'Naik' => 'Naik',
                                        'Turun' => 'Turun',
                                        'Stabil' => 'Stabil',
                                    ])
                                    ->colors([
                                        'Naik' => 'danger',   // Merah biasanya untuk kenaikan harga (inflasi)
                                        'Turun' => 'success', // Hijau untuk penurunan harga
                                        'Stabil' => 'info',   // Biru untuk stabil
                                    ])
                                    ->icons([
                                        'Naik' => 'heroicon-o-arrow-trending-up',
                                        'Turun' => 'heroicon-o-arrow-trending-down',
                                        'Stabil' => 'heroicon-o-arrow-path',
                                    ])
                                    ->default('Stabil')
                                    ->inline()
                                    ->disabled()      // KUNCI: Kunci tombol agar murni dari kalkulasi sistem
                                    ->dehydrated()    // KUNCI: Tetap kirim value ke database saat tombol Simpan ditekan
                                    ->columnSpanFull(),
                            ]),
                    ]),
            ]);
    }
}
