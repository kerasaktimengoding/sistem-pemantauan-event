use Illuminate\Support\Str;
@extends('filament.pdf.layouts.main')

@section('title', 'Laporan Komparasi Disparitas Wilayah')

@section('styles')
    /* Tata letak khusus laporan komparasi pasar/wilayah */
    .table-komparasi th,
    .table-komparasi td {
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

    .kode-box {
        font-weight: bold;
        color: #111827;
    }

    .titik-utama {
        display: block;
        font-size: 8.5px;
        font-weight: bold;
        color: #2563eb; /* Biru primary-600 */
    }

    .sub-info {
        display: block;
        margin-top: 2px;
        font-size: 6.5px;
        color: #4b5563;
        line-height: 1.2;
    }

    /* Pewarnaan Metrik Finansial Berdasarkan Tingkat Disparitas */
    .disparitas-tinggi {
        color: #dc2626; /* Merah Rawan (> 5000) */
        font-weight: bold;
    }
    .disparitas-sedang {
        color: #d97706; /* Amber/Kuning Waspada (> 2000) */
        font-weight: bold;
    }
    .disparitas-normal {
        color: #16a34a; /* Hijau Aman/Stabil */
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
    <h1 class="judul-laporan">Laporan Analisis Perbandingan Disparitas Harga Antar Wilayah</h1>

    <p class="periode">
        Sektor Pengawasan: Dinas Koperasi, Usaha Mikro, Perindustrian dan Perdagangan Kabupaten Banjar
    </p>

    <table class="table-data table-komparasi">
        <thead>
            <tr>
                <th style="width: 30px;">No.</th>
                <th style="width: 120px;">Cakupan Data</th>
                <th style="width: 225px;">Titik Komparasi Wilayah</th>
                <th style="width: 110px; text-align: right;">Selisih Harga</th>
                <th style="width: 155px; text-align: center;">Analisis Disparitas (Keterangan)</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($perbandingans as $data)
                @php
                    // 1. Ambil state nilai selisih harga
                    $selisih = (float) $data->selisih_harga;

                    // 2. Tentukan class warna dinamis berdasarkan skala tingkat keparahan
                    $disparitasClass = match (true) {
                        $selisih > 5000 => 'disparitas-tinggi',
                        $selisih > 2000 => 'disparitas-sedang',
                        default         => 'disparitas-normal',
                    };

                    // 3. Label text untuk kondisi cetak
                    $analisisLabel = match (true) {
                        $selisih > 5000 => ' DISPARITAS TINGGI',
                        $selisih > 2000 => ' DISPARITAS SEDANG',
                        default         => ' STABIL / NORMAL',
                    };
                @endphp
                <tr>
                    {{-- 1. Nomor Urut --}}
                    <td class="text-center" style="color: #6b7280;">
                        {{ $loop->iteration }}
                    </td>

                    {{-- 2. Kode Transaksi & Nama Komoditas --}}
                    <td>
                        <span class="kode-box text-mono">{{ $data->kode_perbandingan ?? '-' }}</span>
                        <span class="sub-info"> {{ $data->komoditas->nama_komoditas ?? '-' }}</span>
                    </td>

                    {{-- 3. Penyatuan Dua Wilayah Komparasi (Desa 1 vs Desa 2) --}}
                    <td>
                        <span class="titik-utama">{{ $data->desa1->nama_desa ?? '-' }}</span>
                        <span class="sub-info" style="font-weight: 500;">
                             Dibandingkan dengan: {{ $data->desa2->nama_desa ?? '-' }}
                        </span>
                    </td>

                    {{-- 4. Metrik Finansial Selisih Harga (Sejajar Kanan) --}}
                    <td class="text-right text-mono {{ $disparitasClass }}">
                        Rp {{ number_format($selisih, 0, ',', '.') }}
                    </td>

                    {{-- 5. Keterangan Analisis Disparitas --}}
                    <td>
                        <span class="{{ $disparitasClass }}" style="display: block; font-size: 7px; margin-bottom: 2px;">
                            {{ $analisisLabel }}
                        </span>
                        <span class="sub-info" style="color: #4b5563;">
                            {{ $data->keterangan ? Str::limit($data->keterangan, 55, '...') : 'Tidak ada catatan analisis.' }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="no-data">
                        Data analisis komparasi harga antar wilayah belum tersedia.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection