{{-- @extends('templateAdminLTE/home') --}}
@extends('layouts.tabler-admin.master')
@section('sub-breadcrumb', 'Filter Barang')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div id="respon">

            </div>
            <div class="panel">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-sm-12 card-tools text-right">
                            <a href="{{ route('admin.barang.index') }}" class="btn btn-xs btn-warning btn-add">
                                <i class="fa fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </div>
                </div>
                <div class="panel-body">

                    <form class="form-horizontal" enctype="multipart/form-data">
                        @csrf


                        <div class="row">
                            <div class="col-md-6">
                                <label for="gedung_id" class="control-label">Gedung</label>
                                <select name="gedung_id" id="gedung_id" class="form-control">
                                    <option value="">-Pilih Gedung-</option>
                                    @foreach ($dataGedung as $gedung)
                                        <option value="{{ $gedung->id }}" {{ $gedung->id == old('gedung_id') }}>
                                            {{ $gedung->nama }}</option>
                                    @endforeach
                                </select>

                            </div>

                            <div class="col-md-6">
                                <label for="ruangan_id" class="control-label">Ruangan</label>
                                <select name="ruangan_id" id="ruangan_id" class="form-control">
                                    <option value="">-Pilih Ruangan-</option>
                                </select>
                            </div>

                        </div>


                        <div class="row " style="margin-top: 8px">
                            <div class="col-md-4">
                                <button type="submit" id="tampilkan-barang" class="btn btn-primary"><i
                                        class="fa fa-eye"></i> Tampilkan Barang</button>
                            </div>
                        </div>


                    </form>

                </div>



            </div>
            <table class="table table-striped table-bordered " id="datatables">
                <thead style="text-align: center !important">
                    <tr>
                        <th width="5%">No</th>
                        <th>Kode Barang</th>
                        <th>Label Aset</th>
                        <th>Jumlah Aset</th>
                        <th>Unit Penanggung Jawab</th>
                        <th>Kondisi</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>


                    <tr class="odd gradeX ">


                    </tr>

                </tbody>
            </table>
            <div class="row " style="margin-top: 8px">
                <div class="col-md-4">
                    <button type="button" id="cetak-qrcode" class="btn btn-primary"><i
                            class="fa fa-qrcode"></i>
                        Cetak QrCode</button>
                </div>
            </div>
        </div>
    </div>

    <div id="tempat-modal"></div>

    @push('js')
        <script>
            setTimeout(function() {
                document.getElementById('respon').innerHTML = '';
            }, 3000);
        </script>




        <script>
            $(document).ready(function() {
                $('#gedung_id').change(function() {
                    var gedungId = $(this).val();

                    $.ajax({
                        url: '{{ route('admin.barang.filterRuanganByGedung', ':gedung_id') }}'.replace(
                            ':gedung_id', gedungId),
                        type: 'GET',
                        success: function(response) {
                            $('#ruangan_id').empty();
                            $('#ruangan_id').append('<option value="">Pilih Ruangan</option>');

                            $.each(response, function(key, value) {
                                $('#ruangan_id').append('<option value="' + value.id +
                                    '">' + value.nama_ruangan + '</option>');
                            });
                        }
                    });
                });


                // Untuk menampilkan data barang
                $('#tampilkan-barang').click(function(event) {
                    event.preventDefault(); // Mencegah form untuk langsung melakukan submit


                    var ruanganId = $('#ruangan_id').val();

                    $.ajax({
                        url: '{{ route('admin.barang.filter') }}', // Endpoint untuk menampilkan data barang berdasarkan ruangan
                        type: 'GET',
                        data: {

                            ruangan_id: ruanganId
                        },
                        success: function(response) {
                            $('#datatables tbody')
                                .empty(); // Kosongkan tabel sebelum memasukkan data baru

                            // Loop untuk setiap data barang yang diterima
                            $.each(response, function(index, barang) {
                                var newRow = '<tr>' +
                                    '<td>' + (index + 1) + '</td>' +

                                    '<td class="center"><b><u>' + barang.kode +
                                    '</u></b></td>' +
                                    '<td class="center">' + barang.nama + '</td>' +
                                    '<td>' + barang.jumlah + '</td>' +
                                    '<td>' + barang.penanggung_jawab + '</td>' +
                                    '<td>' + barang.kondisi + '</td>' +
                                    '<td>' + barang.status + '</td>' +
                                    '</tr>';
                                $('#datatables tbody').append(
                                    newRow); // Tambahkan baris baru ke tabel
                            });
                        }
                    });
                });


                // Fungsi untuk cetak QrCode
                $('#cetak-qrcode').click(function() {
                    var ruanganId = $('#ruangan_id').val(); // Ambil id ruangan yang dipilih

                    // Pastikan ruanganId tidak kosong
                    if (ruanganId) {

                        var url = '{{ route('admin.barang.QrCode', ':ruangan_id') }}'.replace(':ruangan_id',
                            ruanganId);
                        window.open(url, '_blank');
                    } else {
                        // Tampilkan pesan kesalahan jika ruanganId kosong
                        alert('Pilih ruangan terlebih dahulu!');
                    }


                });

            });
        </script>
    @endpush
@endsection
