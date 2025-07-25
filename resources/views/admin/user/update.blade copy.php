@extends('templateAdminLTE/home')
@section('sub-breadcrumb', 'Data User')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div id="respon">

            </div>
            <div class="panel">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-sm-12 card-tools text-right">
                            <a href="{{ route('admin.user.index') }}" class="btn btn-xs btn-warning btn-add">
                                <i class="fa fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </div>
                </div>
                <div class="panel-body">

                    <form action="{{ route('admin.user.update', $dataUser->id) }}" method="POST" class="form-horizontal"
                        enctype="multipart/form-data">
                        @method('put')
                        @csrf
                        {{-- <div class="form-group"> --}}
                        <div class="row">
                            <div class="col-md-6 @error('name') has-error @enderror">
                                <label for="name" class="control-label">Nama</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    placeholder="Nama User" value="{{ old('name', $dataUser->name) }}">
                                @error('name')
                                    <small class="form-message">
                                        {{ $message }}
                                    </small>
                                @enderror
                                {{-- <small class="text-muted form-help-text">Example block-level help text here.</small> --}}
                            </div>
                            <div class="col-md-6  @error('username') has-error @enderror">
                                <label for="username" class=" control-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username"
                                    placeholder="Username" value="{{ old('username', $dataUser->username) }}" required>
                                @error('username')
                                    <small class="form-message">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6  @error('email') has-error @enderror">
                                <label for="email" class="control-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Email"
                                    required value="{{ old('email', $dataUser->email) }}">
                                @error('email')
                                    <small class="form-message">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div>
                            <div class="col-md-6  @error('password') has-error @enderror">
                                <label for="password" class="control-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password"
                                    placeholder="password" value="">
                                @error('password')
                                    <small class="form-message">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6  @error('level') has-error @enderror">
                                <label for="level" class=" control-label">Level</label>
                                <select name="level" id="level" class="form-control">
                                    <option value="">-Level User-</option>
                                    <option value="admin" {{ $dataUser->level == 'admin' ? 'selected' : '' }}>Admin
                                    </option>
                                    <option value="baak" {{ $dataUser->level == 'baak' ? 'selected' : '' }}>BAAK</option>
                                    <option value="mahasiswa" {{ $dataUser->level == 'mahasiswa' ? 'selected' : '' }}>
                                        Mahasiswa</option>
                                </select>

                                @error('level')
                                    <small class="form-message">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div>
                            <div class="col-md-6  @error('no_telepon') has-error @enderror">
                                <label for="no_telepon" class=" control-label">No Telepon</label>
                                <input type="number" class="form-control" id="no_telepon" name="no_telepon" placeholder=""
                                    value="{{ old('no_telepon', $dataUser->no_telepon) }}">
                                <small id="no_telepon_error" class="text-danger" style="display:none;">Masukkan nomor
                                    telepon dengan lengkap</small>
                                @error('no_telepon')
                                    <small class="form-message">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div>
                        </div>

                        <div class="row">



                            <div class="col-md-6  @error('unitkerja_id') has-error @enderror">
                                <label for="unitkerja_id" class=" control-label">Unit Kerja</label>
                                <select name="unitkerja_id" id="unitkerja_id" class="form-control">
                                    <option value="">-Pilih Unit Kerja-</option>
                                    @foreach ($unitKerja as $unit)
                                        <option value="{{ $unit->id }}"
                                            {{ old('unitkerja_id', $dataUser->unitkerja_id) == $unit->id ? 'selected' : '' }}>
                                            {{ $unit->nama }}</option>
                                    @endforeach
                                </select>
                                @error('unitkerja_id')
                                    <small class="form-message">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="foto" class="control-label">Foto User</label>
                                <label class="custom-file px-file" for="success-input-4">
                                    <input type="file" id="success-input-4" class="custom-file-input" name="foto"
                                        accept="image/*" onchange="previewImage()">
                                    <span class="custom-file-control form-control">Pilih file...</span>
                                    <div class="px-file-buttons">
                                        <button type="button" class="btn btn-xs px-file-clear">Clear</button>
                                        <button type="button"
                                            class="btn btn-primary btn-xs px-file-browse">Browse</button>
                                    </div>
                                </label>
                                @if ($dataUser->foto)
                                    <img src="{{ asset('storage/users/' . $dataUser->foto) }}"
                                        class="img-preview img-fluid mb-3 d-block" width="250px">
                                @else
                                    <img class="img-preview img-fluid mb-3" width="250px">
                                @endif
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
                            </div>
                        </div>
                    </form>

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
            function previewImage() {
                const image = document.querySelector('#success-input-4');
                const imgPreview = document.querySelector('.img-preview');

                imgPreview.style.display = 'block';

                const oFReader = new FileReader();
                oFReader.readAsDataURL(image.files[0]);

                oFReader.onload = function(oFREvent) {
                    imgPreview.src = oFREvent.target.result;
                }
            }


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
        </script>
    @endpush
@endsection
