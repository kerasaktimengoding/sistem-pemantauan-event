use Illuminate\Support\Str;
@extends('filament.pdf.layouts.main')

@section('title', 'Laporan Data Jabatan')

@section('styles')
    /* Ukuran tabel khusus laporan data jabatan agar pas landscape A4 */
    .table-jabatan th,
    .table-jabatan td {
    font-size: 7.5px;
    padding: 5px 4px;
    }

    .kode-jabatan {
    display: inline-block;
    padding: 3px 5px;
    background-color: #eff6ff;
    border-radius: 4px;
    font-weight: bold;
    color: #1d4ed8;
    }

    .nama-jabatan {
    font-weight: bold;
    color: #374151;
    }

    .sub-text {
    display: block;
    margin-top: 2px;
    font-size: 6.5px;
    color: #4b5563;
    line-height: 1.2;
    }

    .wewenang-text {
    color: #6b7280;
    line-height: 1.3;
    }

    /* Status Jabatan warna kontras tinggi */
    .status-aktif {
    color: #16a34a;
    font-weight: bold;
    }

    .status-tidak-aktif {
    color: #dc2626;
    font-weight: bold;
    }

    .status-default {
    color: #d97706;
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
    <h1 class="judul-laporan">Laporan Data Jabatan</h1>

    <p class="periode">
        Periode: {{ now()->format('Y') }}
    </p>

    <table class="table-data table-jabatan">
        <thead>
            <tr>
                <th style="width: 20px;">No.</th>
                <th style="width: 50px;">Kode Jabatan</th>
                <th style="width: 180px;">Nama & Tugas Jabatan</th>
                <th style="width: 300px;">Wewenang Utama</th>
                <th style="width: 75px;">Status</th>
            
            </tr>
        </thead>

        <tbody>
            @forelse ($jabatans as $jabatan)
                @php
                    // Normalisasi status jabatan untuk menentukan class style
                    $statusText = strtolower(trim($jabatan->status_jabatan ?? ''));
                    $statusClass = match ($statusText) {
                        'aktif' => 'status-aktif',
                        'non-aktif', 'non aktif', 'tidak aktif' => 'status-tidak-aktif',
                        default => 'status-default',
                    };
                    $statusLabel = match ($statusText) {
                        'aktif' => 'Aktif',
                        'non-aktif', 'non aktif', 'tidak aktif' => 'Non-Aktif',
                        default => ucfirst($jabatan->status_jabatan ?? '-'),
                    };
                @endphp
                <tr>
                    {{-- 1. Nomor urut --}}
                    <td class="text-center">
                        {{ $loop->iteration }}
                    </td>

                    {{-- 2. Kode Jabatan --}}
                    <td class="text-center">
                        <span class="kode-jabatan">
                            {{ $jabatan->kode_jabatan ?? '-' }}
                        </span>
                    </td>

                    {{-- 3. Nama & Tugas Jabatan --}}
                    <td>
                        <span class="nama-jabatan">
                            {{ $jabatan->nama_jabatan ?? '-' }}
                        </span>
                        @if($jabatan->tugas_pokok)
                            <span class="sub-text">
                                {{ Str::limit($jabatan->tugas_pokok, 80, '...') }}
                            </span>
                        @endif
                    </td>

                    {{-- 4. Wewenang Utama --}}
                    <td class="wewenang-text">
                        {{ $jabatan->wewenang ?? '-' }}
                    </td>

                    {{-- 5. Status --}}
                    <td class="text-center">
                        <span class="{{ $statusClass }}">
                            {{ $statusLabel }}
                        </span>
                    </td>

                    {{-- 6. Waktu Data Dibuat --}}

                </tr>
            @empty
                <tr>
                    <td colspan="6" class="no-data">
                        Data jabatan belum tersedia.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection