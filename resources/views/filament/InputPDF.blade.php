use Illuminate\Support\Str;
@extends('filament.pdf.layouts.main')

@section('title', 'Laporan Pencatatan Harga Bahan Pokok')

@section('styles')


    /* Khusus laporan transaksi finansial harga, font sedikit diperketat */
    .table-harga th,
    .table-harga td {
    font-size: 7.2px;
    padding: 5px 3px;
    vertical-align: top;
    }

    .text-mono {
    font-family: monospace;
    }

    .text-primary {
    color: #2563eb;
    font-weight: bold;
    }

    .text-harga {
    font-family: monospace;
    font-weight: 800; /* ExtraBold */
    color: #16a34a; /* Hijau sukses finansial */
    text-align: right;
    }

    .sub-info {
    display: block;
    margin-top: 2px;
    font-size: 6.3px;
    color: #4b5563;
    line-height: 1.2;
    }

    .nama-komoditas {
    font-weight: bold;
    color: #111827;
    font-size: 7.5px;
    }

    /* System Badge Asal Sumber Data */
    .badge-sumber {
    display: inline-block;
    padding: 2px 4px;
    border-radius: 3px;
    font-size: 6.5px;
    font-weight: bold;
    text-align: center;
    }
    .sumber-info { background-color: #e0f2fe; color: #0369a1; } /* Dinas / Resmi */
    .sumber-warning { background-color: #fef3c7; color: #b45309; } /* Pedagang / Pasar */
    .sumber-success { background-color: #dcfce7; color: #15803d; } /* Masyarakat / Online */
    .sumber-gray { background-color: #f3f4f6; color: #4b5563; } /* Netral */

    .keterangan-text {
    font-style: italic;
    color: #6b7280;
    font-size: 6.5px;
    line-height: 1.2;
    }

    .no-data {
    padding: 15px !important;
    text-align: center;
    font-style: italic;
    color: #6b7280;
    }
@endsection

@section('content')
    <h1 class="judul-laporan">Laporan Pencatatan Harga Bahan Pokok</h1>

    <p class="periode">
        Periode Ekstraksi: {{ now()->translatedFormat('d F Y') }}
    </p>

    <table class="table-data table-harga">
        <thead>
            <tr>
                <th style="width: 25px;">No.</th>
                <th style="width: 100px;">Waktu & Kode Transaksi</th>
                <th style="width: 125px;">Komoditas Produk</th>
                <th style="width: 90px; text-align: right;">Harga Jual Resmi</th>
                <th style="width: 125px;">Lokasi Pasar & Wilayah</th>
                <th style="width: 95px;">Petugas Enumerator</th>
                <th style="width: 65px;">Asal Sumber</th>
                <th style="width: 95px;">Catatan Lapangan</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($inputs as $input)
                @php
                    // Logika pewarnaan dinamis kebal case-insensitive (Case Insensitive Match)
                    $cleanedState = strtolower(trim($input->sumber_data ?? ''));

                    $sumberClass = match (true) {
                        in_array($cleanedState, ['dinas', 'pemerintah', 'resmi']) => 'sumber-info',
                        in_array($cleanedState, ['pedagang', 'pasar', 'primer']) => 'sumber-warning',
                        in_array($cleanedState, ['masyarakat', 'online']) => 'sumber-success',
                        default => 'sumber-gray',
                    };
                @endphp
                <tr>
                    {{-- 1. Nomor Urut --}}
                    <td class="text-center" style="color: #6b7280;">
                        {{ $loop->iteration }}
                    </td>

                    {{-- 2. Waktu & Kode Transaksi --}}
                    <td>
                        <span class="text-primary text-mono">
                            
                            {{ $input->tanggal_input ? \Carbon\Carbon::parse($input->tanggal_input)->translatedFormat('d M Y') : '-' }}
                        </span>
                        <span class="sub-info text-mono">
                            ID: {{ $input->kode_input_harga ?? '-' }}
                        </span>
                    </td>

                    {{-- 3. Komoditas Produk + Satuan --}}
                    <td>
                        <span class="nama-komoditas"> {{ $input->komoditas->nama_komoditas ?? '-' }}</span>
                        <span class="sub-info">Satuan Takaran: {{ $input->komoditas->satuan ?? '-' }}</span>
                    </td>

                    {{-- 4. Nominal Harga Jual Resmi (Rata Kanan & Monospace) --}}
                    <td class="text-harga">
                        Rp {{ number_format($input->harga ?? 0, 0, ',', '.') }}
                    </td>

                    {{-- 5. Lokasi Pasar & Wilayah --}}
                    <td>
                        <span style="font-weight: 500; color: #1f2937;">📍 {{ $input->pasar->nama_pasar ?? '-' }}</span>
                        <span class="sub-info">Wilayah : {{ $input->desa->nama_desa ?? '-' }}</span>
                    </td>

                    {{-- 6. Petugas Enumerator --}}
                    <td>
                        @if($input->pegawai)
                            <span style="color: #374151;">👤 {{ $input->pegawai->nama_pegawai }}</span>
                        @else
                            <span class="text-muted" style="font-style: italic; color: #9ca3af;">Bukan Pegawai Tetap</span>
                        @endif
                    </td>

                    {{-- 7. Asal Sumber Data (Formatted Uppercase Badge) --}}
                    <td class="text-center">
                        <span class="badge-sumber {{ $sumberClass }}">
                            {{ strtoupper(trim($input->sumber_data ?? 'NETRAL')) }}
                        </span>
                    </td>

                    {{-- 8. Catatan Lapangan (Miring/Italic) --}}
                    <td>
                        <span class="keterangan-text">
                            @if($input->keterangan)
                                {{ Str::limit($input->keterangan, 50, '...') }}
                            @else
                                Tidak ada catatan
                            @endif
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="no-data">
                        Data rekapitulasi input harga komoditas belum tersedia.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection