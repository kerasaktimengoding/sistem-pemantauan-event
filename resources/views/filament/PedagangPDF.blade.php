use Illuminate\Support\Str;
@extends('filament.pdf.layouts.main')

@section('title', 'Laporan Data Pedagang')

@section('styles')
    /* Optimasi ukuran teks untuk layout tabel berlapis data kependudukan */
    .table-pedagang th,
    .table-pedagang td {
        font-size: 7.5px;
        padding: 5px 4px;
        vertical-align: top;
    }

    .meta-above {
        display: block;
        font-size: 6.5px;
        color: #2563eb; /* Warna primary-600 */
        font-family: monospace;
        font-weight: bold;
        margin-bottom: 2px;
    }

    .nama-pedagang {
        display: block;
        font-size: 8px;
        font-weight: bold;
        color: #1e3a8a;
    }

    .nik-below {
        display: block;
        margin-top: 2px;
        font-size: 6.5px;
        color: #4b5563;
    }

    .wilayah-utama {
        display: block;
        font-weight: bold;
        color: #1f2937;
    }

    .desa-text {
        display: block;
        margin-top: 1px;
        font-size: 6.8px;
        color: #374151;
    }

    .alamat-below {
        display: block;
        margin-top: 2px;
        font-size: 6.3px;
        color: #6b7280;
        font-style: italic;
        line-height: 1.2;
    }

    .kontak-mono {
        font-family: monospace;
        font-weight: 500;
        color: #16a34a; /* Hijau WhatsApp */
    }

    /* Badge Status Akun Pedagang */
    .status-aktif {
        color: #16a34a;
        font-weight: bold;
    }
    .status-suspend {
        color: #d97706;
        font-weight: bold;
    }
    .status-pasif {
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
    <h1 class="judul-laporan">Laporan Data Pengelola / Pedagang UMKM</h1>

    <p class="periode">
        Periode: {{ now()->format('Y') }}
    </p>

    <table class="table-data table-pedagang">
        <thead>
            <tr>
                <th style="width: 30px;">No.</th>
                <th style="width: 190px;">Informasi Pedagang (Kode, Nama, NIK)</th>
                <th style="width: 250px;">Domisili Wilayah & Alamat Lengkap</th>
                <th style="width: 110px;">Kontak WhatsApp</th>
                <th style="width: 70px;">Status</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($pedagangs as $pedagang)
                @php
                    // Normalisasi teks status untuk mapping warna badge di PDF
                    $statusText = strtolower(trim($pedagang->status_pedagang ?? ''));
                    
                    $statusClass = match ($statusText) {
                        'aktif' => 'status-aktif',
                        'tersuspend', 'ditangguhkan' => 'status-suspend',
                        'non-aktif', 'pasif' => 'status-pasif',
                        default => 'status-default',
                    };

                    $statusLabel = match ($statusText) {
                        'aktif' => 'Aktif',
                        'tersuspend', 'ditangguhkan' => 'Suspended',
                        'non-aktif', 'pasif' => 'Pasif',
                        default => ucfirst($pedagang->status_pedagang ?? '-'),
                    };
                @endphp
                <tr>
                    {{-- 1. Nomor Urut --}}
                    <td class="text-center" style="color: #6b7280;">
                        {{ $loop->iteration }}
                    </td>

                    {{-- 2. Pusat Identitas (Above -> Main -> Below) --}}
                    <td>
                        <span class="meta-above">
                            🔑 KODE: {{ $pedagang->kode_pedagang ?? '-' }}
                        </span>
                        
                        <span class="nama-pedagang">
                            {{ $pedagang->nama_pedagang ?? '-' }}
                        </span>

                        <span class="nik-below">
                            @if($pedagang->nik)
                                🪪 NIK: {{ $pedagang->nik }}
                            @else
                                <span style="color: #dc2626;">⚠️ NIK Belum Direkam</span>
                            @endif
                        </span>
                    </td>

                    {{-- 3. Integrasi Wilayah Kompleks --}}
                    <td>
                        <span class="wilayah-utama">
                            📍 Kec. {{ $pedagang->kecamatan->nama_kecamatan ?? '-' }}
                        </span>
                        
                        <span class="desa-text">
                            🏡 Desa: {{ $pedagang->desa?->nama_desa ?? '-' }}
                        </span>

                        <span class="alamat-below">
                            @if($pedagang->alamat)
                                Detail: {{ Str::limit($pedagang->alamat, 80, '...') }}
                            @else
                                Tidak ada detail alamat
                            @endif
                        </span>
                    </td>

                    {{-- 4. Nomor Kontak WhatsApp (Format Monospace) --}}
                    <td class="text-center kontak-mono">
                        {{ $pedagang->no_hp ? '💬 ' . $pedagang->no_hp : '-' }}
                    </td>

                    {{-- 5. Status Akun Pedagang --}}
                    <td class="text-center">
                        <span class="{{ $statusClass }}">
                            {{ $statusLabel }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="no-data">
                        Data pedagang belum tersedia.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection