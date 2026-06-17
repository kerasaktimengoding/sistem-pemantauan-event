use Illuminate\Support\Str;
@extends('filament.pdf.layouts.main')

@section('title', 'Laporan Data Bahan Pokok')

@section('styles')
    /* Penyesuaian ukuran font khusus agar teks deskripsi panjang tidak merusak tata letak */
    .table-komoditas th,
    .table-komoditas td {
        font-size: 7.5px;
        padding: 5px 4px;
        vertical-align: top; /* Membuat teks berlapis rata atas agar rapi */
    }

    .kode-mono {
        font-family: monospace;
        font-weight: bold;
        color: #1d4ed8;
        background-color: #f1f5f9;
        padding: 2px 5px;
        border-radius: 4px;
        display: inline-block;
    }

    .meta-above {
        display: block;
        font-size: 6.5px;
        color: #4b5563;
        margin-bottom: 2px;
        font-weight: 500;
    }

    .nama-komoditas {
        display: block;
        font-size: 8px;
        font-weight: bold;
        color: #111827;
    }

    .deskripsi-below {
        display: block;
        margin-top: 2px;
        font-size: 6.5px;
        color: #6b7280;
        line-height: 1.2;
    }

    /* Badge Status Komoditas */
    .status-aktif {
        color: #16a34a;
        font-weight: bold;
    }
    .status-terbatas {
        color: #d97706;
        font-weight: bold;
    }
    .status-non-aktif {
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
    <h1 class="judul-laporan">Laporan Data Komoditas / Bahan Pokok</h1>

    <p class="periode">
        Periode: {{ now()->format('Y') }}
    </p>

    <table class="table-data table-komoditas">
        <thead>
            <tr>
                <th style="width: 35px;">No.</th>
                <th style="width: 110px;">Kode Komoditas</th>
                <th style="width: 410px;">Pusat Informasi Komoditas (Kategori, Nama & Deskripsi)</th>
                <th style="width: 95px;">Status</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($komoditas as $item)
                @php
                    // Normalisasi status komoditas untuk warna badge
                    $statusText = strtolower(trim($item->status_komoditas ?? ''));
                    
                    $statusClass = match ($statusText) {
                        'aktif' => 'status-aktif',
                        'terbatas' => 'status-terbatas',
                        'non-aktif', 'matikan' => 'status-non-aktif',
                        default => 'status-default',
                    };

                    $statusLabel = match ($statusText) {
                        'aktif' => 'Aktif',
                        'terbatas' => 'Terbatas',
                        'non-aktif', 'matikan' => 'Non-Aktif',
                        default => ucfirst($item->status_komoditas ?? '-'),
                    };
                @endphp
                <tr>
                    {{-- 1. Nomor Urut --}}
                    <td class="text-center" style="color: #6b7280;">
                        {{ $loop->iteration }}
                    </td>

                    {{-- 2. Kode Komoditas (Format Monospace) --}}
                    <td class="text-center">
                        <span class="kode-mono">
                            {{ $item->kode_komoditas ?? '-' }}
                        </span>
                    </td>

                    {{-- 3. Pusat Informasi Berlapis (Above -> Main -> Below) --}}
                    <td>
                        {{-- Atas (Kategori & Satuan) --}}
                        <span class="meta-above">
                            📦 Kategori: {{ $item->kategori ?? '-' }} | ⚖️ Satuan: {{ $item->satuan ?? '-' }}
                        </span>

                        {{-- Tengah (Nama Utama) --}}
                        <span class="nama-komoditas">
                            {{ $item->nama_komoditas ?? '-' }}
                        </span>

                        {{-- Bawah (Deskripsi Panjang Terbatasi) --}}
                        <span class="deskripsi-below">
                            @if($item->deskripsi)
                                💡 {{ Str::limit($item->deskripsi, 120, '...') }}
                            @else
                                Tidak ada deskripsi tambahan
                            @endif
                        </span>
                    </td>

                    {{-- 4. Status dengan Badge Kontras --}}
                    <td class="text-center">
                        <span class="{{ $statusClass }}">
                            {{ $statusLabel }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="no-data">
                        Data komoditas belum tersedia.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection