<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Cetak Peminjaman</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS (CDN) -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <style>
        .table-bordered td,
        .table-bordered th {
            border: 1px solid #000 !important;
        }

        .ttd-space {
            height: 80px;
        }

        .qr-code {
            position: absolute;
            bottom: 20px;
            right: 20px;
        }

        .header-logo {
            width: 80px;
        }

        @media print {
            body {
                -webkit-print-color-adjust: exact;
            }
        }
    </style>
</head>

<body>

    <div class="container mt-3 position-relative">

        <!-- Header -->
        <div class="text-center">
            <img src="{{ asset('img/Tekno.png') }}" class="header-logo" alt="Logo">
            <h5 class="mt-2 font-weight-bold">PEMINJAMAN RUANGAN & PERALATAN</h5>
        </div>

        <div class="row mt-2 mb-3">
            <div class="col-6">
                <p>Mulai :
                    {{ \Carbon\Carbon::parse($dataPeminjaman->waktu_peminjaman)->isoFormat(' D MMMM YYYY [pukul] HH:mm') }}
                </p>
                <p>Selesai :
                    {{ \Carbon\Carbon::parse($dataPeminjaman->waktu_pengembalian)->isoFormat(' D MMMM YYYY [pukul] HH:mm') }}
                </p>
                <p>Ruangan : {{ $dataPeminjaman->ruangan->nama_ruangan ?? '-' }}</p>
                <p>Kegiatan : {{ $dataPeminjaman->kegiatan }}</p>
            </div>
            <div class="col-6">
                <p>Nama Pengguna Ruangan : {{ $dataPeminjaman->user->name }}</p>
                <p>Prog. Studi : {{ $dataPeminjaman->user->prodi->nama }}</p>
                <p>NPM/No. Kartu Identitas : {{ $dataPeminjaman->user->username }}</p>
                <p>No. Telp/HP : {{ $dataPeminjaman->user->no_telepon ?? '-' }}</p>
            </div>
        </div>

        <!-- Tabel Barang -->
        <table class="table table-bordered">
            <thead class="text-center">
                <tr>
                    <th style="width: 5%;">No.</th>
                    <th>Nama Barang/Alat</th>
                    <th style="width: 15%;">Jumlah</th>
                    <th style="width: 15%;">Checklist</th>
                </tr>
            </thead>
            <tbody>

                @if ($dataPeminjaman->detail_peminjaman->isNotEmpty())
                    <td class="text-center">1</td>
                    <td>Ruangan beserta fasilitas</td>
                    <td></td>
                    <td></td>
                    @foreach ($dataPeminjaman->detail_peminjaman as $dataBarang)
                        <tr>
                            <td class="text-center">{{ $loop->iteration + 1 }}</td>
                            <td>{{ $dataBarang->barang->nama }}</td>
                            <td class="text-center">{{ $dataBarang->jml_barang }}</td>
                            <td></td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td class="text-center">1</td>
                        <td>Ruangan beserta fasilitas</td>
                        <td></td>
                        <td></td>
                    </tr>

                @endif
            </tbody>
        </table>

        <!-- Persetujuan -->
        <div class="row mt-3">
            @foreach ($dataPeminjaman->persetujuan_peminjaman as $item)
                <div class="col">
                    <p>
                        Menyetujui,
                        @if ($item->approval_role == 'baak')
                            Tata Usaha {{ $item->unit_kerja->kode }}<br>
                        @else
                            {{ $item->unitkerja->kode ?? $item->approval_role }}<br>
                        @endif
                        <img src="{{ asset('storage/tanda_tangan/' . $item->user->tanda_tangan) }}" alt="">
                        <strong>{{ $item->user->name }}</strong>
                    </p>
                </div>
            @endforeach
            <div class="col mt-2">
                <p>Peminjam Ruang</p>

                <img src="{{ asset('storage/tanda_tangan/' . $dataPeminjaman->user->tanda_tangan) }}" alt="">
                <p> <strong>{{ $dataPeminjaman->user->name }}</strong></p>
            </div>
        </div>

        <!-- Catatan -->
        <div class="mt-4">
            <p><strong>Catatan:</strong></p>
            <ol>
                <li>Lampirkan keterangan fasilitas yang sudah ditandatangani peminjam ruangan</li>
                <li>Formulir ini diserahkan pada saat peminjaman & pengembalian ruangan</li>
                <li>Barang/alat dikembalikan dalam kondisi baik & lengkap</li>
                <li>Apabila barang/alat di ruangan ini hilang, <strong>pengguna ruangan wajib mengganti</strong></li>
            </ol>
        </div>

        <!-- QR Code -->
        <div class="qr-code">
            {!! QrCode::size(100)->generate(encrypt($dataPeminjaman->id)) !!}
        </div>

    </div>

    <!-- Auto print -->
    <script>
        window.onload = function() {
            window.print();
        }
    </script>

</body>

</html>
