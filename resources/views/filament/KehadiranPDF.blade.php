use Illuminate\Support\Str;
@extends('filament.pdf.layouts.main')

@section('title', 'Laporan Kehadiran Event UMKM')

@section('styles')
    /* Optimasi ukuran baris presensi event */
    .table-event th,
    .table-event td {
    font-size: 7.5px;
    padding: 5px 4px;
    vertical-align: top;
    }

    .text-mono {
    font-family: monospace;
    }

    .waktu-log {
    font-weight: bold;
    color: #2563eb; /* Warna primary-600 */
    }

    .sub-info {
    display: block;
    margin-top: 2px;
    font-size: 6.3px;
    color: #4b5563;
    line-height: 1.2;
    }

    .nama-peserta {
    display: block;
    font-size: 8.5px; /* Visual Anchor Utama */
    font-weight: bold;
    color: #111827;
    }

    .nama-usaha {
    display: block;
    margin-top: 1px;
    font-size: 6.8px;
    color: #4b5563;
    font-weight: 500;
    }

    /* Penanda Status Presensi */
    .presensi-hadir {
    color: #16a34a;
    font-weight: bold;
    }
    .presensi-izin {
    color: #d97706;
    font-weight: bold;
    }
    .presensi-terlambat {
    color: #dc2626;
    font-weight: bold;
    }
    .presensi-default {
    color: #6b7280;
    font-weight: bold;
    }

    .catatan-text {
    font-size: 6.5px;
    color: #6b7280;
    line-height: 1.3;
    }

    .no-data {
    padding: 15px !important;
    text-align: center;
    font-style: italic;
    color: #6b7280;
    }
@endsection

@section('content')
    <h1 class="judul-laporan">Laporan Presensi & Kehadiran Event UMKM</h1>

    <p class="periode">
        Tanggal Unduh: {{ now()->translatedFormat('d F Y, H:i') }} WITA
    </p>

    <table class="table-data table-event">
        <thead>
            <tr>
                <th style="width: 30px;">No.</th>
                <th style="width: 140px;">Waktu Kedatangan</th>
                <th style="width: 260px;">Nama Peserta / Profil Usaha</th>
                <th style="width: 100px;">Status Presensi</th>
                <th style="width: 140px;">Catatan / Keterangan</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($kehadirans as $log)
                @php
                    // Normalisasi status presensi untuk penentuan warna teks
                    $status = trim($log->status_kehadiran ?? '');

                    $statusClass = match ($status) {
                        'Hadir' => 'presensi-hadir',
                        'Izin' => 'presensi-izin',
                        'Terlambat' => 'presensi-terlambat',
                        default => 'presensi-default',
                    };
                @endphp
                <tr>
                    {{-- 1. Nomor Urut --}}
                    <td class="text-center" style="color: #6b7280;">
                        {{ $loop->iteration }}
                    </td>

                    {{-- 2. Waktu Kedatangan & Log ID --}}
                    <td>
                        <span class="waktu-log text-mono">
                            {{ $log->waktu_kehadiran ? \Carbon\Carbon::parse($log->waktu_kehadiran)->translatedFormat('d M Y, H:i') : '-' }}
                        </span>
                        <span class="sub-info text-mono">
                            Log ID: {{ $log->kode_kehadiran ?? '-' }}
                        </span>
                    </td>

                    {{-- 3. Nama Peserta & Entitas Bisnis Usaha --}}
                    <td>
                        <span class="nama-peserta">
                            {{ $log->pesertaevent->nama_peserta ?? '-' }}
                        </span>
                        <span class="nama-usaha">
                            Usaha: {{ $log->pesertaevent->nama_usaha ?? '-' }}
                        </span>
                    </td>

                    {{-- 4. Status Presensi --}}
                    <td class="text-center">
                        <span class="{{ $statusClass }}">
                            {{ $status ?? '-' }}
                        </span>
                    </td>

                    {{-- 5. Catatan / Alasan --}}
                    <td>
                        <span class="catatan-text">
                            @if($log->catatan)
                                {{ Str::limit($log->catatan, 60, '...') }}
                            @else
                                <span style="color: #9ca3af; font-style: italic;">Tidak ada catatan</span>
                            @endif
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="no-data">
                        Data log presensi event belum tersedia.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection