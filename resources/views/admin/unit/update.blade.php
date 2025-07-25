@extends('layouts.tabler-admin.master')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div id="respon"></div>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Ubah Data Unit</h3>
                    <div class="card-actions">
                        <a href="{{ route('admin.unit.index') }}" class="btn btn-xs btn-warning btn-add">
                            <i class="fa fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <form action="{{ route('admin.unit.update', $dataUnit->id) }}" method="POST" class="form-horizontal"
                    enctype="multipart/form-data">
                    <div class="card-body">
                        @method('put')
                        @csrf
                        {{-- <div class="form-group"> --}}
                        <div class="row">
                            <div class="col-md-6 @error('kode') has-error @enderror">
                                <label for="kode" class="form-label">Kode Unit Kerja</label>
                                <input type="text" class="form-control" id="kode" name="kode"
                                    placeholder="Kode Unit Kerja" value="{{ old('kode', $dataUnit->kode) }}">
                                @error('kode')
                                    <small class="form-message">
                                        {{ $message }}
                                    </small>
                                @enderror
                                {{-- <small class="text-muted form-help-text">Example block-level help text here.</small> --}}
                            </div>
                            <div class="col-md-6  @error('nama') has-error @enderror">
                                <label for="nama" class=" form-label">Nama Unit Kerja</label>
                                <input type="text" class="form-control" id="nama" name="nama"
                                    placeholder="Nama Unit Kerja" value="{{ old('nama', $dataUnit->nama) }}" required>
                                @error('nama')
                                    <small class="form-message">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Ubah</button>
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
