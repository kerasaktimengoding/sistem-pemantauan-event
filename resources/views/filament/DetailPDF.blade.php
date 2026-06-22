use Illuminate\Support\Str;
@extends('filament.pdf.layouts.main')

@section('title', 'Laporan Detail Pelaksanaan Event')

@section('styles')
    /* Desain khusus tabel detail operasional dan finansial */
    .table-detail-event th,
    .table-detail-event td {
        font-size: 7.5px;
        padding: 6px 5px;
        vertical-align: top;
    }

    .text-mono {
        font-family: monospace;
    }

    .nama-event {
        display: block;
        font-size: 8.5px;
        font-weight: bold;
        color: #111827;
    }

    .sub-info {
        display: block;
        margin-top: 2px;
        font-size: 6.5px;
        color: #4b5563;
        line-height: 1.3;
    }

    .narasumber-tag {
        font-weight: 600;
        color: #374151;
    }

    /* Format keuangan kanan lurus */
    .text-right {
        text-align: right;
    }

    /* Pewarnaan Dinamis Anggaran (Skala Prioritas) */
    .anggaran-high {
        color: #16a34a; /* Hijau jika >= 50 Juta (Standout) */
        font-weight: bold;
    }
    .anggaran-normal {
        color: #2563eb; /* Biru primary jika di bawah 50 Juta */
        font-weight: bold;
    }

    /* Indikator Kuota Kritis vs Aman */
    .kuota-danger {
        color: #dc2626;
        font-weight: bold;
    }
    .kuota-info {
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
    <h1 class="judul-laporan">Laporan Detail Pelaksanaan & Anggaran Event UMKM</h1>

    <p class="periode">
        Tanggal Cetak Dokumen: {{ now()->translatedFormat('d F Y') }}
    </p>

    <table class="table-data table-detail-event">
        <thead>
            <tr>
                <th style="width: 25px;">No.</th>
                <th style="width: 220px;">Detail Kegiatan (Kode & Penyelenggara)</th>
                <th style="width: 125px;">Narasumber / Pemateri</th>
                <th style="width: 110px; text-align: right;">Alokasi Anggaran</th>
                <th style="width: 75px; text-align: center;">Kuota</th>
                <th style="width: 145px;">Deskripsi Operasional</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($details as $detail)
                @php
                    // 1. Logika Pewarnaan Anggaran (Standout >= 50 Juta)
                    $anggaran = (float) $detail->anggaran_event;
                    $anggaranClass = $anggaran >= 50000000 ? 'anggaran-high' : 'anggaran-normal';

                    // 2. Logika Penanda Kuota Kritis (<= 20 Orang)
                    $kuota = (int) $detail->kuota_peserta;
                    $kuotaClass = $kuota <= 20 ? 'kuota-danger' : 'kuota-info';
                    $kuotaIcon = $kuota <= 20 ? ' ' : ' ';
                @endphp
                <tr>
                    {{-- 1. Nomor Urut --}}
                    <td class="text-center" style="color: #6b7280;">
                        {{ $loop->iteration }}
                    </td>

                    {{-- 2. Gabungan Informasi Utama, Kode, dan Penyelenggara --}}
                    <td>
                        <span class="nama-event"> {{ $detail->event?->nama_event ?? '-' }}</span>
                        <span class="sub-info">
                            Kode: <span class="text-mono">{{ $detail->kode_detail_event ?? '-' }}</span> • Oleh: {{ $detail->penyelenggara ?? '-' }}
                        </span>
                    </td>

                    {{-- 3. Narasumber / Pemateri --}}
                    <td>
                        <span class="narasumber-tag"> {{ $detail->narasumber ?? '-' }}</span>
                    </td>

                    {{-- 4. Format Anggaran Finansial Rupiah (Sejajar Kanan) --}}
                    <td class="text-right text-mono {{ $anggaranClass }}">
                        Rp {{ number_format($anggaran, 2, ',', '.') }}
                    </td>

                    {{-- 5. Kuota Peserta (Kondisional) --}}
                    <td class="text-center text-mono {{ $kuotaClass }}">
                        {{ $kuotaIcon }}{{ $kuota }} Orang
                    </td>

                    {{-- 6. Deskripsi Event Rapi --}}
                    <td>
                        <span style="color: #555555; font-size: 6.8px; line-height: 1.3;">
                            @if($detail->deskripsi_event)
                                {{ Str::limit($detail->deskripsi_event, 65, '...') }}
                            @else
                                <span style="color: #d1d5db; font-style: italic;">Tidak ada deskripsi tambahan</span>
                            @endif
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="no-data">
                        Rincian teknis pelaksanaan event belum terekam di dalam sistem.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection