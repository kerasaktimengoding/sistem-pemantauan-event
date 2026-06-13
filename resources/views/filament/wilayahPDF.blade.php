<!DOCTYPE html> 
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Data Wilayah - Kabupaten Banjar</title>
    <style>
        @page {
            size: A4 portrait; 
            margin: 1cm;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            line-height: 1.2;
            color: #333;
            margin: 0;
            padding: 10px;
        }

        .container {
            width: 100%;
        }

        /* Kop Surat */
        .kop-surat {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 5px solid #000;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }

        .logo {
            width: 70px;
            height: auto;
        }

        .konten-kop {
            text-align: center;
            flex-grow: 1;
        }

        .konten-kop h1 {
            margin: 0;
            font-size: 16px;
            text-transform: uppercase;
        }

        .konten-kop h2 {
            margin: 0;
            font-size: 18px;
            text-transform: uppercase;
            font-weight: bold;
        }

        .konten-kop p {
            margin: 0;
            font-size: 10px;
        }

        .tanggal {
            text-align: right;
            font-size: 12px;
            margin-bottom: 15px;
        }

        .judul-laporan {
            text-align: center;
            text-decoration: underline;
            text-transform: uppercase;
            font-size: 16px;
            margin-bottom: 5px;
        }

        /* Tabel Data */
        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        table th,
        table td {
            border: 1px solid #000;
            padding: 8px 5px;
            font-size: 11px; /* Ukuran teks sedikit lebih besar karena kolom lebih sedikit */
            word-wrap: break-word;
            vertical-align: middle;
        }

        table th {
            background-color: #f2f2f2;
            text-align: center;
            text-transform: uppercase;
            font-weight: bold;
        }

        .text-center {
            text-align: center;
        }

        /* Badge Tipe Wilayah */
        .badge {
            padding: 3px 8px;
            border-radius: 10px;
            font-size: 9px;
            text-transform: uppercase;
            font-weight: bold;
            color: white;
            display: inline-block;
        }

        .badge-info { background-color: #17a2b8; }    /* Blue for Kecamatan */
        .badge-success { background-color: #28a745; } /* Green for Desa */
        .badge-warning { background-color: #ffc107; color: #000; } /* Yellow for Kelurahan */
        .badge-secondary { background-color: #6c757d; }

        /* Footer Tanda Tangan */
        .footer {
            margin-top: 40px;
            text-align: right;
            width: 100%;
        }

        .footer p {
            margin: 0;
            font-size: 12px;
        }

        .footer-signature {
            margin-top: 30px;
            border-top: 2px solid #000;
            padding-top: 10px;
            width: 50%;
            margin-left: 50%;
            text-align: right;
        }

        .footer-signature p {
            margin: 0;
            padding-top: 10px;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="kop-surat">
            <img src="{{ public_path('image/logo.png') }}" alt="Logo" class="logo">
            <div class="konten-kop">
                <h1>Pemerintah Kabupaten Banjar</h1>
                <h2>Dinas Koperasi Usaha Mikro Perindustrian Dan Perdagangan</h2>
                <p>Jl. Perwira No. 44 G, Telp. (0511) - 6750417 Fax. (0511) - 6750418</p>
                <p>Martapura Kode Pos 70613</p>
            </div>
        </div>

        <div class="tanggal">
            Martapura, {{ date('d F Y') }}
        </div>

        <h1 class="judul-laporan">Laporan Data Wilayah</h1>
        <p class="text-center" style="font-size: 11px; margin-top: 0;">Kabupaten Banjar - Periode {{ date('Y') }}</p>

        <table>
            <thead>
                <tr>
                    <th width="40px">No</th>
                    <th>Kode Wilayah</th>
                    <th>Nama Wilayah</th>
                    <th>Tipe Wilayah</th>
                    <th>Kode Pos</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $no = 1;
                @endphp

                @foreach ($wilayahs as $wilayah)
                    <tr>
                        <td class="text-center">{{ $no++ }}</td>
                        <td class="text-center" style="font-weight: bold;">{{ $wilayah->kode_wilayah }}</td>
                        <td>{{ $wilayah->nama_wilayah }}</td>
                        <td class="text-center">
                            @php
                                $tipeClass = match($wilayah->tipe_wilayah) {
                                    'Kecamatan' => 'badge-info',
                                    'Desa' => 'badge-success',
                                    'Kelurahan' => 'badge-warning',
                                    default => 'badge-secondary',
                                };
                            @endphp
                            <span class="badge {{ $tipeClass }}">
                                {{ $wilayah->tipe_wilayah }}
                            </span>
                        </td>
                        <td class="text-center">{{ $wilayah->kode_pos ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

         <div class="footer">
            <div class="footer-right">
                <p>Kepala Dinas Koperasi Usaha Mikro,</p>
                <p>Perindustrian dan Perdagangan</p>
                <p>Kabupaten Banjar</p>
            </div>
        </div>

        <div class="footer-signature">
            <p>_________________________</p>
            <p>I Gusti Samiran Akbar, S.Sos,</p>
            <p>Pembina Utama Muda</p>
            <p>NIP: 1234567890</p>
        </div>
    </div>

</body>

</html>