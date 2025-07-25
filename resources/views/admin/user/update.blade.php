@extends('layouts.tabler-admin.master')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div id="respon">
                @if (session()->has('msg'))
                    <div class="alert alert-important alert-{{ session('class') }} alert-dismissible" role="alert">
                        <div class="d-flex">
                            <div><i class="fa fa-info"></i></div>
                            <div>{{ session('msg') }}</div>
                        </div>
                        <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
                    </div>
                @endif
            </div>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Ubah Data</h3>

                    <div class="card-actions">
                        <a href="{{ route('admin.user.index') }}" class="btn btn-xs btn-warning btn-add">
                            <i class="fa fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <form action="{{ route('admin.user.update', $dataUser->id) }}" method="POST" class="form-horizontal"
                    enctype="multipart/form-data">
                    @method('put')
                    @csrf
                    <div class="card-body">

                        {{-- <div class="form-group"> --}}
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <label for="name" class="form-label">Nama</label>
                                <input type="text" class="form-control @error('name') has-error @enderror" id="name"
                                    name="name" placeholder="Nama User" value="{{ old('name', $dataUser->name) }}"
                                    required>
                                @error('name')
                                    <small class="invalid-feedback">
                                        {{ $message }}
                                    </small>
                                @enderror
                                {{-- <small class="text-muted form-help-text">Example block-level help text here.</small> --}}
                            </div>
                            <div class="col-md-6 mb-2 ">
                                <label for="username" class=" form-label">Username</label>
                                <input type="text" class="form-control @error('username') has-error @enderror"
                                    id="username" name="username" placeholder="Username"
                                    value="{{ old('username', $dataUser->username) }}">
                                @error('username')
                                    <small class="invalid-feedback">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-2 ">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') has-error @enderror"
                                    id="email" name="email" placeholder="Email" required
                                    value="{{ old('email', $dataUser->email) }}">
                                @error('email')
                                    <small class="invalid-feedback">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-2 ">
                                <label for="password" class="form-label">Password <small
                                        class="text-blue italic"><em>abaikan
                                            jika
                                            tidak diubah</em></small></label>
                                <input type="password" class="form-control @error('password') has-error @enderror"
                                    id="password" name="password" placeholder="Password" value="{{ old('password') }}">
                                @error('password')
                                    <small class="invalid-feedback">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-2 ">
                                <label for="level" class=" form-label">Level</label>
                                <select name="level" id="level"
                                    class="form-control @error('level') has-error @enderror">
                                    <option value="{{ old('level') }}">-Level User-</option>
                                    <option value="admin"
                                        {{ old('level', $dataUser->level) == 'admin' ? 'selected' : '' }}>
                                        Admin</option>
                                    <option value="baak"
                                        {{ old('level', $dataUser->level) == 'baak' ? 'selected' : '' }}>Baak</option>
                                    <option value="mahasiswa"
                                        {{ old('level', $dataUser->level) == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa
                                    </option>
                                </select>
                                @error('level')
                                    <small class="invalid-feedback">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="no_telepon" class="form-label">No Telepon</label>
                                <input type="number" class="form-control @error('no_telepon') has-error @enderror"
                                    id="no_telepon" name="no_telepon" placeholder="Nomor Telepon"
                                    value="{{ old('no_telepon', $dataUser->no_telepon) }}" maxlength="13" required>
                                <small id="no_telepon_error" class="text-danger" style="display:none;">Masukkan nomor
                                    telepon dengan lengkap</small>
                                @error('no_telepon')
                                    <small class="invalid-feedback">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-2 " id="units">
                                <label for="unitkerja_id" class=" form-label">Unit Kerja</label>
                                <select name="unitkerja_id" id="unitkerja_id"
                                    class="form-control @error('unitkerja_id') has-error @enderror" required>
                                    <option value="">- Pilih Unit Kerja -</option>
                                    @foreach ($unitKerja as $unit)
                                        <option value="{{ $unit->id }}"
                                            {{ old('unitkerja_id', $dataUser->unitkerja_id) == $unit->id ? 'selected' : '' }}>
                                            {{ $unit->kode }} &nbsp; [{{ $unit->nama }}]
                                        </option>
                                    @endforeach
                                </select>
                                @error('unitkerja_id')
                                    <small class="invalid-feedback">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-2 ">
                                <label for="foto" class="form-label">Foto User</label>
                                <input type="file" id="success-input-4"
                                    class="form-control @error('foto') has-error @enderror" name="foto"
                                    accept="image/*" onchange="previewImage()">

                                @error('foto')
                                    <small class="invalid-feedback">
                                        {{ $message }}
                                    </small>
                                @enderror
                                <img src="{{ asset('storage/users/' . $dataUser->foto) }}" class="img-fluid mb-3"
                                    width="250px">
                                <br> Foto baru :
                                <img class="img-preview img-fluid mb-3" width="250px">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="tempat-modal"></div>
@endsection

@push('js')
    <script>
        setTimeout(function() {
            document.getElementById('respon').innerHTML = '';
        }, 3000);
    </script>
    <script>
        // Prewiew Image
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
    <script>
        $(document).ready(function() {
            function toggleUnits() {
                if ($('#level').val() === 'Mahasiswa' || $('#level').val() === 'mahasiswa') {
                    $('#units').addClass('d-none');
                } else {
                    $('#units').removeClass('d-none');
                }
            }

            // Panggil fungsi saat halaman dimuat
            toggleUnits();

            // Panggil fungsi saat pilihan di select level berubah
            $('#level').on('change', function() {
                toggleUnits();
            });
        });
    </script>
@endpush
