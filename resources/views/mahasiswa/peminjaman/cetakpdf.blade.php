<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Cetak Peminjaman - {{ $dataPeminjaman->kegiatan }}</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            line-height: 1.4;
        }

        .page-container {
            width: 100%;
            margin: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td,
        th {
            padding: 2px 4px;
            vertical-align: top;
        }

        .text-center {
            text-align: center;
        }

        .font-weight-bold {
            font-weight: bold;
        }

        /* Header */
        .header-logo {
            width: 80px;
        }

        .header-kop {
            text-align: center;
            line-height: 1.2;
        }

        .doc-code-box {
            border: 1px solid #000;
            padding: 5px;
            font-size: 9pt;
        }

        /* Judul Utama */
        .main-title {
            text-align: center;
            font-weight: bold;
            text-decoration: underline;
            font-size: 14pt;
            padding: 10px 0;
        }

        /* Tabel Detail Barang */
        .main-table,
        .main-table th,
        .main-table td {
            border: 1px solid #000;
        }

        .main-table thead {
            background-color: #f2f2f2;
        }

        .main-table th {
            font-weight: bold;
        }

        .main-table tr {
            height: 25px;
        }

        /* Memberi tinggi minimal pada setiap baris */

        /* Bagian Tanda Tangan */
        .signature-table {
            margin-top: 15px;
            text-align: center;
        }

        .signature-cell {
            height: 120px;
            /* Ruang kosong untuk TTD */
            vertical-align: bottom;
            /* Agar nama berada di bawah */
        }

        .signature-cell img {
            max-height: 60px;
            /* Batasi tinggi gambar TTD */
            display: block;
            margin: 0 auto 5px;
        }

        /* Catatan */
        .notes {
            font-size: 9pt;
            margin-top: 20px;
        }

        .notes ol {
            padding-left: 20px;
        }

        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>

<body>
    <div class="page-container">
        <table>
            <tr>
                <td style="width: 15%; text-align: right; padding-right: 10px;">
                    <img src="{{ asset('img/Tekno.png') }}" class="header-logo" alt="Logo">
                </td>
                <td class="header-kop" style="width: 70%;">
                    <div style="font-size: 14pt; font-weight:bold;">FORMULIR</div>
                    <div style="font-size: 16pt; font-weight:bold;">PEMINJAMAN RUANGAN & PERALATAN</div>
                </td>
                <td style="width: 15%;">
                    <div class="doc-code-box">
                        <strong>Kode Dok:</strong> F-RUT-026<br>
                        <strong>Revisi:</strong> 1
                    </div>
                </td>
            </tr>
        </table>

        <hr>

        <table style="margin-top: 15px;">
            <tr>
                <td style="width: 50%;">
                    <table>
                        <tr>
                            <td style="width: 120px;">Mulai</td>
                            <td>:
                                {{ \Carbon\Carbon::parse($dataPeminjaman->waktu_peminjaman)->isoFormat(' D MMMM YYYY HH:mm') }}
                            </td>
                        </tr>
                        <tr>
                            <td>Selesai</td>
                            <td>: {{ \Carbon\Carbon::parse($dataPeminjaman->waktu_pengembalian)->isoFormat(' D MMMM YYYY HH:mm') }}</td>
                        </tr>
                        <tr>
                            <td>Ruangan</td>
                            <td>: {{ $dataPeminjaman->ruangan?->nama_ruangan ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td>Kegiatan</td>
                            <td>: {{ $dataPeminjaman->kegiatan }}</td>
                        </tr>
                    </table>
                </td>
                <td style="width: 50%;">
                    <table>
                        <tr>
                            <td style="width: 180px;">Nama Pengguna Ruangan</td>
                            <td>: {{ $dataPeminjaman->user->name }}</td>
                        </tr>
                        <tr>
                            <td>Prog. Studi</td>
                            <td>: {{ $dataPeminjaman->user->prodi?->nama ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td>NPM/No. Kartu Identitas</td>
                            <td>: {{ $dataPeminjaman->user->username }}</td>
                        </tr>
                        <tr>
                            <td>No. Telp/HP</td>
                            <td>: {{ $dataPeminjaman->user->no_telepon ?? '-' }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <table class="main-table" style="margin-top: 15px;">
            <thead>
                <tr>
                    <th style="width: 5%;">No.</th>
                    <th>Nama Barang/Alat</th>
                    <th style="width: 15%;">Jumlah</th>
                    <th style="width: 20%;">Checklist</th>
                </tr>
            </thead>
            <tbody>
                @if ($dataPeminjaman->ruangan)
                    <tr>
                        <td class="text-center">1</td>
                        <td><b>Ruangan Beserta Fasilitas</b></td>
                        <td></td>
                        <td></td>
                    </tr>
                @endif
                @foreach ($dataPeminjaman->detail_peminjaman as $detail)
                    <tr>
                        <td class="text-center">{{ $loop->iteration + 1 }}</td>
                        <td>{{ $detail->barang->nama }}</td>
                        <td class="text-center">{{ $detail->jml_barang }}</td>
                        <td></td>
                    </tr>
                @endforeach
               
            </tbody>
        </table>

<table class="signature-table">
    <tr>
        {{-- Loop untuk setiap BAAK/Tata Usaha --}}
        @foreach($dataPeminjaman->persetujuan_peminjaman as $persetujuan)
            <td>
                <div>Tata Usaha {{ $persetujuan->unit_kerja->kode ?? '' }}</div>
                <div class="signature-cell">
                    @if($persetujuan->status == 'disetujui' && $persetujuan?->user?->tanda_tangan)
                        <img src="{{ asset('storage/tanda_tangan/' . $persetujuan->user->tanda_tangan) }}" alt="TTD">
                    @endif

                    @if($persetujuan->status == 'disetujui' && $persetujuan?->user)
                        <u>{{ $persetujuan->user->name }}</u>
                    @else
                        (.....................)
                    @endif
                </div>
            </td>
        @endforeach

        {{-- Sel untuk Peminjam --}}
        <td>
            <div>Peminjam</div>
            <div class="signature-cell">
                @if($dataPeminjaman->user->tanda_tangan)
                     <img src="{{ asset('storage/tanda_tangan/' . $dataPeminjaman->user->tanda_tangan) }}" alt="TTD">
                @endif
                <u>{{ $dataPeminjaman->user->name }}</u>
            </div>
        </td>
    </tr>
</table>

    </div>

    <script type="text/javascript">
        window.onload = function() {
            window.print();
        };
    </script>
</body>

</html>
