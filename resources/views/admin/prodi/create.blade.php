@extends('layouts.tabler-admin.master')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div id="respon">
            </div>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Form Tambah Prodi </h3>
                    <div class="card-actions">
                        <a href="{{ route('admin.prodi.index') }}" class="btn btn-xs btn-warning btn-add">
                            <i class="fa fa-arrow-left"></i> kembali
                        </a>
                    </div>
                </div>
                <form action="{{ route('admin.prodi.store') }}" method="POST" class="form-horizontal"
                    enctype="multipart/form-data">
                    <div class="card-body">

                        @csrf
                        {{-- <div class="form-group"> --}}
                        <div class="row">
                            <div class="col-md-6 @error('kode') has-error @enderror">
                                <label for="kode_prodi" class="form-label">Kode Prodi</label>
                                <input type="text" class="form-control" id="kode_prodi" name="kode_prodi"
                                    placeholder="Kode Unit Kerja" value="{{ old('kode_prodi') }}" required>
                                @error('kode_prodi')
                                    <small class="form-message">
                                        {{ $message }}
                                    </small>
                                @enderror
                                {{-- <small class="text-muted form-help-text">Example block-level help text here.</small> --}}
                            </div>
                            <div class="col-md-6  @error('nama') has-error @enderror">
                                <label for="nama" class=" form-label">Nama Prodi</label>
                                <input type="text" class="form-control" id="nama" name="nama"
                                    placeholder="Nama Unit Kerja" value="{{ old('nama') }}" required>
                                @error('nama')
                                    <small class="form-message">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-2 ">
                                <label for="unitkerja_id" class=" form-label">Unit Kerja</label>
                                <select name="unitkerja_id" id="unitkerja_id"
                                    class="form-control @error('unitkerja_id') is-invalid @enderror" required>
                                    <option value="">-Pilih Unit Kerja-</option>
                                    @foreach ($unit as $uk)
                                        <option value="{{ $uk->id }}">{{ $uk->kode }} - {{ $uk->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('unitkerja_id')
                                    <small class="invalid-feedback">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div>
                        </div>

                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
                    </div>
                </form>

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
        $(document).ready(function() {
            $('#unitkerja_id').select2({
                
            });
        });
    </script>
    @endpush
@endsection
