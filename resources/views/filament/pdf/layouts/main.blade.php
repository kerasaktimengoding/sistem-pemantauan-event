```blade
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <title>@yield('title', 'Laporan PDF')</title>

    <style>
        @page {
            size: A4 landscape;
            margin: 1cm;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
            font-size: 11px;
            line-height: 1.3;
        }

        .container {
            width: 100%;
        }

        /* Kop surat */
        .kop-surat {
            width: 100%;
            border-bottom: 4px solid #000;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }

        .kop-table {
            width: 100%;
            border-collapse: collapse;
        }

        .kop-table td {
            border: none;
            vertical-align: middle;
        }

        .logo-box {
            width: 90px;
            text-align: center;
        }

        .logo {
            width: 70px;
            height: auto;
        }

        .konten-kop {
            text-align: center;
        }

        .konten-kop h1 {
            margin: 0;
            font-size: 16px;
            text-transform: uppercase;
            font-weight: normal;
        }

        .konten-kop h2 {
            margin: 2px 0;
            font-size: 18px;
            text-transform: uppercase;
            font-weight: bold;
        }

        .konten-kop p {
            margin: 1px 0;
            font-size: 10px;
        }

        /* Tanggal */
        .tanggal {
            text-align: right;
            font-size: 11px;
            margin-bottom: 15px;
        }

        /* Judul laporan */
        .judul-laporan {
            text-align: center;
            text-transform: uppercase;
            text-decoration: underline;
            font-size: 16px;
            margin: 0 0 5px;
        }

        .periode {
            text-align: center;
            font-size: 11px;
            margin: 0 0 15px;
        }

        /* Tabel laporan */
        .table-data {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .table-data th,
        .table-data td {
            border: 1px solid #000;
            padding: 5px 3px;
            font-size: 8px;
            vertical-align: middle;
            word-wrap: break-word;
        }

        .table-data th {
            background-color: #f2f2f2;
            text-align: center;
            text-transform: uppercase;
            font-weight: bold;
        }

        /* Posisi teks */
        .text-center {
            text-align: center;
        }

        .text-left {
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        /* Badge status */
        .badge {
            display: inline-block;
            padding: 3px 6px;
            border-radius: 8px;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
            color: #fff;
        }

        .badge-success {
            background-color: #28a745;
        }

        .badge-danger {
            background-color: #dc3545;
        }

        .badge-warning {
            background-color: #ffc107;
            color: #000;
        }

        .badge-secondary {
            background-color: #6c757d;
        }

        /* Tanda tangan */
        .signature-table {
            width: 100%;
            margin-top: 35px;
            border-collapse: collapse;
        }

        .signature-table td {
            border: none;
            font-size: 11px;
            vertical-align: top;
        }

        .signature-space {
            height: 55px;
        }

        .signature-name {
            font-weight: bold;
            text-decoration: underline;
        }

        /* CSS tambahan dari file laporan */
        @yield('styles')
    </style>
</head>

<body>
    <div class="container">

        {{-- Kop surat --}}
        <div class="kop-surat">
            <table class="kop-table">
                <tr>
                    <td class="logo-box">
                        <img
                            src="{{ public_path('image/logo.png') }}"
                            alt="Logo Kabupaten Banjar"
                            class="logo"
                        >
                    </td>

                    <td class="konten-kop">
                        <h1>Pemerintah Kabupaten Banjar</h1>

                        <h2>
                            Dinas Koperasi Usaha Mikro Perindustrian dan Perdagangan
                        </h2>

                        <p>
                            Jl. Perwira No. 44 G, Telp. (0511) 6750417,
                            Fax. (0511) 6750418
                        </p>

                        <p>Martapura, Kode Pos 70613</p>
                    </td>

                    <td style="width: 90px;"></td>
                </tr>
            </table>
        </div>

        {{-- Tanggal cetak --}}
        <div class="tanggal">
            Martapura, {{ now()->translatedFormat('d F Y') }}
        </div>

        {{-- Isi laporan dari file lain --}}
        @yield('content')

        {{-- Tanda tangan --}}
        <table class="signature-table">
            <tr>
                <td style="width: 55%;"></td>

                <td class="text-center">
                    <p>Kepala Dinas Koperasi Usaha Mikro,</p>
                    <p>Perindustrian dan Perdagangan</p>
                    <p>Kabupaten Banjar</p>

                    <div class="signature-space"></div>

                    <p class="signature-name">
                        I Gusti Samiran Akbar, S.Sos.
                    </p>

                    <p>Pembina Utama Muda</p>
                    <p>NIP. 1234567890</p>
                </td>
            </tr>
        </table>

    </div>
</body>

</html>
```
