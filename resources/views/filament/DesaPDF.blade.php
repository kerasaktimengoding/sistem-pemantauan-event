@extends('filament.pdf.layouts.main')

@section('title', 'Laporan Data Desa')

@section('styles')
    /* Ukuran tabel khusus laporan data desa agar pas landscape A4 */
    .table-desa th,
    .table-desa td {
        font-size: 7px;
        padding: 4px 3px;
    }

    .kode-desa {
        display: inline-block;
        padding: 3px 5px;
        background-color: #e5e7eb;
        border-radius: 4px;
        font-weight: bold;
        color: #374151;
    }

    .nama-desa {
        font-weight: bold;
        color: #1d4ed8;
    }

    .sub-text {
        display: block;
        margin-top: 2px;
        font-size: 6.5px;
        color: #6b7280;
    }

    .status-aktif {
        color: #16a34a;
        font-weight: bold;
    }

    .status-non-aktif {
        color: #dc2626;
        font-weight: bold;
    }

    .luas-wilayah {
        color: #0284c7;
        font-weight: bold;
    }

    .kode-pos-badge {
        display: inline-block;
        padding: 2px 4px;
        background-color: #fef3c7;
        color: #d97706;
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
    <h1 class="judul-laporan">Laporan Data Desa</h1>

    <p class="periode">
        Periode: {{ now()->format('Y') }}
    </p>

    <table class="table-data table-desa">
        <thead>
            <tr>
                <th style="width: 25px;">No.</th>
                <th style="width: 65px;">Kode Desa</th>
                <th style="width: 120px;">Informasi Wilayah</th>
                <th style="width: 110px;">Pimpinan / Pembakal</th>
                <th style="width: 140px;">Alamat Kantor</th>
                <th style="width: 50px;">Kode Pos</th>
                <th style="width: 65px;">Luas Wilayah</th>
                <th style="width: 60px;">Status</th>
                <th style="width: 75px;">Sistem Dibuat</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($desas as $desa)
                <tr>
                    {{-- 1. Nomor urut --}}
                    <td class="text-center">
                        {{ $loop->iteration }}
                    </td>

                    {{-- 2. Kode Desa --}}
                    <td class="text-center">
                        <span class="kode-desa">
                            {{ $desa->kode_desa ?? '-' }}
                        </span>
                    </td>

                    {{-- 3. Nama Desa & Kecamatan Induk --}}
                    <td>
                        <span class="nama-desa">
                            {{ $desa->nama_desa ?? '-' }}
                        </span>
                        <span class="sub-text">
                            Kecamatan: {{ $desa->kecamatan->nama_kecamatan ?? '-' }}
                        </span>
                    </td>

                    {{-- 4. Nama Pembakal & No HP --}}
                    <td>
                        <strong>
                            {{ $desa->nama_pembakal ?? 'Belum Ada Pejabat' }}
                        </strong>
                        <span class="sub-text">
                            📞 {{ $desa->no_hp_pembakal ?? '-' }}
                        </span>
                    </td>

                    {{-- 5. Alamat Kantor Desa --}}
                    <td>
                        {{ $desa->alamat_kantor_desa ?? '-' }}
                    </td>

                    {{-- 6. Kode Pos --}}
                    <td class="text-center">
                        <span class="kode-pos-badge">
                            {{ $desa->kode_pos ?? '-' }}
                        </span>
                    </td>

                    {{-- 7. Luas Wilayah --}}
                    <td class="text-right luas-wilayah">
                        {{ number_format(
                            (float) ($desa->luas_wilayah ?? 0),
                            2,
                            ',',
                            '.'
                        ) }} Km²
                    </td>

                    {{-- 8. Status Keaktifan --}}
                    <td class="text-center">
                        <span class="{{ $desa->is_active == '1' ? 'status-aktif' : 'status-non-aktif' }}">
                            {{ $desa->is_active == '1' ? 'Aktif' : 'Non-Aktif' }}
                        </span>
                    </td>

                    {{-- 9. Waktu Data Dibuat --}}
                    <td class="text-center">
                        {{ $desa->created_at 
                            ? $desa->created_at->format('d/m/Y H:i') 
                            : '-' 
                        }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="no-data">
                        Data desa belum tersedia.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection