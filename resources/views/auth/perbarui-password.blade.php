@extends('layouts.tabler-front.master')
@section('sub-breadcrumb', 'Halaman Perbarui password')
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6 col-md-offset-3">
            <div class="card">
                <div class="card-header">
                    <h3>Perbarui Password</h3>
                </div>

                <div class="card-body">
                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    <form class="form-horizontal" method="POST" action="{{ route('perbaruipassword_new') }}">
                        @csrf
                        <div class="form-group mt-2 row">
                            <label for="new-password" class="col-md-4 control-label">Password Lama</label>

                            <div class="col-md-6">
                                <input id="current-password" type="password"
                                    class="form-control form-control-sm @error('current-password') is-invalid @enderror"
                                    name="current-password" placeholder="Password Lama">
                                @error('current-password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group mt-2 row">
                            <label for="new-password" class="col-md-4 control-label">Password Baru</label>
                            <div class="col-md-6">
                                <input id="new_password" type="password"
                                    class="form-control form-control-sm @error('new_password') is-invalid @enderror"
                                    name="new_password" placeholder="Password Baru">
                                @error('new_password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group mt-2 row">
                            <label for="new-password-confirm" class="col-md-4 control-label">Konfirmasi Password
                                Baru</label>

                            <div class="col-md-6">
                                <input id="new-password_confirm" type="password"
                                    class="form-control form-control-sm @error('new_password_confirm') is-invalid @enderror"
                                    name="new_password_confirm" placeholder="Konfirmasi Password Baru">
                                @error('new_password_confirm')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group mt-2 row">
                            <div class="col-md-6 col-md-offset-4">
                                <a href="{{ route('home.index') }}" class="btn btn-sm btn-default"><i
                                        class="fa fa-arrow-left me-1"></i> Kembali</a>
                                <button type="submit" class="btn btn-sm btn-primary">
                                    <i class="fa fa-edit me-1"></i> Perbarui Password
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
