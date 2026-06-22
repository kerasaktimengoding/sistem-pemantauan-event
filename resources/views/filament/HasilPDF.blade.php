use Illuminate\Support\Str;
@extends('filament.pdf.layouts.main')

@section('title', 'Laporan Hasil Evaluasi Pelatihan UMKM')

@section('styles')
    /* Desain khusus tabel penilaian/evaluasi */
    .table-evaluasi th,
    .table-evaluasi td {
        font-size: 7.5px;
        padding: 6px 4px;
        vertical-align: top;
    }

    .text-mono {
        font-family: monospace;
    }

    .nama-peserta {
        display: block;
        font-size: 8.5px;
        font-weight: bold;
        color: #111827;
    }

    .sub-info {
        display: block;
        margin-top: 2px;
        font-size: 6.3px;
        color: #4b5563;
        line-height: 1.2;
    }

    .sub-kkm {
        display: block;
        margin-bottom: 2px;
        font-size: 6px;
        color: #9ca3af;
        text-align: center;
    }

    /* Indikator Tren Progres */
    .tren-naik {
        color: #16a34a;
        font-size: 6.5px;
        display: block;
        margin-top: 1px;
    }
    .tren-turun {
        color: #dc2626;
        font-size: 6.5px;
        display: block;
        margin-top: 1px;
    }

    /* Pewarnaan Nilai Akhir Berbasis KKM */
    .nilai-success { color: #16a34a; font-weight: bold; }
    .nilai-info { color: #2563eb; font-weight: bold; }
    .nilai-warning { color: #d97706; font-weight: bold; }
    .nilai-danger { color: #dc2626; font-weight: bold; }

    /* Hasil Kelulusan Badge Teks */
    .status-lulus {
        color: #16a34a;
        font-weight: bold;
    }
    .status-gagal {
        color: #dc2626;
        font-weight: bold;
    }
    .status-remedial {
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
    <h1 class="judul-laporan">Laporan Hasil Evaluasi & Kelulusan Pelatihan UMKM</h1>

    <p class="periode">
        Tanggal Evaluasi Cetak: {{ now()->translatedFormat('d F Y') }}
    </p>

    <table class="table-data table-evaluasi">
        <thead>
            <tr>
                <th style="width: 30px;">No.</th>
                <th style="width: 210px;">Informasi Peserta</th>
                <th style="width: 75px; text-align: center;">Pre-Test</th>
                <th style="width: 90px; text-align: center;">Post-Test</th>
                <th style="width: 75px; text-align: center;">Nilai Akhir</th>
                <th style="width: 90px; text-align: center;">Hasil Akhir</th>
                <th style="width: 130px;">Catatan Evaluasi</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($hasils as $hasil)
                @php
                    // 1. Hitung Selisih Tren Nilai
                    $pre = (float) $hasil->nilai_pretest;
                    $post = (float) $hasil->nilai_posttest;
                    $selisih = $post - $pre;

                    // 2. Warna Dinamis Nilai Akhir (Skala Kondisional)
                    $na = (float) $hasil->nilai_akhir;
                    $nilaiClass = match (true) {
                        $na >= 85 => 'nilai-success',
                        $na >= 75 => 'nilai-info',
                        $na >= 60 => 'nilai-warning',
                        default   => 'nilai-danger',
                    };

                    // 3. Status Kelulusan
                    $statusKelulusan = trim($hasil->status_kelulusan ?? '');
                    $statusClass = match ($statusKelulusan) {
                        'Lulus'       => 'status-lulus',
                        'Tidak Lulus' => 'status-gagal',
                        'Remedial'    => 'status-remedial',
                        default       => '',
                    };
                @endphp
                <tr>
                    {{-- 1. Nomor Urut --}}
                    <td class="text-center" style="color: #0f1013ff;">
                        {{ $loop->iteration }}
                    </td>

                    {{-- 2. Profil & Identitas Peserta --}}
                    <td>
                        <span class="nama-peserta"> {{ $hasil->pesertaevent->nama_peserta ?? '-' }}</span>
                        <span class="sub-info text-mono">ID Pelatihan: {{ $hasil->kode_hasil_pelatihan ?? '-' }}</span>
                    </td>

                    {{-- 3. Rekapitulasi Nilai Pre-Test --}}
                    <td class="text-center text-mono">
                        <span class="sub-kkm" style="display: block; color: #000000ff;">Awal</span>
                        <span style="color: #0c0f15ff;">{{ number_format($pre, 2) }}</span>
                    </td>

                    {{-- 4. Rekapitulasi Nilai Post-Test & Tren Kenaikan --}}
                    <td class="text-center text-mono">
                        <span style="color: #2563eb; font-weight: 600;">{{ number_format($post, 2) }}</span>
                        @if($selisih >= 0)
                            <span class="tren-naik"> Naik (+{{ number_format($selisih, 2) }})</span>
                        @else
                            <span class="tren-turun"> Turun ({{ number_format($selisih, 2) }})</span>
                        @endif
                    </td>

                    {{-- 5. Nilai Akhir Berwarna Kontras (KKM: 75.00) --}}
                    <td class="text-center text-mono">
                        <span class="sub-kkm">KKM: 75.00</span>
                        <span class="{{ $nilaiClass }}">{{ number_format($na, 2) }}</span>
                    </td>

                    {{-- 6. Status Kelulusan Teks --}}
                    <td class="text-center">
                        <span class="{{ $statusClass }}" style="font-size: 8px;">
                            {{ strtoupper($statusKelulusan) }}
                        </span>
                    </td>

                    {{-- 7. Catatan Ringkas Evaluasi --}}
                    <td>
                        <span style="color: #6b7280; font-size: 6.8px; line-height: 1.2;">
                            @if($hasil->catatan)
                                {{ Str::limit($hasil->catatan, 45, '...') }}
                            @else
                                <span style="color: #d1d5db; font-style: italic;">Tidak ada catatan</span>
                            @endif
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="no-data">
                        Data rekapitulasi hasil evaluasi pelatihan belum terekam.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection