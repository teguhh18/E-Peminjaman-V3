@extends('layouts.tabler-admin.master')
{{-- @extends('templateAdminLTE/home') --}}
@section('sub-breadcrumb', 'Edit Booking')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div id="respon"></div>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Ubah Data Booking</h3>
                    <div class="card-actions">
                        <a href="{{ route('admin.booking.index') }}" class="btn btn-xs btn-warning btn-add">
                            <i class="fa fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">

                    <form action="{{ route('admin.booking.update', $dataPinjam->id) }}" method="POST"
                        class="form-horizontal" enctype="multipart/form-data">
                        @method('put')
                        @csrf
                        {{-- <div class="form-group"> --}}
                        <div class="row">
                            <div class="col-md-6 @error('name') has-error @enderror">
                                <label for="name" class="form-label">Nama Peminjam</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    placeholder="name" value="{{ old('name', $dataPinjam->user->name) }}" readonly>
                                @error('name')
                                    <small class="form-message">
                                        {{ $message }}
                                    </small>
                                @enderror
                               
                            </div>

                            <div class="col-md-6 @error('npm') has-error @enderror">
                                <label for="npm" class="form-label">NPM/Username</label>
                                <input type="text" class="form-control" id="npm" npm="npm"
                                    placeholder="npm" value="{{ old('npm', $dataPinjam->user->username) }}" readonly>
                                @error('npm')
                                    <small class="form-message">
                                        {{ $message }}
                                    </small>
                                @enderror
                               
                            </div>

                            <div class="col-md-6 @error('no_telepon') has-error @enderror">
                                <label for="no_telepon" class="form-label">No Telepon</label>
                                <input type="text" class="form-control" id="no_telepon" no_telepon="no_telepon"
                                    placeholder="no_telepon" value="{{ old('no_telepon', $dataPinjam->no_peminjam) }}" readonly>
                                @error('no_telepon')
                                    <small class="form-message">
                                        {{ $message }}
                                    </small>
                                @enderror
                               
                            </div>
                            <div class="col-md-6  @error('nama_ruangan') has-error @enderror">
                                <label for="nama_ruangan" class=" form-label">Nama Ruangan</label>
                                <input type="text" class="form-control" id="nama_ruangan" name="nama_ruangan"
                                     value="{{ old('nama_ruangan', $dataPinjam->ruangan->nama_ruangan) }}" readonly>
                                @error('nama_ruangan')
                                    <small class="form-message">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div>

                           
                        </div>
                        <div class="row">
                            
                            <div class="col-md-6  @error('waktu_peminjaman') has-error @enderror">
                                <label for="waktu_peminjaman" class="form-label">Waktu Peminjaman </label>
                                <input type="text" class="form-control" id="waktu_peminjaman" name="waktu_peminjaman"
                                readonly
                                    value="{{ old('waktu_peminjaman', date('d/m/Y', strtotime($dataPinjam->waktu_peminjaman))) }}">
                                @error('waktu_peminjaman')
                                    <small class="form-message">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div>

                            <div class="col-md-6  @error('waktu_pengembalian') has-error @enderror">
                                <label for="waktu_pengembalian" class="form-label">Waktu Pengembalian </label>
                                <input type="text" class="form-control" id="waktu_pengembalian" name="waktu_pengembalian"
                                readonly
                                    value="{{ old('waktu_pengembalian', date('d/m/Y', strtotime($dataPinjam->waktu_pengembalian))) }}">
                                @error('waktu_pengembalian')
                                    <small class="form-message">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div>
                            
                            
                        </div>

                        <div class="row">

                             <div class="col-md-6  @error('kegiatan') has-error @enderror">
                                <label for="kegiatan" class=" form-label"> Kegiatan</label>
                                <input type="text" class="form-control" id="kegiatan" name="kegiatan"
                                     value="{{ old('kegiatan', $dataPinjam->kegiatan) }}" readonly>
                                @error('kegiatan')
                                    <small class="form-message">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div>

                            
                            <div class="col-md-6  @error('status') has-error @enderror">
                                <label for="status" class=" form-label">Status</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="">-Pilih Status-</option>
                                    <option value="1" {{ $dataPinjam->konfirmasi == 1 ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                                    <option value="2" {{ $dataPinjam->konfirmasi == 2 ? 'selected' : '' }}>Dibooking/Sedang Dipinjam
                                    </option>
                                    <option value="3" {{ $dataPinjam->konfirmasi == 3 ? 'selected' : '' }}>Ditolak
                                    </option>
                                    <option value="4" {{ $dataPinjam->konfirmasi == 4 ? 'selected' : '' }}>Dikembalikan
                                    </option>
                                </select>
                                @error('status')
                                    <small class="form-message">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div>
                        </div>

                        <div class="row " style="margin-top: 8px">
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i>
                                    Simpan</button>
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
        </script>
        <script>
            $(function() {
                $("#tgl_perolehan").datepicker();
            });
        </script>
    @endpush
@endsection
