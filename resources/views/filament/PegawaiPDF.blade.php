@extends('filament.pdf.layouts.main')

@section('title', 'Laporan Data Pegawai')

@section('styles')
    /* Ukuran teks disesuaikan agar muat banyak informasi gabungan dalam satu baris */
    .table-pegawai th,
    .table-pegawai td {
    font-size: 7px;
    padding: 4px 3px;
    }

    .nama-pegawai {
    font-weight: bold;
    color: #1f2937;
    }

    .sub-info {
    display: block;
    margin-top: 1px;
    font-size: 6.3px;
    color: #4b5563;
    }

    .text-gender {
    font-weight: bold;
    color: #374151;
    }

    .jabatan-text {
    font-weight: bold;
    color: #1e40af;
    }

    .kontak-text {
    color: #111827;
    font-weight: 500;
    }

    /* Badge Status Pegawai */
    .status-aktif {
    color: #16a34a;
    font-weight: bold;
    }
    .status-tidak-aktif {
    color: #dc2626;
    font-weight: bold;
    }
    .status-cuti {
    color: #d97706;
    font-weight: bold;
    }
    .status-default {
    color: #6b7280;
    }

    .no-data {
    padding: 15px !important;
    text-align: center;
    font-style: italic;
    color: #6b7280;
    }
@endsection

@section('content')
    <h1 class="judul-laporan">Laporan Data Pegawai</h1>

    <p class="periode">
        Periode: {{ now()->format('Y') }}
    </p>

    <table class="table-data table-pegawai">
        <thead>
            <tr>
                <th style="width: 25px;">No.</th>
                <th style="width: 125px;">Pegawai (Nama / NIP)</th>
                <th style="width: 35px;">L/P</th>
                <th style="width: 140px;">Jabatan Utama & Wilayah Tugas</th>
                <th style="width: 120px;">Kontak (No. HP / Email)</th>
                <th style="width: 60px;">Status</th>
                <th style="width: 75px;">Mulai Bekerja</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($pegawais as $pegawai)
                @php
                    // Pemetaan teks gender
                    $genderText = strtolower(trim($pegawai->jenis_kelamin ?? ''));
                    $genderLabel = match ($genderText) {
                        'laki-laki', 'l' => 'L',
                        'perempuan', 'p' => 'P',
                        default => '-',
                    };

                    // Pemetaan status pegawai
                    $statusText = strtolower(trim($pegawai->status_pegawai ?? ''));
                    $statusClass = match ($statusText) {
                        'aktif' => 'status-aktif',
                        'non-aktif', 'tidak aktif' => 'status-tidak-aktif',
                        'cuti' => 'status-cuti',
                        default => 'status-default',
                    };
                @endphp
                <tr>
                    {{-- 1. Nomor Urut --}}
                    <td class="text-center">
                        {{ $loop->iteration }}
                    </td>

                    {{-- 2. Nama Pegawai & NIP --}}
                    <td>
                        <span class="nama-pegawai">{{ $pegawai->nama_pegawai ?? '-' }}</span>
                        <span class="sub-info">{{ $pegawai->nip ?? '-' }}</span>
                    </td>

                    {{-- 3. Jenis Kelamin --}}
                    <td class="text-center text-gender">
                        {{ $genderLabel }}
                    </td>

                    {{-- 4. Jabatan & Penugasan Wilayah --}}
                    <td>
                        <span class="jabatan-text">{{ $pegawai->jabatan->nama_jabatan ?? '-' }}</span>
                        <span class="sub-info">
                            {{ $pegawai->kecamatan->nama_kecamatan ?? '-' }}
                            {{ $pegawai->desa->jenis === 'kelurahan' ? 'Kel. ' . $pegawai->desa->nama_desa : 'Desa ' . $pegawai->desa->nama_desa ?? '-' }})
                        </span>
                    </td>

                    {{-- 5. Data Kontak --}}
                    <td>
                        <span class="kontak-text"> {{ $pegawai->no_hp ?? '-' }}</span>
                        @if($pegawai->email)
                            <span class="sub-info">{{ $pegawai->email }}</span>
                        @endif
                    </td>

                    {{-- 6. Status Kerja --}}
                    <td class="text-center">
                        <span class="{{ $statusClass }}">
                            {{ ucfirst($pegawai->status_pegawai ?? '-') }}
                        </span>
                    </td>

                    {{-- 7. Tanggal Masuk Kerja --}}
                    <td class="text-center">
                        {{ $pegawai->tanggal_masuk
                ? \Carbon\Carbon::parse($pegawai->tanggal_masuk)->translatedFormat('d F Y')
                : '-' 
                                }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="no-data">
                        Data pegawai belum tersedia.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection