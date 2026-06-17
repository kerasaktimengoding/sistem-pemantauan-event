use Illuminate\Support\Str;
@extends('filament.pdf.layouts.main')

@section('title', 'Laporan Data Pasar')

@section('styles')
    /* Penyesuaian ukuran font khusus agar detail alamat yang panjang tetap rapi */
    .table-pasar th,
    .table-pasar td {
        font-size: 7.5px;
        padding: 5px 4px;
        vertical-align: top;
    }

    .meta-above {
        display: block;
        font-size: 6.5px;
        color: #2563eb;
        font-family: monospace;
        font-weight: bold;
        margin-bottom: 2px;
    }

    .nama-pasar {
        display: block;
        font-size: 8px;
        font-weight: bold;
        color: #1e3a8a; /* Warna primary-600 versi cetak */
    }

    .alamat-below {
        display: block;
        margin-top: 2px;
        font-size: 6.5px;
        color: #4b5563;
        line-height: 1.3;
    }

    .wilayah-utama {
        font-weight: 600;
        color: #374151;
    }

    .sub-desa {
        display: block;
        margin-top: 2px;
        font-size: 6.5px;
        color: #6b7280;
    }

    /* Penanda Status Operasional Pasar */
    .status-aktif {
        color: #16a34a;
        font-weight: bold;
    }
    .status-tutup {
        color: #dc2626;
        font-weight: bold;
    }
    .status-renovasi {
        color: #d97706;
        font-weight: bold;
    }
    .status-default {
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
    <h1 class="judul-laporan">Laporan Data Pasar Kabupaten Banjar</h1>

    <p class="periode">
        Periode: {{ now()->format('Y') }}
    </p>

    <table class="table-data table-pasar">
        <thead>
            <tr>
                <th style="width: 35px;">No.</th>
                <th style="width: 320px;">Detail Pasar (Kode, Nama, & Alamat Lengkap)</th>
                <th style="width: 180px;">Nama Wilayah (Kecamatan / Desa)</th>
                <th style="width: 115px;">Status Operasional</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($pasars as $pasar)
                @php
                    // Normalisasi status pasar untuk menentukan style warna
                    $statusText = strtolower(trim($pasar->status_pasar ?? ''));
                    
                    $statusClass = match ($statusText) {
                        'aktif' => 'status-aktif',
                        'non-aktif', 'tutup' => 'status-tutup',
                        'renovasi', 'perbaikan' => 'status-renovasi',
                        default => 'status-default',
                    };

                    $statusLabel = match ($statusText) {
                        'aktif' => 'Aktif',
                        'non-aktif', 'tutup' => 'Tutup',
                        'renovasi', 'perbaikan' => 'Renovasi',
                        default => ucfirst($pasar->status_pasar ?? '-'),
                    };
                @endphp
                <tr>
                    {{-- 1. Nomor Urut --}}
                    <td class="text-center" style="color: #6b7280;">
                        {{ $loop->iteration }}
                    </td>

                    {{-- 2. Pusat Informasi Detail Pasar (Above -> Main -> Below) --}}
                    <td>
                        {{-- Baris Atas: Kode Pasar --}}
                        <span class="meta-above">
                            🔑 KODE: {{ $pasar->kode_pasar ?? '-' }}
                        </span>

                        {{-- Tengah: Nama Pasar --}}
                        <span class="nama-pasar">
                            {{ $pasar->nama_pasar ?? '-' }}
                        </span>

                        {{-- Baris Bawah: Alamat Lengkap --}}
                        <span class="alamat-below">
                            @if($pasar->alamat_pasar)
                                📍 {{ Str::limit($pasar->alamat_pasar, 100, '...') }}
                            @else
                                Belum ada alamat lengkap
                            @endif
                        </span>
                    </td>

                    {{-- 3. Informasi Integrasi Wilayah --}}
                    <td>
                        <span class="wilayah-utama">
                            {{ $pasar->kecamatan->nama_kecamatan ?? '-' }}
                        </span>
                        <span class="sub-desa">
                            🏡 Desa: {{ $pasar->desa?->nama_desa ?? '-' }}
                        </span>
                    </td>

                    {{-- 4. Status Operasional --}}
                    <td class="text-center">
                        <span class="{{ $statusClass }}">
                            {{ $statusLabel }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="no-data">
                        Data pasar belum tersedia.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection