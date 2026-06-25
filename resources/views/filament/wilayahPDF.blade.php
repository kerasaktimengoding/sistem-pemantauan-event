@extends('filament.pdf.layouts.main')

@section('title', 'Laporan Data Wilayah')

@section('styles')
    /* Ukuran tabel khusus laporan data wilayah agar pas landscape A4 */
    .table-wilayah th,
    .table-wilayah td {
        font-size: 7px;
        padding: 4px 3px;
    }

    .kode-wilayah {
        display: inline-block;
        padding: 3px 5px;
        background-color: #eff6ff;
        border-radius: 4px;
        font-weight: bold;
        color: #1d4ed8;
    }

    .nama-kecamatan {
        font-weight: bold;
        color: #374151;
    }

    .sub-text {
        display: block;
        margin-top: 2px;
        font-size: 6.5px;
        color: #6b7280;
    }

    .luas-wilayah {
        font-weight: normal;
        color: #111827;
    }

    .populasi-wilayah {
        color: #0284c7;
        font-weight: bold;
    }

    /* Styling dinamis Potensi Ekonomi berbasis teks */
    .potensi-pertanian, .potensi-perkebunan {
        color: #16a34a;
        font-weight: bold;
    }
    .potensi-perdagangan, .potensi-jasa {
        color: #d97706;
        font-weight: bold;
    }
    .potensi-industri, .potensi-pariwisata {
        color: #2563eb;
        font-weight: bold;
    }
    .potensi-maritim, .potensi-perikanan {
        color: #091e3a;
        font-weight: bold;
    }
    .potensi-default {
        color: #6b7280;
    }

    .kode-pos-badge {
        display: inline-block;
        padding: 2px 4px;
        background-color: #f3f4f6;
        color: #4b5563;
        border-radius: 3px;
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
    <h1 class="judul-laporan">Laporan Data Wilayah</h1>

    <p class="periode">
        Periode: {{ now()->format('Y') }}
    </p>

    <table class="table-data table-wilayah">
        <thead>
            <tr>
                <th style="width: 25px;">No.</th>
                <th style="width: 70px;">Kode Wilayah</th>
                <th style="width: 140px;">Nama Wilayah (Kecamatan / Desa)</th>
                <th style="width: 75px;">Luas Wilayah</th>
                <th style="width: 75px;">Populasi</th>
                <th style="width: 95px;">Potensi Ekonomi</th>
                <th style="width: 55px;">Kode Pos</th>
                <th style="width: 135px;">Keterangan Geografis</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($wilayahs as $wilayah)
                @php
                    // Normalisasi string potensi untuk mapping class CSS
                    $potensiText = strtolower(trim($wilayah->potensi_ekonomi ?? ''));
                    $potensiClass = match ($potensiText) {
                        'pertanian', 'perkebunan' => 'potensi-pertanian',
                        'perdagangan', 'jasa' => 'potensi-perdagangan',
                        'industri', 'pariwisata' => 'potensi-industri',
                        'maritim', 'perikanan' => 'potensi-maritim',
                        default => 'potensi-default',
                    };
                @endphp
                <tr>
                    {{-- 1. Nomor urut --}}
                    <td class="text-center">
                        {{ $loop->iteration }}
                    </td>

                    {{-- 2. Kode Wilayah --}}
                    <td class="text-center">
                        <span class="kode-wilayah">
                            {{ $wilayah->kode_wilayah ?? '-' }}
                        </span>
                    </td>

                    {{-- 3. Nama Wilayah & Relasi Desa --}}
                    <td>
                        <span class="nama-kecamatan">
                            {{ $wilayah->kecamatan->nama_kecamatan ?? '-' }}
                        </span>
                        <span class="sub-text">
                            {{ $wilayah->desa->jenis === 'kelurahan' ? 'Kel. ' . $wilayah->desa->nama_desa : 'Desa ' . $wilayah->desa->nama_desa }}
                        </span>
                    </td>

                    {{-- 4. Luas Wilayah --}}
                    <td class="text-right luas-wilayah">
                        {{ number_format(
                            (float) ($wilayah->luas_wilayah ?? 0),
                            2,
                            ',',
                            '.'
                        ) }} km²
                    </td>

                    {{-- 5. Populasi --}}
                    <td class="text-right populasi-wilayah">
                        {{ number_format(
                            (int) ($wilayah->jumlah_penduduk ?? 0),
                            0,
                            ',',
                            '.'
                        ) }} Jiwa
                    </td>

                    {{-- 6. Potensi Ekonomi --}}
                    <td class="text-center">
                        <span class="{{ $potensiClass }}">
                            {{ $wilayah->potensi_ekonomi ?? '-' }}
                        </span>
                    </td>

                    {{-- 7. Kode Pos --}}
                    <td class="text-center">
                        <span class="kode-pos-badge">
                            {{ $wilayah->kode_pos ?? '-' }}
                        </span>
                    </td>

                    {{-- 8. Keterangan Geografis --}}
                    <td>
                        {{ $wilayah->keterangan_geografis ?? '-' }}
                        @if($wilayah->batas_utara || $wilayah->batas_selatan)
                            <span class="sub-text">
                                (U: {{ $wilayah->batas_utara ?? '-' }} | S: {{ $wilayah->batas_selatan ?? '-' }})
                            </span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="no-data">
                        Data wilayah belum tersedia.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection