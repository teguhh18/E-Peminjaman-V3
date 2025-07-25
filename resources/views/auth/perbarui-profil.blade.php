{{-- Tampilkan layout untuk halaman perbarui-profil berdasarkan level --}}
@php
    $layout = (auth()->user()->level === 'mahasiswa') 
                ? 'layouts.tabler-front.master' 
                : 'layouts.tabler-admin.master';
@endphp

@extends($layout)
    {{-- @extends('layouts.tabler-front.master') --}}
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Update Profile</div>

                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong>Whoops! Ada beberapa masalah dengan input Anda:</strong>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if (session('msg'))
                        <div class="alert alert-success">
                            {{ session('msg') }}
                        </div>
                    @endif
                    <form class="form-horizontal" method="POST" action="{{ route('profil_update', encrypt($user->id)) }}"
                        enctype="multipart/form-data">
                        @method('put')
                        @csrf
                        <div class="form-group row mb-2">
                            <label class="col-md-2 control-label">Nama</label>
                            <div class="col-md-6">
                                <input type="text"
                                    class="form-control form-control-sm @error('name') is-invalid @enderror" name="name"
                                    placeholder="Nama" value="{{ old('name', $user->name) }}" readonly>
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-2">
                            <label class="col-md-2 control-label">Username</label>
                            <div class="col-md-6">
                                <input type="text"
                                    class="form-control form-control-sm @error('username') is-invalid @enderror"
                                    name="username" value="{{ old('username', $user->username) }}" readonly>
                                @error('username')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-2">
                            <label class="col-md-2 control-label">Email</label>
                            <div class="col-md-6">
                                <input type="email"
                                    class="form-control form-control-sm @error('email') is-invalid @enderror" name="email"
                                    value="{{ old('email', $user->email) }}">
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-2">
                            <label class="col-md-2 control-label">No Telepon</label>
                            <div class="col-md-6  @error('no_telepon') has-error @enderror">
                                {{-- <label for="no_telepon" class=" control-label">No Telepon</label> --}}
                                <input type="text" class="form-control" id="no_telepon" name="no_telepon" placeholder=""
                                    value="{{ old('no_telepon', $user->no_telepon) }}">
                                <small id="no_telepon_error" class="text-danger" style="display:none;">Masukkan nomor
                                    telepon dengan lengkap</small>

                                @error('no_telepon')
                                    <small class="form-message">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="foto" class="col-md-2 control-label">Foto</label>
                            <div class="col-md-6">
                                <label class="custom-file px-file" for="foto">
                                    <input type="file" id="foto" class="custom-file-input" name="foto"
                                        accept="image/*" onchange="previewImage()">

                                </label>
                            </div>
                        </div>

                        <div class="form-group row mt-2">
                            <label for="tanda_tangan" class="col-md-2 control-label">Tanda Tangan</label>
                            <div class="col-md-6">
                                <label class="custom-file px-file" for="tanda_tangan">
                                    <input type="file" id="tanda_tangan" class="custom-file-input" name="tanda_tangan"
                                        accept="image/*" onchange="tandaTangan()">

                                </label>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-2">
                                <div class="text-center">
                                    @if ($user->foto)
                                        <img src="{{ asset('storage/users/' . $user->foto) }}"
                                            class="img-preview img-fluid mb-3 d-block mx-auto" style="max-width: 250px;">
                                    @else
                                        <img class="img-preview img-fluid mb-3 mx-auto" style="max-width: 250px;">
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-2">
                                <div class="text-center">
                                    @if ($user->tanda_tangan)
                                        <img src="{{ asset('storage/tanda_tangan/' . $user->tanda_tangan) }}"
                                            class="tanda-tangan-preview img-fluid mb-3 d-block mx-auto"
                                            style="max-width: 250px;">
                                    @else
                                        <img class="tanda-tangan-preview img-fluid mb-3 mx-auto" style="max-width: 250px;">
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 col-md-offset-4">
                                <a href="{{ route('home.index') }}" class="btn btn-xs btn-default"><i
                                        class="fa fa-arrow-left"></i> Kembali</a>
                                <button type="submit" class="btn btn-xs btn-warning">
                                    <i class="fa fa-edit"></i> Update Profil
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>



    @push('js')
        <script>
            function previewImage() {
                const image = document.querySelector('#foto');
                const imgPreview = document.querySelector('.img-preview');

                imgPreview.style.display = 'block';

                const oFReader = new FileReader();
                oFReader.readAsDataURL(image.files[0]);

                oFReader.onload = function(oFREvent) {
                    imgPreview.src = oFREvent.target.result;
                }
            }

            function tandaTangan() {
                const image = document.querySelector('#tanda_tangan');
                const imgPreview = document.querySelector('.tanda-tangan-preview');

                imgPreview.style.display = 'block';

                const oFReader = new FileReader();
                oFReader.readAsDataURL(image.files[0]);

                oFReader.onload = function(oFREvent) {
                    imgPreview.src = oFREvent.target.result;
                }
            }
        </script>

        {{-- <script>
            // Untuk format no telepon jadi 628
            document.getElementById('no_telepon').addEventListener('input', function(e) {
                let phoneNumber = e.target.value.trim();
                // Menghapus semua karakter kecuali angka
                phoneNumber = phoneNumber.replace(/\D/g, '');

                // Mengubah nomor telepon yang dimulai dengan '08' menjadi '628'
                if (phoneNumber.startsWith('08')) {
                    phoneNumber = '628' + phoneNumber.slice(2);
                }

                // Memastikan panjang nomor telepon
                if (phoneNumber.length < 10) {
                    document.getElementById('no_telepon_error').style.display = 'block';
                } else {
                    document.getElementById('no_telepon_error').style.display = 'none';
                }

                // Jika panjang nomor telepon melebihi 15 digit, ambil 15 digit pertama
                if (phoneNumber.length > 15) {
                    phoneNumber = phoneNumber.slice(0, 15);
                }

                // Mengatur ulang nilai input dengan nomor telepon yang telah dimodifikasi
                e.target.value = phoneNumber;
            });
        </script> --}}
    @endpush
@endsection
