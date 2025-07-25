@extends('templateAdminLTE/home')
@section('sub-breadcrumb', 'Data Barang')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div id="respon">

            </div>
            <div class="panel">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-sm-12 card-tools text-right">
                            <a href="{{ route('admin.barang.index') }}" class="btn btn-xs btn-warning btn-add">
                                <i class="fa fa-arrow-left"></i> kembali
                            </a>
                        </div>
                    </div>
                </div>
                <div class="panel-body">

                    <form action="{{ route('admin.barang.update', $dataBarang->id) }}" method="POST"
                        class="form-horizontal" enctype="multipart/form-data">
                        @method('put')
                        @csrf
                        {{-- <div class="form-group"> --}}
                        <div class="row">
                            <div class="col-md-6 @error('kode') has-error @enderror">
                                <label for="kode" class="control-label">Kode Barang</label>
                                <input type="text" class="form-control" id="kode" name="kode"
                                    placeholder="Kode Barang" value="{{ old('kode', $dataBarang->kode) }}" readonly>
                                @error('kode')
                                    <small class="form-message">
                                        {{ $message }}
                                    </small>
                                @enderror
                                {{-- <small class="text-muted form-help-text">Example block-level help text here.</small> --}}
                            </div>
                            <div class="col-md-6  @error('nama') has-error @enderror">
                                <label for="nama" class=" control-label">Nama Barang</label>
                                <input type="text" class="form-control" id="nama" name="nama"
                                    placeholder="Nama Barang" value="{{ old('nama', $dataBarang->nama) }}" required>
                                @error('nama')
                                    <small class="form-message">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6  @error('kategori_id') has-error @enderror">
                                <label for="kategori_id" class="control-label">Kategori</label>
                                <select name="kategori_id" id="kategori_id" class="form-control" required>
                                    <option value="">-Kategori-</option>
                                    @foreach ($kategoris as $kat)
                                        <option value="{{ $kat->id }}"
                                            {{ $kat->id == $dataBarang->kategori_id ? 'selected' : '' }}>
                                            {{ $kat->nama }}</option>
                                    @endforeach
                                </select>
                                @error('kategori_id')
                                    <small class="form-message">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div>
                            <div class="col-md-6  @error('tgl_perolehan') has-error @enderror">
                                <label for="tgl_perolehan" class="control-label">Tanggal Perolehan</label>
                                <input type="text" class="form-control" id="tgl_perolehan" name="tgl_perolehan"
                                    placeholder="Tanggal Perolehan" required
                                    value="{{ old('tgl_perolehan', date('d/m/Y', strtotime($dataBarang->tgl_perolehan))) }}">
                                @error('tgl_perolehan')
                                    <small class="form-message">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div>
                            <div class="col-md-6  @error('ruangan_id') has-error @enderror">
                                <label for="ruangan_id" class="control-label">Lokasi Aset</label>
                                <select name="ruangan_id" id="ruangan_id" class="form-control">
                                    <option value="">-Pilih Ruangan-</option>
                                    @foreach ($ruangans as $row)
                                        <option value="{{ $row->id }}"
                                            {{ $row->id == $dataBarang->ruangan_id ? 'selected' : '' }}>
                                            {{ $row->gedung->nama . ' - ' . $row->nama_ruangan . ' [Lt. ' . $row->lantai . ']' }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('ruangan_id')
                                    <small class="form-message">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div>
                            <div class="col-md-6  @error('penanggung_jawab') has-error @enderror">
                                <label for="penanggung_jawab" class="control-label">Penanggung Jawab</label>
                                <input type="text" class="form-control" id="penanggung_jawab" name="penanggung_jawab"
                                    placeholder="Penanggung Jawab"
                                    value="{{ old('penanggung_jawab', $dataBarang->penanggung_jawab) }}">
                                @error('penanggung_jawab')
                                    <small class="form-message">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6  @error('harga_perolehan') has-error @enderror">
                                <label for="harga_perolehan" class=" control-label">Harga Barang</label>
                                <input type="number" class="form-control" id="harga_perolehan" name="harga_perolehan"
                                    placeholder="Harga Barang"
                                    value="{{ old('harga_perolehan', $dataBarang->harga_perolehan) }}">
                            </div>
                            <div class="col-md-6  @error('jumlah') has-error @enderror">
                                <label for="jumlah" class="control-label">Jumlah Aset</label>
                                <input type="text" class="form-control" id="jumlah" name="jumlah"
                                    placeholder="Jumlah Aset" value="{{ old('jumlah', $dataBarang->jumlah) }}">
                                @error('jumlah')
                                    <small class="form-message">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div>

                            <div class="col-md-6  @error('kondisi') has-error @enderror">
                                <label for="kondisi" class=" control-label">Kondisi</label>
                                <select name="kondisi" id="kondisi" class="form-control">
                                    <option value="">-Kondisi Ruangan-</option>
                                    <option value="1" {{ $dataBarang->kondisi == 1 ? 'selected' : '' }}>Baik</option>
                                    <option value="2" {{ $dataBarang->kondisi == 2 ? 'selected' : '' }}>Rusak Berat
                                    </option>
                                    <option value="3" {{ $dataBarang->kondisi == 3 ? 'selected' : '' }}>Rusak Ringan
                                    </option>
                                </select>
                                @error('kondisi')
                                    <small class="form-message">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div>
                            <div class="col-md-6  @error('status') has-error @enderror">
                                <label for="status" class=" control-label">Status</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="">-Pilih Status-</option>
                                    <option value="1" {{ $dataBarang->status == 1 ? 'selected' : '' }}>Aktif</option>
                                    <option value="2" {{ $dataBarang->status == 2 ? 'selected' : '' }}>Dihapus
                                    </option>
                                    <option value="3" {{ $dataBarang->status == 3 ? 'selected' : '' }}>Diperbaiki
                                    </option>
                                </select>
                                @error('status')
                                    <small class="form-message">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div>
                        </div>

                        <div class="row">

                            <div class="col-md-6  @error('deskripsi') has-error @enderror">
                                <label for="deskripsi" class=" control-label">Deskripsi</label>
                                <textarea name="deskripsi" id="deskripsi" cols="30" rows="5" class="form-control">{{ $dataBarang->deskripsi }}</textarea>
                                @error('deskripsi')
                                    <small class="form-message">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div>
                            <div class="col-md-6  @error('foto') has-error @enderror">
                                <label for="foto" class="control-label">Foto Barang</label>
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
                                @if ($dataBarang->foto)
                                    <img src="{{ asset('storage/barangs/' . $dataBarang->foto) }}"
                                        class="img-preview img-fluid mb-3 d-block" width="250px">
                                @else
                                    <img class="img-preview img-fluid mb-3" width="250px">
                                @endif
                                @error('foto')
                                    <small class="form-message">
                                        {{ $message }}
                                    </small>
                                @enderror
                                <img class="img-preview img-fluid mb-3" width="250px">
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
