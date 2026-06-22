@extends('filament.pdf.layouts.main')

@section('title', 'Laporan Daftar Kegiatan UMKM')

@section('styles')
    /* Tata letak kolom master data kegiatan */
    .table-kegiatan th,
    .table-kegiatan td {
        font-size: 7.5px;
        padding: 5px 4px;
        vertical-align: top;
    }

    .text-mono {
        font-family: monospace;
    }

    .kode-event {
        font-weight: bold;
        color: #2563eb; /* Warna primary-600 */
    }

    .nama-event {
        display: block;
        font-size: 8.5px;
        font-weight: bold;
        color: #1e3a8a;
        margin-bottom: 2px;
    }

    .sub-info {
        display: block;
        font-size: 6.5px;
        color: #4b5563;
        line-height: 1.2;
    }

    .waktu-pelaksanaan {
        font-weight: 600;
        color: #111827;
    }

    .lokasi-text {
        font-size: 6.8px;
        color: #374151;
        line-height: 1.3;
    }

    /* Badge Status Pelaksanaan Event */
    .status-mendatang {
        color: #2563eb;
        font-weight: bold;
    }
    .status-berjalan {
        color: #d97706;
        font-weight: bold;
    }
    .status-selesai {
        color: #16a34a;
        font-weight: bold;
    }
    .status-batal {
        color: #dc2626;
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
    <h1 class="judul-laporan">Laporan Daftar Agenda & Event Kegiatan UMKM</h1>

    <p class="periode">
        Tahun Anggaran: {{ now()->format('Y') }}
    </p>

    <table class="table-data table-kegiatan">
        <thead>
            <tr>
                <th style="width: 30px;">No.</th>
                <th style="width: 75px;">Kode Event</th>
                <th style="width: 210px;">Detail Nama Kegiatan & Instansi</th>
                <th style="width: 130px;">Waktu Pelaksanaan</th>
                <th style="width: 145px;">Tempat / Lokasi Instansi</th>
                <th style="width: 80px;">Status Agenda</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($events as $event)
                @php
                    // Normalisasi status event untuk keperluan style cetak
                    $status = strtolower(trim($event->status_event ?? $event->status ?? ''));
                    
                    $statusClass = match ($status) {
                        'mendatang', 'planned', 'persiapan' => 'status-mendatang',
                        'berjalan', 'ongoing', 'mulai' => 'status-berjalan',
                        'selesai', 'success', 'done' => 'status-selesai',
                        'batal', 'canceled' => 'status-batal',
                        default => '',
                    };

                    $statusLabel = match ($status) {
                        'mendatang', 'planned', 'persiapan' => 'Persiapan',
                        'berjalan', 'ongoing', 'mulai' => 'Berjalan',
                        'selesai', 'success', 'done' => 'Selesai',
                        'batal', 'canceled' => 'Batal',
                        default => ucfirst($event->status_event ?? $event->status ?? '-'),
                    };
                @endphp
                <tr>
                    {{-- 1. Nomor Urut --}}
                    <td class="text-center" style="color: #6b7280;">
                        {{ $loop->iteration }}
                    </td>

                    {{-- 2. Kode Agenda Event --}}
                    <td class="text-center kode-event text-mono">
                        {{ $event->kode_event ?? $event->kode_kegiatan ?? '-' }}
                    </td>

                    {{-- 3. Detail Nama Kegiatan & Penyelenggara --}}
                    <td>
                        <span class="nama-event">
                             {{ $event->nama_event ?? $event->nama_kegiatan ?? '-' }}
                        </span>
                        <span class="sub-info">
                             Bidang/Penyelenggara: {{ $event->penyelenggara ?? 'DKUMPP Kab. Banjar' }}
                        </span>
                    </td>

                    {{-- 4. Rentang Waktu Pelaksanaan --}}
                    <td>
                        <span class="waktu-pelaksanaan">
                             {{ $event->tanggal_mulai ? \Carbon\Carbon::parse($event->tanggal_mulai)->translatedFormat('d M Y') : '-' }}
                        </span>
                        @if($event->tanggal_selesai && $event->tanggal_mulai != $event->tanggal_selesai)
                            <span class="sub-info" style="margin-top: 1px;">
                                s.d {{ \Carbon\Carbon::parse($event->tanggal_selesai)->translatedFormat('d M Y') }}
                            </span>
                        @endif
                    </td>

                    {{-- 5. Tempat / Lokasi Pelaksanaan --}}
                    <td>
                        <span class="lokasi-text">
                             {{ $event->lokasi ?? $event->tempat_pelaksanaan ?? 'Tempat Belum Ditentukan' }}
                        </span>
                    </td>

                    {{-- 6. Status Agenda Pelaksanaan --}}
                    <td class="text-center">
                        <span class="{{ $statusClass }}">
                            {{ $statusLabel }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="no-data">
                        Daftar agenda kegiatan atau event UMKM belum terekam.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection