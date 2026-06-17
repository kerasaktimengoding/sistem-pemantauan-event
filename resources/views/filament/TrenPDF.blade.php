@extends('filament.pdf.layouts.main')

@section('title', 'Laporan Analisis Tren Harga')

@section('styles')
    /* Tata letak laporan tren harga bahan pokok */
    .table-tren-harga th,
    .table-tren-harga td {
        font-size: 7.5px;
        padding: 6px 5px;
        vertical-align: top;
    }

    .text-mono {
        font-family: monospace;
    }

    .text-right {
        text-align: right;
    }

    .periode-text {
        font-weight: bold;
        color: #4b5563;
    }

    .komoditas-title {
        display: block;
        font-size: 8.5px;
        font-weight: bold;
        color: #111827;
    }

    .sub-info {
        display: block;
        margin-top: 1px;
        font-size: 6.3px;
        color: #4b5563;
        line-height: 1.2;
    }

    .label-posisi {
        display: block;
        margin-bottom: 2px;
        font-size: 6px;
        color: #9ca3af;
    }

    .harga-akhir-style {
        color: #2563eb; /* Primary-600 */
        font-weight: bold;
    }

    /* Penanda Kondisi Tren Perubahan */
    .tren-naik {
        color: #dc2626; /* Merah Peringatan (Danger) */
        font-weight: bold;
    }
    .tren-turun {
        color: #16a34a; /* Hijau Segar (Success) */
        font-weight: bold;
    }
    .tren-stabil {
        color: #0284c7; /* Biru Informasi (Info) */
        font-weight: bold;
    }
    .tren-default {
        color: #6b7280;
        font-weight: bold;
    }

    .no-data {
        padding: 15px !important;
        text-align: center;
        font-style: italic;
        color: #6b7280;
    }
@endsection

@section('content')
    <h1 class="judul-laporan">Laporan Analisis Tren Fluktuasi Harga Komoditas</h1>

    <p class="periode">
        Tanggal Analisis Sistem: {{ now()->translatedFormat('d F Y, H:i') }} WITA
    </p>

    <table class="table-data table-tren-harga">
        <thead>
            <tr>
                <th style="width: 30px;">No.</th>
                <th style="width: 100px;">Periode</th>
                <th style="width: 210px;">Komoditas & Wilayah</th>
                <th style="width: 125px; text-align: right;">Informasi Harga</th>
                <th style="width: 90px; text-align: center;">Kondisi</th>
                <th style="width: 95px; text-align: right;">Selisih (%)</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($trens as $tren)
                @php
                    $arah = trim($tren->arah_tren ?? '');
                    
                    // 1. Tentukan class warna berdasarkan arah tren
                    $kondisiClass = match ($arah) {
                        'Naik'   => 'tren-naik',
                        'Turun'  => 'tren-turun',
                        'Stabil' => 'tren-stabil',
                        default  => 'tren-default',
                    };

                    // 2. Format persentase indikator geometris
                    $persentase = number_format((float)$tren->persentase_perubahan, 2, ',', '.');
                    $prefixPersen = match ($arah) {
                        'Naik'   => "▲ {$persentase}%",
                        'Turun'  => "▼ {$persentase}%",
                        default  => "• {$persentase}%",
                    };
                @endphp
                <tr>
                    {{-- 1. Nomor Urut --}}
                    <td class="text-center" style="color: #6b7280;">
                        {{ $loop->iteration }}
                    </td>

                    {{-- 2. Periode Tren (M Y) --}}
                    <td class="text-center periode-text text-mono">
                        {{ $tren->periode_tren ? \Carbon\Carbon::parse($tren->periode_tren)->translatedFormat('M Y') : '-' }}
                    </td>

                    {{-- 3. Komoditas & Wilayah (Smart Search) --}}
                    <td>
                        <span class="komoditas-title">🛍️ {{ $tren->komoditas->nama_komoditas ?? '-' }}</span>
                        <span class="sub-info">📍 Wilayah: {{ $tren->desa->nama_desa ?? '-' }}</span>
                    </td>

                    {{-- 4. Transformasi Finansial (Harga Awal vs Harga Akhir) --}}
                    <td class="text-right text-mono">
                        <span class="label-posisi" style="text-align: right;">
                            Semula: Rp {{ number_format($tren->harga_awal, 0, ',', '.') }}
                        </span>
                        <span class="harga-akhir-style">
                            Rp {{ number_format($tren->harga_akhir, 0, ',', '.') }}
                        </span>
                    </td>

                    {{-- 5. Visualisasi Kondisi Tren --}}
                    <td class="text-center">
                        <span class="{{ $kondisiClass }}" style="font-size: 8px;">
                            {{ strtoupper($arah ?: '-') }}
                        </span>
                    </td>

                    {{-- 6. Selisih Persentase dengan Indikator Geometris UX --}}
                    <td class="text-right text-mono {{ $kondisiClass }}">
                        {{ $prefixPersen }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="no-data">
                        Data analisis tren perubahan harga pangan belum terekam.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection