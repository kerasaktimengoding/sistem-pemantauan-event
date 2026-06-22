use Illuminate\Support\Str;
@extends('filament.pdf.layouts.main')

@section('title', 'Laporan Jadwal Monitoring Harga')

@section('styles')
    /* Optimasi ruang baris data operasional dinas lapang */
    .table-monitoring th,
    .table-monitoring td {
        font-size: 7.3px;
        padding: 5px 3px;
        vertical-align: top;
    }

    .kode-tugas {
        font-family: monospace;
        font-weight: bold;
        color: #374151;
        background-color: #f3f4f6;
        padding: 2px 4px;
        border-radius: 4px;
        font-size: 7px;
        display: inline-block;
    }

    .text-rencana {
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

    .nama-utama {
        font-weight: 600;
        color: #111827;
    }

    .catatan-text {
        font-size: 6.5px;
        color: #6b7280;
        line-height: 1.2;
    }

    /* Status Kemajuan Badge System */
    .status-selesai {
        color: #16a34a;
        font-weight: bold;
    }
    .status-proses {
        color: #d97706;
        font-weight: bold;
    }
    .status-pending {
        color: #6b7280;
        font-weight: bold;
    }
    .status-batal {
        color: #dc2626;
        font-weight: bold;
    }
    .status-default {
        color: #2563eb;
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
    <h1 class="judul-laporan">Laporan Jadwal Monitoring Harga Bahan Pokok</h1>

    <p class="periode">
        Periode Cetak: {{ now()->translatedFormat('F Y') }}
    </p>

    <table class="table-data table-monitoring">
        <thead>
            <tr>
                <th style="width: 25px;">No.</th>
                <th style="width: 65px;">Kode Tugas</th>
                <th style="width: 140px;">Rencana Pelaksanaan & Surat</th>
                <th style="width: 135px;">Lokasi Pasar Target</th>
                <th style="width: 120px;">Petugas Lapangan</th>
                <th style="width: 75px;">Status</th>
                <th style="width: 110px;">Hasil Temuan / Catatan</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($jadwals as $jadwal)
                @php
                    // Normalisasi string status kemajuan
                    $statusText = strtolower(trim($jadwal->status_monitoring ?? ''));
                    
                    $statusClass = match ($statusText) {
                        'selesai', 'success', 'approved' => 'status-selesai',
                        'proses', 'ongoing', 'on progress' => 'status-proses',
                        'pending', 'draft' => 'status-pending',
                        'batal', 'rejected', 'failed' => 'status-batal',
                        default => 'status-default',
                    };

                    $statusLabel = match ($statusText) {
                        'selesai', 'success', 'approved' => 'SELESAI',
                        'proses', 'ongoing', 'on progress' => 'PROSES',
                        'pending', 'draft' => 'PENDING',
                        'batal', 'rejected', 'failed' => 'BATAL',
                        default => strtoupper($jadwal->status_monitoring ?? '-'),
                    };
                @endphp
                <tr>
                    {{-- 1. Nomor Urut --}}
                    <td class="text-center" style="color: #6b7280;">
                        {{ $loop->iteration }}
                    </td>

                    {{-- 2. Kode Tugas --}}
                    <td class="text-center">
                        <span class="kode-tugas">
                            {{ $jadwal->kode_jadwal ?? '-' }}
                        </span>
                    </td>

                    {{-- 3. Rencana Pelaksanaan & No Surat Tugas --}}
                    <td>
                        <span class="text-rencana">
                            {{ $jadwal->tanggal_rencana ? \Carbon\Carbon::parse($jadwal->tanggal_rencana)->translatedFormat('d M Y') : '-' }}
                        </span>
                        <span class="sub-info">
                            {{ $jadwal->nomor_surat_tugas ? ' No. Surat: '.$jadwal->nomor_surat_tugas : '⚠️ Surat Tugas: Belum Diterbitkan' }}
                        </span>
                    </td>

                    {{-- 4. Target Lokasi Pasar & Wilayah Kecamatan --}}
                    <td>
                        @if($jadwal->pasar)
                            <span class="nama-utama">📍 {{ $jadwal->pasar->nama_pasar }}</span>
                            <span class="sub-info"> Wilayah: {{ $jadwal->pasar->kecamatan->nama_kecamatan ?? '-' }}</span>
                        @else
                            <span class="text-muted" style="font-style: italic; color: #9ca3af;">Pasar Tidak Terdaftar</span>
                        @endif
                    </td>

                    {{-- 5. Petugas Lapangan & NIP --}}
                    <td>
                        @if($jadwal->pegawai)
                            <span class="nama-utama"> {{ $jadwal->pegawai->nama_pegawai }}</span>
                            <span class="sub-info"> NIP. {{ $jadwal->pegawai->nip ?? '-' }}</span>
                        @else
                            <span class="text-muted" style="font-style: italic; color: #9ca3af;">Belum Ditunjuk</span>
                        @endif
                    </td>

                    {{-- 6. Status Kemajuan (Formatted Upper) --}}
                    <td class="text-center">
                        <span class="{{ $statusClass }}">
                            {{ $statusLabel }}
                        </span>
                    </td>

                    {{-- 7. Catatan Temuan Lapangan --}}
                    <td>
                        <span class="catatan-text">
                            @if($jadwal->catatan_petugas)
                                {{ Str::limit($jadwal->catatan_petugas, 55, '...') }}
                            @else
                                Tidak ada catatan khusus lapangan
                            @endif
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="no-data">
                        Data jadwal monitoring belum tersedia.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection