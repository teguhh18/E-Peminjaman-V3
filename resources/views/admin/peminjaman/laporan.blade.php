@extends('layouts.tabler-admin.master')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Report Peminjaman Ruangan</h3>
                </div>
                <div class="card-body">
                    <form id="reportForm" action="" method="post">
                        @csrf
                        <div class="form-group mb-2">
                            <label for="start_date">Start Date:</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" required>
                        </div>
                        <div class="form-group mb-2">
                            <label for="end_date">End Date:</label>
                            <input type="date" class="form-control" id="end_date" name="end_date" required>
                        </div>

                        <div class="form-group mb-2">
                            <label for="status" class="control-label">Status</label>
                            <select name="status" id="status" class="form-control">
                                <option value="">- Pilih Status -</option>
                                <option value="menunggu">Menunggu</option>
                                <option value="disetujui">Disetujui</option>
                                <option value="ditolak">Ditolak</option>
                                <option value="aktif">Aktif/Dipinjam</option>
                                <option value="selesai">Selesai</option>
                            </select>
                        </div>
                    </form>
                    <button type="button" class="btn btn-primary btn-sm" id="lihatDataBtn"><i class="fa fa-eye me-1"></i>Lihat Data</button>
                </div>
            </div>

        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <table class="table table-striped table-bordered " id="datatables">
                        <thead style="text-align: center !important">
                            <tr>
                                <th width="5%">No</th>
                                <th>Nama Peminjam</th>
                                <th>No Hp</th>
                                <th>Ruangan</th>
                                <th>Kegiatan</th>
                                <th>Waktu</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="odd gradeX ">
                            </tr>
                        </tbody>
                    </table>

                    {{-- <button type="" class="btn btn-primary">Cetak Laporan</button> --}}
                    <button type="button" class="btn btn-primary btn-sm" id="cetakLaporanBtn" onclick="cetakLaporan()"><i class="fa fa-print me-1"></i>Cetak
                        Laporan
                    </button>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            $('#lihatDataBtn').click(function() {
                var startDate = $('#start_date').val();
                var endDate = $('#end_date').val();
                if (startDate == '' && endDate == '') {
                    // alert('Pilih Stard Date dan End Date!!');
                    Swal.fire({
                        title: "Info!",
                        text: "Pilih Stard Date dan End Date!!",
                        icon: "error"
                    });
                } else {
                    var formData = $('#reportForm').serialize();

                    // Jika status tidak dipilih, hapus parameter status dari formData
                    if (!$('#status').val()) {
                        formData = formData.split('&').filter(function(item) {
                            return item.indexOf('status') === -1;
                        }).join('&');
                    }

                    $.get('{{ route('fetch.data.laporan') }}', formData, function(response) {
                        $('#datatables tbody').empty();
                        if (response.length > 0) {
                            $.each(response, function(index, data) {
                                var statusText = '';
                                switch (data.status_peminjaman) {
                                    case 'menunggu':
                                        statusText = 'Menunggu';
                                        break;
                                    case 'disetujui':
                                        statusText = 'Disetujui';
                                        break;
                                    case 'ditolak':
                                        statusText = 'Ditolak';
                                        break;
                                    case 'aktif':
                                        statusText = 'Aktif/Dipinjam';
                                        break;
                                    default:
                                        statusText = 'Selesai';
                                        break;
                                }
                                var waktuPinjam = new Date(data.waktu_peminjaman);
                                var waktuSelesai = new Date(data.waktu_pengembalian);
                                var formattedTime = waktuPinjam.toLocaleString('id-ID', {
                                    weekday: 'long',
                                    year: 'numeric',
                                    month: 'long',
                                    day: 'numeric',
                                    hour: '2-digit',
                                    minute: '2-digit'
                                });
                                var formattedEndTime = waktuSelesai.toLocaleString(
                                    'id-ID', {
                                        weekday: 'long',
                                        year: 'numeric',
                                        month: 'long',
                                        day: 'numeric',
                                        hour: '2-digit',
                                        minute: '2-digit'
                                    });
                                var waktuText = formattedTime + ' s/d ' +
                                    formattedEndTime;
                                $('#datatables tbody').append(`
                            <tr>
                                <td>${index + 1}</td>
                                <td>${data.user.name}</td>
                                <td>${data.no_peminjam}</td>
                                <td>${data.ruangan.nama_ruangan}</td>
                                <td>${data.kegiatan}</td>
                                <td>${waktuText}</td>
                                <td>${statusText}</td>
                            </tr>
                        `);
                            });
                        } else {
                            $('#datatables tbody').append(
                                '<tr><td colspan="6" class="text-center">Tidak ada data yang ditemukan.</td></tr>'
                            );
                        }
                    });
                }


            });
        });


        function cetakLaporan() {
            // Ambil nilai dari form
            var startDate = $('#start_date').val();
            var endDate = $('#end_date').val();
            var status = $('#status').val();

            // Bangun URL cetak laporan
            var cetakUrl = '{{ route('admin.cetak.laporan') }}?start_date=' + startDate + '&end_date=' + endDate;

            // Jika status dipilih, tambahkan ke URL
            if (status) {
                cetakUrl += '&status=' + status;
            }

            // Buka URL dalam tab baru
            window.open(cetakUrl, '_blank');
        }
    </script>
@endpush
