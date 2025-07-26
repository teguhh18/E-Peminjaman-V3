@extends('layouts.tabler-admin.master')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div id="respon">
                @if (session()->has('msg'))
                    <div class="alert alert-important {{ session('class') }} alert-dismissible" role="alert">
                        <div class="d-flex">
                            <div></div>
                            <div>{{ session('msg') }}</div>
                        </div>
                        <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="row">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Data Booking Ruangan</div>
                <div class="card-actions">
                    @if (auth()->user()->level === 'admin')
                        <a href="{{ route('admin.peminjaman.create') }}" class="btn btn-sm btn-success btn-add">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-category-plus">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M4 4h6v6h-6zm10 0h6v6h-6zm-10 10h6v6h-6zm10 3h6m-3 -3v6" />
                            </svg>
                            Tambah Peminjaman
                        </a>
                    @endif
                    <a href="{{ route('scan.pinjamRuangan') }}" class="btn btn-sm btn-primary"> <i class="fa fa-qrcode"></i>
                        &nbsp; Scan QrCode</a>

                </div>
            </div>
            <div class="card-body">
                <div class="table-primary">
                    @if (auth()->user()->level === 'admin')
                        @include('admin.booking.table.admin')
                    @elseif(auth()->user()->level === 'kerumahtanggaan')
                        @include('admin.booking.table.kerumahtanggaan')
                    @elseif(auth()->user()->level === 'kaprodi')
                        @include('admin.booking.table.kaprodi')
                    @else
                        @include('admin.booking.table.baak')
                    @endif
                </div>
            </div>
        </div>
    </div>


    <div id="tempat-modal"></div>
@endsection
@push('js')
    <script>
        setTimeout(function() {
            document.getElementById('respon').innerHTML = '';
        }, 2000);
        $(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });


            // Tombol Konfirmasi/Approve Peminjaman (Baak/Unitkerja, Kaprodi, Kerumahtanggan)
            $(document).on("click", ".btn-confirm", function() {
                var id = $(this).attr("data-id");
                // console.log(id);
                var url = "{{ route('admin.booking.show', ':id_data') }}";
                url = url.replace(":id_data", id);
                $.ajax({
                        method: "GET",
                        url: url,
                    })
                    .done(function(data) {
                        $('#tempat-modal').html(data.html);
                        $('#modal_show').modal('show');
                    })
            })

            // Tombol Detail Barang
            $(document).on("click", ".btn-detail", function() {
                var id = $(this).attr("data-id");
                var url = "{{ route('admin.booking.detail', ':id_data') }}";
                url = url.replace(":id_data", id);
                $.ajax({
                        method: "GET",
                        url: url,
                    })
                    .done(function(data) {
                        $('#tempat-modal').html(data.html);
                        $('#modal_detail').modal('show');
                    })
            })

            // Tombol Status Barang
            $(document).on("click", "#btn-status-barang", function() {
                var id = $(this).attr("data-id");
                var url = "{{ route('admin.pengembalian.edit', ':id_data') }}";
                url = url.replace(":id_data", id);
                $.ajax({
                        method: "GET",
                        url: url,
                    })
                    .done(function(data) {
                        $('#tempat-modal').html(data.html);
                        $('#status_barang').modal('show');
                    })
            })
            // Tombol Status Ruangan
            $(document).on("click", "#btn-status-ruangan", function() {
                var id = $(this).attr("data-id");
                var url = "{{ route('admin.pengembalian.show', ':id_data') }}";
                url = url.replace(":id_data", id);
                $.ajax({
                        method: "GET",
                        url: url,
                    })
                    .done(function(data) {
                        $('#tempat-modal').html(data.html);
                        $('#status_barang').modal('show');
                    })
            })

            // Tombol Konfirmasi/Approve untuk Admin
            $(document).on("click", "#btn-konfirmasi", function() {
                var id = $(this).attr("data-id");
                // console.log(id);
                var url = "{{ route('admin.booking.konfirmasi', ':id_data') }}";
                url = url.replace(":id_data", id);
                $.ajax({
                        method: "GET",
                        url: url,
                    })
                    .done(function(data) {
                        $('#tempat-modal').html(data.html);
                        $('#modal_show').modal('show');
                    })
            })

            $(document).on("click", "#btn-delete", function() {
                var id = $(this).attr("data-id");
                var url = "{{ route('admin.booking.modal-delete', ':id_data') }}";
                url = url.replace(":id_data", id);
                $.ajax({
                        method: "GET",
                        url: url,
                    })
                    .done(function(data) {
                        $('#tempat-modal').html(data.html);
                        $('#modal_show').modal('show');
                    })
            })
        });
    </script>
@endpush
