@extends('filament.pdf.layouts.main')

@section('title', 'Laporan Rekapitulasi Harga Komoditas')

@section('styles')
    /* Desain khusus tabel rekapitulasi harga bahan pokok */
    .table-rekap-harga th,
    .table-rekap-harga td {
        font-size: 7.5px;
        padding: 5px 4px;
        vertical-align: top;
    }

    .text-mono {
        font-family: monospace;
    }

    .text-right {
        text-align: right;
    }

    .periode-tag {
        font-weight: bold;
        color: #2563eb; /* Biru utama penanda waktu dokumen */
    }

    .komoditas-nama {
        font-weight: 600;
        color: #374151;
    }

    .nama-wilayah {
        display: block;
        font-weight: 500;
        color: #111827;
    }

    .sub-info {
        display: block;
        margin-top: 1px;
        font-size: 6.3px;
        color: #4b5563;
        line-height: 1.2;
    }

    .label-batas {
        display: block;
        margin-bottom: 2px;
        font-size: 6px;
        color: #9ca3af;
    }

    /* Pewarnaan Indikator Batas Finansial */
    .harga-rata {
        color: #2563eb;
        font-weight: bold;
    }
    .harga-max {
        color: #dc2626; /* Merah untuk harga tertinggi/batas atas */
    }
    .harga-min {
        color: #16a34a; /* Hijau untuk harga terendah/batas bawah */
    }

    .no-data {
        padding: 15px !important;
        text-align: center;
        font-style: italic;
        color: #6b7280;
    }
@endsection

@section('content')
    <h1 class="judul-laporan">Laporan Rekapitulasi Harga Bahan Pokok & Komoditas</h1>

    <p class="periode">
        Tanggal Cetak Laporan: {{ now()->translatedFormat('d F Y') }}
    </p>

    <table class="table-data table-rekap-harga">
        <thead>
            <tr>
                <th style="width: 25px;">No.</th>
                <th style="width: 105px;">Periode Rekap</th>
                <th style="width: 125px;">Komoditas</th>
                <th style="width: 145px;">Cakupan Wilayah</th>
                <th style="width: 100px; text-align: right;">Harga Rata-Rata</th>
                <th style="width: 85px; text-align: right;">Tertinggi</th>
                <th style="width: 85px; text-align: right;">Terendah</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($rekaps as $rekap)
                @php
                    // 1. Ambil data nilai finansial
                    $avg = (float) $rekap->harga_rata_rata;
                    $max = (float) $rekap->harga_maksimum;
                    $min = (float) $rekap->harga_minimum;
                    
                    // 2. Kalkulasi nilai spread / selisih harga pasar
                    $spread = $max - $min;
                @endphp
                <tr>
                    {{-- 1. Nomor Urut --}}
                    <td class="text-center" style="color: #6b7280;">
                        {{ $loop->iteration }}
                    </td>

                    {{-- 2. Periode Rekapitulasi (Format: Bulan Tahun) --}}
                    <td>
                        <span class="periode-tag">
                            📅 {{ $rekap->periode_rekap ? \Carbon\Carbon::parse($rekap->periode_rekap)->translatedFormat('F Y') : '-' }}
                        </span>
                    </td>

                    {{-- 3. Nama Komoditas --}}
                    <td>
                        <span class="komoditas-nama">🛍️ {{ $rekap->komoditas->nama_komoditas ?? '-' }}</span>
                    </td>

                    {{-- 4. Hierarki Wilayah (Kecamatan & Desa) --}}
                    <td>
                        <span class="nama-wilayah">🏢 {{ $rekap->kecamatan->nama_kecamatan ?? '-' }}</span>
                        <span class="sub-info">
                            {{ $rekap->desa ? '📍 Desa: ' . $rekap->desa->nama_desa : '📍 Seluruh Kecamatan' }}
                        </span>
                    </td>

                    {{-- 5. Statistik Harga Rata-Rata & Selisih Spread --}}
                    <td class="text-right text-mono">
                        <span class="harga-rata">Rp {{ number_format($avg, 0, ',', '.') }}</span>
                        <span class="sub-info" style="color: #6b7280;">
                            Selisih: Rp {{ number_format($spread, 0, ',', '.') }}
                        </span>
                    </td>

                    {{-- 6. Batas Harga Atas (Maksimum) --}}
                    <td class="text-right text-mono harga-max">
                        <span class="label-batas" style="text-align: right;">Batas Atas</span>
                        <span>Rp {{ number_format($max, 0, ',', '.') }}</span>
                    </td>

                    {{-- 7. Batas Harga Bawah (Minimum) --}}
                    <td class="text-right text-mono harga-min">
                        <span class="label-batas" style="text-align: right;">Batas Bawah</span>
                        <span>Rp {{ number_format($min, 0, ',', '.') }}</span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="no-data">
                        Data rekapitulasi fluktuasi harga komoditas belum tersedia.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection