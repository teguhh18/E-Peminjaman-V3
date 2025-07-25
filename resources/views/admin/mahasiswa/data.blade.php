@extends('layouts.tabler-admin.master')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div id="respon">
                @if (session()->has('msg'))
                    <div class="alert  alert-important alert-{{ session('class') }} alert-dismissible" role="alert">
                        <div class="d-flex">
                            <div>
                                <i class="fa fa-info"></i>
                            </div>
                            <div>
                                {{ session('msg') }}
                            </div>
                        </div>
                        <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
                    </div>
                @endif
            </div>
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Data User</div>
                    <div class="card-actions">
                        {{-- <a href="{{ route('admin.user.create') }}" class="btn btn-md btn-primary btn-add">
                            <i class="fa fa-plus"></i>&nbsp; Tambah User
                        </a> --}}
                    </div>
                </div>
                <div class="card-body">
                    <form method="get">
                        @csrf
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <select name="angkatan" class="form-control" required>
                                        <option value="" selected disabled>Pilih</option>
                                        @php
                                            $now = date('Y');
                                        @endphp
                                        @for ($i = 2015; $i <= $now; $i++)
                                            <option value="{{ substr($i, -2) }}"
                                                {{ $angkatan == substr($i, -2) ? 'selected' : '' }}>{{ $i }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <select name="prodi" class="form-control" required>
                                        <option value="" selected disabled>Pilih</option>
                                        @foreach ($dataProdi->data as $key)
                                            <option value="{{ $key->id_prodi }}"
                                                {{ $idProdi == $key->id_prodi ? 'selected' : '' }}>{{ $key->nama_prodi }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary"><i class="fa fa-filter"></i>
                                        Filter</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="table-light">
                        @if ($idProdi != false)
                            <div class="row">
                                <div class="col-md-12 mt-3" style="padding-bottom:5px;">
                                    <form method="post" action="{{ route('admin.mahasiswa.store') }}">
                                        @csrf
                                        <input type="hidden" value="20{{ $angkatan }}" name="angkatan">
                                        <input type="hidden" value="{{ $prodiSelect->id_fakultas }}" name="kode_fakultas">
                                        <input type="hidden" value="{{ $prodiSelect->id_prodi }}" name="kode_prodi">
                                        <input type="hidden" value="{{ $prodiSelect->nama_fakultas }}"
                                            name="nama_fakultas">
                                        <input type="hidden" value="{{ $prodiSelect->nama_prodi }}" name="nama_prodi">
                                        <button type="submit" class="btn btn-success"
                                            onclick="return confirm('Sinkronisasi Data Sekarang?')">
                                            <i class="fas fa-sync"></i>&nbsp; Sinkronisasi
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endif
                        @include('admin.mahasiswa.table')
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

                $(document).on("click", ".btn-delete", function() {
                    var id = $(this).attr("data-id");
                    // console.log(id);
                    var url = "{{ route('admin.mahasiswa.show', ':id_data') }}";
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
