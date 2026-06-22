@extends('filament.pdf.layouts.main')

@section('title', 'Laporan Peserta Event UMKM')

@section('styles')
    /* Optimasi ukuran baris daftar peserta */
    .table-peserta th,
    .table-peserta td {
        font-size: 7.3px;
        padding: 5px 4px;
        vertical-align: top;
    }

    .text-mono {
        font-family: monospace;
    }

    .nama-peserta {
        display: block;
        font-size: 8.5px; /* Visual Anchor */
        font-weight: bold;
        color: #111827;
    }

    .sub-info {
        display: block;
        margin-top: 1px;
        font-size: 6.3px;
        color: #4b5563;
        line-height: 1.2;
    }

    .nama-usaha {
        font-weight: bold;
        color: #2563eb; /* Warna utama identitas bisnis */
    }

    .agenda-badge {
        font-weight: bold;
        color: #0369a1; /* Biru info premium */
    }

    .kontak-wa {
        color: #16a34a;
        font-weight: 500;
    }

    /* Status Partisipasi Berwarna */
    .status-terdaftar {
        color: #0284c7;
        font-weight: bold;
    }
    .status-hadir {
        color: #16a34a;
        font-weight: bold;
    }
    .status-batal {
        color: #dc2626;
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
    <h1 class="judul-laporan">Laporan Daftar Peserta & Profil Usaha Event UMKM</h1>

    <p class="periode">
        Tanggal Cetak: {{ now()->translatedFormat('d F Y') }}
    </p>

    <table class="table-data table-peserta">
        <thead>
            <tr>
                <th style="width: 25px;">No.</th>
                <th style="width: 140px;">Nama Peserta / NIK</th>
                <th style="width: 160px;">Profil Usaha & Produk</th>
                <th style="width: 140px;">Agenda Kegiatan</th>
                <th style="width: 95px;">Kontak WhatsApp</th>
                <th style="width: 85px;">Wilayah Asal</th>
                <th style="width: 65px;">Status</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($pesertas as $peserta)
                @php
                    // Mapping status partisipasi
                    $status = trim($peserta->status_partisipasi ?? '');
                    
                    $statusClass = match ($status) {
                        'Terdaftar' => 'status-terdaftar',
                        'Hadir'     => 'status-hadir',
                        'Batal'     => 'status-batal',
                        default     => 'status-default',
                    };
                @endphp
                <tr>
                    {{-- 1. Nomor Urut --}}
                    <td class="text-center" style="color: #6b7280;">
                        {{ $loop->iteration }}
                    </td>

                    {{-- 2. Nama Peserta + NIK --}}
                    <td>
                        <span class="nama-peserta"> {{ $peserta->nama_peserta ?? '-' }}</span>
                        <span class="sub-info text-mono">NIK: {{ $peserta->nik ?? '-' }}</span>
                    </td>

                    {{-- 3. Nama Usaha + Jenis Produk --}}
                    <td>
                        <span class="nama-usaha"> {{ $peserta->nama_usaha ?? '-' }}</span>
                        <span class="sub-info">Produk: {{ $peserta->jenis_produk ?? '-' }}</span>
                    </td>

                    {{-- 4. Agenda Kegiatan (Kapital Berwarna Info) --}}
                    <td>
                        <span class="agenda-badge">
                             {{ $peserta->event?->nama_event ? strtoupper($peserta->event->nama_event) : '-' }}
                        </span>
                    </td>

                    {{-- 5. Kontak WhatsApp (Monospace) --}}
                    <td class="text-center text-mono kontak-wa">
                        {{ $peserta->no_hp ? ' ' . $peserta->no_hp : '-' }}
                    </td>

                    {{-- 6. Lokasi Wilayah Asal --}}
                    <td>
                        <span> {{ $peserta->desa->nama_desa ?? 'Wilayah' }}</span>
                    </td>

                    {{-- 7. Status Partisipasi --}}
                    <td class="text-center">
                        <span class="{{ $statusClass }}">
                            {{ $status ?? '-' }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="no-data">
                        Daftar data peserta event belum tersedia.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection