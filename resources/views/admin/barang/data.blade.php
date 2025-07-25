@extends('layouts.tabler-admin.master')
@section('content')
    <div class="row">
        <div id="respon">
            @if (session()->has('msg'))
                <div class="alert {{ session('class') }} alert-dark">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    {{ session('msg') }}
                </div>
            @endif
        </div>
        <div class="card">
            <div class="card-header">
                <div class="card-title">Data Barang</div>
                <div class="card-actions">

                    <a href="{{ route('admin.barang.create') }}" class="btn btn-sm btn-primary btn-add">
                        <i class="fa fa-plus"></i>&nbsp; Tambah Data
                    </a>

                    <a href="{{ route('admin.barang.filter') }}" class="btn btn-sm btn-primary btn-add">
                        <i class="fa fa-qrcode"></i>&nbsp; Cetak QR Code
                    </a>
                    <a href="{{ route('excelExport') }}" class="btn btn-sm btn-primary btn-add">
                        <i class="fa fa-download"></i>&nbsp; Export Data
                    </a>

                </div>
            </div>
            <div class="card-body">
                <div class="table-light">
                    @include('admin.barang.table')
                </div>
            </div>
        </div>
    </div>

    <div id="tempat-modal"></div>
@endsection
@push('js')
    <script>
        $(document).ready(function() {

        });
        setTimeout(function() {
            document.getElementById('respon').innerHTML = '';
        }, 2000);
        $(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $(document).on("click", ".btn-delete", function() {
                var id = $(this).attr("data-id");
                // console.log(id);
                var url = "{{ route('admin.barang.show', ':id_data') }}";
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
