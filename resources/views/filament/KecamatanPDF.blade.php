```blade
@extends('filament.pdf.layouts.main')

@section('title', 'Laporan Data Kecamatan')

@section('styles')
    /* Ukuran tabel khusus laporan kecamatan */
    .table-kecamatan th,
    .table-kecamatan td {
        font-size: 7px;
        padding: 4px 3px;
    }

    .kode-wilayah {
        display: inline-block;
        padding: 3px 5px;
        background-color: #e5e7eb;
        border-radius: 4px;
        font-weight: bold;
        color: #374151;
    }

    .nama-kecamatan {
        font-weight: bold;
        color: #1d4ed8;
    }

    .sub-text {
        display: block;
        margin-top: 2px;
        font-size: 6.5px;
        color: #6b7280;
    }

    .penduduk-padat {
        color: #dc2626;
        font-weight: bold;
    }

    .penduduk-normal {
        color: #16a34a;
        font-weight: bold;
    }

    .luas-wilayah {
        color: #0284c7;
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
    <h1 class="judul-laporan">Laporan Data Kecamatan</h1>

    <p class="periode">
        Periode: {{ now()->format('Y') }}
    </p>

    <table class="table-data table-kecamatan">
        <thead>
            <tr>
                <th style="width: 25px;">No.</th>

                <th style="width: 65px;">
                    Kode Wilayah
                </th>

                <th style="width: 90px;">
                    Kecamatan
                </th>

                <th style="width: 95px;">
                    Pejabat Camat
                </th>

                <th style="width: 90px;">
                    Hubungi Kantor
                </th>

                <th style="width: 120px;">
                    Alamat Kantor
                </th>

                <th style="width: 65px;">
                    Luas Wilayah
                </th>

                <th style="width: 70px;">
                    Jumlah Penduduk
                </th>

                <th style="width: 90px;">
                    Catatan Wilayah
                </th>

                <th style="width: 75px;">
                    Sistem Dibuat
                </th>
            </tr>
        </thead>

        <tbody>
            @forelse ($kecamatans as $kecamatan)
                <tr>
                    {{-- 1. Nomor urut --}}
                    <td class="text-center">
                        {{ $loop->iteration }}
                    </td>

                    {{-- 2. Kode kecamatan --}}
                    <td class="text-center">
                        <span class="kode-wilayah">
                            {{ $kecamatan->kode_kecamatan ?? '-' }}
                        </span>
                    </td>

                    {{-- 3. Nama kecamatan --}}
                    <td>
                        <span class="nama-kecamatan">
                            {{ $kecamatan->nama_kecamatan ?? '-' }}
                        </span>
                    </td>

                    {{-- 4. Nama camat dan NIP --}}
                    <td>
                        <strong>
                            {{ $kecamatan->nama_camat ?? 'Belum Ada Pejabat' }}
                        </strong>

                        <span class="sub-text">
                            NIP. {{ $kecamatan->nip_camat ?? '-' }}
                        </span>
                    </td>

                    {{-- 5. Nomor telepon dan email --}}
                    <td>
                        {{ $kecamatan->no_telp ?? 'Tidak Ada Telp' }}

                        <span class="sub-text">
                            {{ $kecamatan->email_kecamatan ?? 'Email: -' }}
                        </span>
                    </td>

                    {{-- 6. Alamat kantor --}}
                    <td>
                        {{ $kecamatan->alamat_kantor ?? '-' }}
                    </td>

                    {{-- 7. Luas wilayah --}}
                    <td class="text-right luas-wilayah">
                        {{ number_format(
                            (float) ($kecamatan->luas_wilayah ?? 0),
                            2,
                            ',',
                            '.'
                        ) }}
                        Km²
                    </td>

                    {{-- 8. Jumlah penduduk --}}
                    <td class="text-right">
                        <span class="{{
                            ($kecamatan->jumlah_penduduk ?? 0) > 5000
                                ? 'penduduk-padat'
                                : 'penduduk-normal'
                        }}">
                            {{ number_format(
                                (int) ($kecamatan->jumlah_penduduk ?? 0),
                                0,
                                ',',
                                '.'
                            ) }}
                            Jiwa
                        </span>
                    </td>

                    {{-- 9. Keterangan --}}
                    <td>
                        {{ $kecamatan->keterangan ?? '-' }}
                    </td>

                    {{-- 10. Waktu data dibuat --}}
                    <td class="text-center">
                        {{ $kecamatan->created_at
                            ? $kecamatan->created_at->format('d/m/Y H:i')
                            : '-'
                        }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="no-data">
                        Data kecamatan belum tersedia.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
```
