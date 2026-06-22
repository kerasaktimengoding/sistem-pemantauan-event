@extends('filament.pdf.layouts.main')

@section('title', 'Laporan Data Tempat Usaha')

@section('styles')
    /* Optimasi ukuran font untuk layout cetak landscape */
    .table-tempat th,
    .table-tempat td {
        font-size: 7.5px;
        padding: 5px 4px;
    }

    .kode-tempat {
        font-weight: bold;
        color: #1d4ed8;
        background-color: #eff6ff;
        padding: 2px 4px;
        border-radius: 4px;
        display: inline-block;
    }

    .nama-utama {
        font-weight: bold;
        color: #374151;
    }

    .sub-info {
        display: block;
        margin-top: 2px;
        font-size: 6.5px;
        color: #6b7280;
    }

    /* Penanda Status Tempat Usaha */
    .status-terisi {
        color: #16a34a;
        font-weight: bold;
    }
    .status-kosong {
        color: #6b7280;
        font-weight: bold;
    }
    .status-rusak {
        color: #dc2626;
        font-weight: bold;
    }
    .status-booking {
        color: #d97706;
        font-weight: bold;
    }
    .status-default {
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
    <h1 class="judul-laporan">Laporan Data Tempat Usaha</h1>

    <p class="periode">
        Periode: {{ now()->format('Y') }}
    </p>

    <table class="table-data table-tempat">
        <thead>
            <tr>
                <th style="width: 30px;">No.</th>
                <th style="width: 85px;">Kode Tempat</th>
                <th style="width: 90px;">No. Tempat / Blok</th>
                <th style="width: 75px;">Luas Ukuran</th>
                <th style="width: 150px;">Lokasi Pasar & Wilayah</th>
                <th style="width: 150px;">Pengelola / Pedagang</th>
                <th style="width: 70px;">Status</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($tempats as $tempat)
                @php
                    // Normalisasi string status untuk mengantisipasi variasi database (kebal typo)
                    $statusText = strtolower(trim($tempat->status_tempat ?? ''));
                    
                    $statusClass = match ($statusText) {
                        'aktif', 'terisi', 'tersewa' => 'status-terisi',
                        'kosong', 'tersedia' => 'status-kosong',
                        'perbaikan', 'rusak', 'renovasi' => 'status-rusak',
                        'booking', 'dipesan' => 'status-booking',
                        default => 'status-default',
                    };

                    $statusLabel = match ($statusText) {
                        'aktif', 'terisi', 'tersewa' => 'Terisi',
                        'kosong', 'tersedia' => 'Kosong',
                        'perbaikan', 'rusak', 'renovasi' => 'Perbaikan',
                        'booking', 'dipesan' => 'Booking',
                        default => ucfirst($tempat->status_tempat ?? '-'),
                    };
                @endphp
                <tr>
                    {{-- 1. Nomor urut --}}
                    <td class="text-center">
                        {{ $loop->iteration }}
                    </td>

                    {{-- 2. Kode Tempat Usaha --}}
                    <td class="text-center">
                        <span class="kode-tempat">
                            {{ $tempat->kode_tempat_usaha ?? '-' }}
                        </span>
                    </td>

                    {{-- 3. Nomor / Nama Blok --}}
                    <td class="text-center nama-utama">
                        {{ $tempat->nomor_tempat ?? '-' }}
                    </td>

                    {{-- 4. Luas Tempat --}}
                    <td class="text-center" style="font-weight: 500; width: 100px;">
                        {{ $tempat->luas_tempat ?? '0' }} m²
                    </td>

                    {{-- 5. Data Pasar & Wilayah Desa --}}
                    <td>
                        <span class="nama-utama">{{ $tempat->pasar->nama_pasar ?? '-' }}</span>
                        <span class="sub-info"> Wilayah: {{ $tempat->desa->nama_desa ?? '-' }}</span>
                    </td>

                    {{-- 6. Data Pedagang & Kontak HP --}}
                    <td>
                        <span class="nama-utama">{{ $tempat->pedagang->nama_pedagang ?? 'Belum Terisi' }}</span>
                        <span class="sub-info">{{ $tempat->nomor_hp ?? ($tempat->pedagang->nomor_hp ?? '-') }}</span>
                    </td>

                    {{-- 7. Badge Status Dinamis --}}
                    <td class="text-center">
                        <span class="{{ $statusClass }}">
                            {{ $statusLabel }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="no-data">
                        Data tempat usaha belum tersedia.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection