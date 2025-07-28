@extends('layouts.tabler-admin.master')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div id="respon">
                @if (session()->has('msg'))
                    <div class="alert {{ session('class') }} alert-dark">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        {{ session('msg') }}
                    </div>
                @endif
            </div>
            <div class="card">
                <div class="card-header py-2 px-4">
                    <div class="card-title">Data Mahasiswa</div>
                </div>
                <div class="card-body">
                    @include('admin.master.mahasiswa.filter')
                    <div class="table-light">
                        <div class="d-flex justify-content-end my-2">
                            @if ($idProdi != false)
                                <form method="post" action="{{ route('admin.mahasiswa.store') }}">
                                    @csrf
                                    <input type="hidden" value="20{{ $angkatan }}" name="angkatan">
                                    <button type="submit" class="btn btn-sm btn-success"
                                        onclick="return confirm('Sinkronisasi Data Sekarang?')">
                                        <i class="fas fa-sync"></i>&nbsp; Sinkronisasi
                                    </button>
                                </form>
                            @endif
                        </div>
                        @include('admin.master.mahasiswa.table')
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('admin.master.mahasiswa.javascript')
@endsection
