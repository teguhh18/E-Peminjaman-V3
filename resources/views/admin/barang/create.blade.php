@extends('layouts.tabler-admin.master')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div id="respon">
                @if ($errors->count() > 0)
                    <div class="alert alert-important alert-danger alert-dismissible" role="alert">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
            <div class="card">
                <div class="card-header">
                    <div class="card-actions">
                        <a href="{{ route('admin.barang.index') }}" class="btn btn-md btn-warning btn-add">
                            <i class="fa fa-arrow-left"></i>&nbsp; kembali
                        </a>
                    </div>
                </div>
                <form action="{{ route('admin.barang.store') }}" method="POST" class="form-horizontal"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <label for="kode" class="form-label">Kode Barang</label>
                                <input type="text" class="form-control @error('kode') is-invalid @enderror"
                                    id="kode" name="kode" placeholder="Kode Barang"
                                    value="{{ old('kode', $newKode) }}" required>
                                @error('kode')
                                    <small class="invalid-feedback">
                                        {{ $message }}
                                    </small>
                                @enderror

                            </div>
                            <div class="col-md-6 mb-2 ">
                                <label for="nama" class=" form-label">Nama Barang</label>
                                <input type="text" class="form-control @error('nama') is-invalid @enderror"
                                    id="nama" name="nama" placeholder="Nama Barang" value="{{ old('nama') }}"
                                    required>
                                @error('nama')
                                    <small class="invalid-feedback">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-2 ">
                                <label for="kategori_id" class="form-label">Kategori</label>
                                <select name="kategori_id" id="kategori_id"
                                    class="form-control @error('kategori_id') is-invalid @enderror">
                                    <option value="">-Kategori-</option>
                                    @foreach ($kategoris as $kat)
                                        <option value="{{ $kat->id }}"
                                            {{ $kat->id == old('kategori_id') ? 'selected' : '' }}>
                                            {{ $kat->nama }}</option>
                                    @endforeach
                                </select>
                                @error('kategori_id')
                                    <small class="invalid-feedback">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div>
                            {{-- <div class="col-md-6 mb-2 ">
                                <label for="tgl_perolehan" class="form-label">Tanggal Perolehan</label>
                                <input type="text" class="form-control @error('tgl_perolehan') is-invalid @enderror"
                                    id="tgl_perolehan" name="tgl_perolehan" placeholder="Tanggal Perolehan" required
                                    value="{{ old('tgl_perolehan', date('m/d/Y')) }}" autocomplete="off">
                                @error('tgl_perolehan')
                                    <small class="invalid-feedback">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div> --}}
                            <div class="col-md-6 mb-2 ">
                                <label for="ruangan_id" class="form-label">Tempat</label>
                                <select name="ruangan_id" id="ruangan_id" style="padding: 9px 12px !important"
                                    class="form-control js-example-basic-single  @error('ruangan_id') is-invalid @enderror"
                                    width="100%">
                                    <option value="">-Pilih Ruangan-</option>
                                    @php
                                        // Mengelompokkan ruangans berdasarkan gedung
                                        $gedungs = $ruangans->groupBy(function ($item) {
                                            return $item->gedung->nama;
                                        });
                                    @endphp

                                    @foreach ($gedungs as $gedungNama => $ruanganGroup)
                                        <optgroup label="{{ $gedungNama }}">
                                            @foreach ($ruanganGroup as $row)
                                                <option value="{{ $row->id }}"
                                                    {{ $row->id == old('ruangan_id') ? 'selected' : '' }}>
                                                    {{ $row->nama_ruangan . ' [Lt. ' . $row->lantai . ']' }}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                    {{-- @foreach ($ruangans as $row)
                                        <option value="{{ $row->id }}">
                                            {{ $row->gedung->nama . ' - ' . $row->nama_ruangan . ' [Lt. ' . $row->lantai . ']' }}
                                        </option>
                                    @endforeach --}}
                                </select>
                                @error('ruangan_id')
                                    <small class="invalid-feedback">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-2 ">
                                <label for="penanggung_jawab" class="form-label">Penanggung Jawab</label>
                                <input type="text" class="form-control @error('penanggung_jawab') is-invalid @enderror"
                                    id="penanggung_jawab" name="penanggung_jawab" placeholder="Penanggung Jawab"
                                    value="{{ old('penanggung_jawab') }}">
                                @error('penanggung_jawab')
                                    <small class="invalid-feedback">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div>

                            {{-- <div class="col-md-6 mb-2 ">
                                <label for="harga_perolehan" class=" form-label">Harga Barang</label>
                                <input type="number" class="form-control @error('harga_perolehan') is-invalid @enderror"
                                    id="harga_perolehan" name="harga_perolehan" placeholder="Harga Barang"
                                    value="{{ old('harga_perolehan') }}">
                            </div> --}}

                            <div class="col-md-6 mb-2 ">
                                <label for="jumlah" class="form-label">Jumlah</label>
                                <input type="text" class="form-control @error('jumlah') is-invalid @enderror"
                                    id="jumlah" name="jumlah" placeholder="Jumlah Aset" value="{{ old('jumlah') }}">
                                @error('jumlah')
                                    <small class="invalid-feedback">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-2 ">
                                <label for="kondisi" class=" form-label">Kondisi</label>
                                <select name="kondisi" id="kondisi"
                                    class="form-control @error('kondisi') is-invalid @enderror">
                                    <option value="">-Kondisi Ruangan-</option>
                                    <option value="1" {{ 1 == old('kondisi') ? 'selected' : '' }}>Baik
                                    </option>
                                    <option value="2" {{ 2 == old('kondisi') ? 'selected' : '' }}>Rusak
                                        Berat</option>
                                    <option value="3" {{ 3 == old('kondisi') ? 'selected' : '' }}>Rusak
                                        Ringan</option>
                                </select>
                                @error('kondisi')
                                    <small class="invalid-feedback">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-2 ">
                                <label for="status" class=" form-label">Status</label>
                                <select name="status" id="status"
                                    class="form-control @error('status') is-invalid @enderror">
                                    <option value="">-Pilih Status-</option>
                                    <option value="1" {{ 1 == old('status') ? 'selected' : '' }}>Aktif
                                    </option>
                                    <option value="2" {{ 2 == old('status') ? 'selected' : '' }}>Dihapus
                                    </option>
                                    <option value="3" {{ 3 == old('status') ? 'selected' : '' }}>
                                        Diperbaiki</option>
                                </select>
                                @error('status')
                                    <small class="invalid-feedback">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div>

                            {{-- <div class="col-md-6 mb-2 ">
                                <label for="deskripsi" class=" form-label">Deskripsi</label>
                                <textarea name="deskripsi" id="deskripsi" cols="30" rows="5"
                                    class="form-control @error('deskripsi') is-invalid @enderror"></textarea>
                                @error('deskripsi')
                                    <small class="invalid-feedback">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div> --}}
                            <div class="col-md-6 mb-2 ">
                                <label for="foto" class="form-label">Foto Barang</label>
                                <input type="file" id="success-input-4"
                                    class="form-control @error('foto') is-invalid @enderror" name="foto"
                                    accept="image/*" onchange="previewImage()">

                                @error('foto')
                                    <small class="invalid-feedback">
                                        {{ $message }}
                                    </small>
                                @enderror
                                <img class="img-preview img-fluid mb-3" width="250px">
                            </div>
                        </div>

                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save"></i>&nbsp; Simpan
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <div id="tempat-modal"></div>

    @push('js')
        <script>
            // setTimeout(function() {
            //     document.getElementById('respon').innerHTML = '';
            // }, 3000);
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

            $(document).ready(function() {
                $('.js-example-basic-single').select2();
            });
        </script>
    @endpush
@endsection
