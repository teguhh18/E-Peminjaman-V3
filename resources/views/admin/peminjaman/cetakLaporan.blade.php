<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Peminjaman</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container-fluid">
        <!-- Logo Instansi -->
        <div class="row">
            <div class="col-12">
                <img src="{{ asset('img/logo.png') }}" alt="Logo Instansi" class="img-fluid" style="max-width: 200px;">
            </div>
        </div>

        <!-- Head Judul View -->
        <div class="row mt-4">
            <div class="col-12 text-center">
                <h1>Laporan Peminjaman</h1>
            </div>
        </div>
    </div>

    <!-- Tabel Data Peminjaman -->
    <div class="container mt-3">
        <div class="row">
            <div class="col-12">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Peminjam</th>
                                <th>No Hp</th>
                                <th>Ruangan</th>
                                <th>Kegiatan</th>
                                <th>Waktu</th>
                                <th>Barang</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dataPeminjaman as $index => $peminjaman)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $peminjaman['user']['name'] }}</td>
                                    <td>{{ $peminjaman['no_peminjam'] }}</td>
                                    <td>{{ $peminjaman['ruangan']['nama_ruangan'] ?? '-' }}</td>
                                    <td>{{ $peminjaman['kegiatan'] }}</td>
                                    <td>
                                        <span> Mulai : 
                                            {{ \Carbon\Carbon::parse($peminjaman['waktu_peminjaman'])->isoFormat(' D MMMM YYYY  HH:mm') }}
                                        </span> <br>
                                        <span> Selesai : 
                                            {{ \Carbon\Carbon::parse($peminjaman['waktu_pengembalian'])->isoFormat(' D MMMM YYYY  HH:mm') }}
                                        </span> <br>
                                    </td>
                                    <td>
                                        @forelse ($peminjaman['detail_peminjaman'] as $detail)
                                                <li>
                                                    {{ $detail['barang']['nama'] }}
                                                </li>
                                        @empty
                                        -
                                        @endforelse
                                    </td>
                                    <td>
                                        @switch($peminjaman['status_peminjaman'])
                                            @case('menunggu')
                                                Menunggu Konfirmasi
                                            @break

                                            @case('disetujui')
                                                Disetujui
                                            @break

                                            @case('ditolak')
                                                Ditolak
                                            @break

                                            @case('aktif')
                                                Aktif/Dipinjam
                                            @break

                                            @default
                                                Selesai
                                        @endswitch
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
<script>
    window.onload = function() {
        window.print();
    };
</script>

</html>
