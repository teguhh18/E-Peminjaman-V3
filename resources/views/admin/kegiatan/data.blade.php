@extends('templateAdminLTE/home')
@section('sub-breadcrumb', 'Data Kegiatan')
@section('content')
    <div class="row">
        <div class="col-md-12">
            {{-- {{ auth()->user()->username }} --}}
            <div id="respon">
                @if (session()->has('msg'))
                    <div class="alert {{ session('class') }} alert-dark">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        {{ session('msg') }}
                    </div>
                @endif
            </div>
            <div class="panel">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-sm-6 panel-title">Data Kegiatan</div>
                        <div class="col-sm-6 card-tools text-right">

                            <a href="javascript:;" class="btn btn-xs btn-primary btn-add">
                                <i class="fa fa-plus"></i> Tambah Data
                            </a>
                        </div>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="table-default">
                        @include('admin.kegiatan.table')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="tempat-modal"></div>

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
                $(document).on("click", ".btn-add", function() {
                    var url = "{{ route('admin.kegiatan.create') }}";
                    $.ajax({
                            method: "GET",
                            url: url,
                        })
                        .done(function(data) {
                            $('#tempat-modal').html(data.html);
                            $('#modal_show').modal('show');
                        })
                })
                $(document).on("click", ".btn-update", function() {
                    var id = $(this).attr("data-id");
                    var url = "{{ route('admin.kegiatan.edit', ':id_data') }}";
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
                $(document).on("click", ".btn-delete", function() {
                    var id = $(this).attr("data-id");
                    var url = "{{ route('admin.kegiatan.show', ':id_data') }}";
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
@endsection
