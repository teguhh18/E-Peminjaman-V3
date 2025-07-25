@extends('layouts.tabler-admin.master')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div id="respon">

            </div>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Form Tambah Data Gedung</h3>
                    <div class="card-actions">
                        <a href="{{ route('admin.gedung.index') }}" class="btn btn-xs btn-warning btn-add">
                            <i class="fa fa-arrow-left"></i> kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">

                    <form action="{{ route('admin.gedung.store') }}" method="POST" class="form-horizontal"
                        enctype="multipart/form-data">
                        @csrf
                        {{-- <div class="form-group"> --}}
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <label for="kode" class="form-label">Kode Gedung</label>
                                <input type="text" class="form-control @error('kode') is-invalid @enderror"
                                    id="kode" name="kode" placeholder="Kode Gedung" value="{{ old('kode') }}"
                                    required>
                                @error('kode')
                                    <small class="invalid-feedback">
                                        {{ $message }}
                                    </small>
                                @enderror
                                {{-- <small class="text-muted form-help-text">Example block-level help text here.</small> --}}
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="nama" class=" form-label">Nama Gedung</label>
                                <input type="text" class="form-control  @error('nama') is-invalid @enderror"
                                    id="nama" name="nama" placeholder="Nama Gedung" value="{{ old('nama') }}"
                                    required>
                                @error('nama')
                                    <small class="invalid-feedback">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-2 ">
                                <label for="jumlah_lantai" class="form-label">Jumlah Lantai</label>
                                <input type="number" class="form-control @error('jumlah_lantai') is-invalid @enderror"
                                    id="jumlah_lantai" name="jumlah_lantai" placeholder="Jumlah lantai" required
                                    value="{{ old('jumlah_lantai') }}">
                                @error('jumlah_lantai')
                                    <small class="invalid-feedback">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-2 ">
                                <label for="lokasi" class="form-label">Lokasi</label>
                                <input type="text" class="form-control @error('lokasi') is-invalid @enderror"
                                    id="lokasi" name="lokasi" placeholder="Lokasi" value="{{ old('lokasi') }}">
                                @error('lokasi')
                                    <small class="invalid-feedback">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div>

                            {{-- <div class="col-md-6 mb-2 ">
                                <label for="besar_dana" class=" form-label">Besar Dana</label>
                                <input type="text" class="form-control @error('besar_dana') is-invalid @enderror"
                                    id="besar_dana" name="besar_dana" placeholder="Jumlah dana perolehan"
                                    value="{{ old('besar_dana') }}">
                            </div>
                            <div class="col-md-6 mb-2 ">
                                <label for="sumber_dana" class=" form-label">Sumber Dana</label>
                                <input type="text" class="form-control @error('sumber_dana') is-invalid @enderror"
                                    id="sumber_dana" name="sumber_dana" placeholder="Sumber dana"
                                    value="{{ old('sumber_dana') }}">
                                @error('sumber_dana')
                                    <small class="invalid-feedback">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div> --}}

                            <div class="col-md-6 mb-2 ">
                                <label for="tahun" class=" form-label">Tahun Perolehan</label>
                                <select name="tahun" id="tahun"
                                    class="form-control @error('tahun') is-invalid @enderror">
                                    <option value="">-Pilih Tahun-</option>
                                    @for ($year = date('Y'); $year >= 2000; $year--)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endfor
                                </select>
                                @error('tahun')
                                    <small class="invalid-feedback">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-2 ">
                                <label for="foto" class="form-label">Foto Gedung</label>
                                <input type="file" id="success-input-4"
                                    class="form-control @error('foto') is-invalid @enderror" name="foto" accept="image/*"
                                    onchange="previewImage()">
                                {{-- <span class="custom-file-control form-control">Pilih file...</span> --}}

                                @error('foto')
                                    <small class="invalid-feedback">
                                        {{ $message }}
                                    </small>
                                @enderror
                                <img class="img-preview img-fluid mb-3" width="250px">
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
        </script>
    @endpush
@endsection
