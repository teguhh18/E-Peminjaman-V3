@extends('layouts.tabler-admin.master')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div id="respon"></div>

            <div class="card">
                <div class="card-header">
                    <div class="card-actions">
                        <a href="{{ route('admin.ruangan.index') }}" class="btn btn-sm btn-warning btn-add">
                            <i class="fa fa-arrow-left"></i> kembali
                        </a>
                    </div>
                </div>
                <form action="{{ route('admin.ruangan.update', $ruangan->id) }}" method="POST" class="form-horizontal"
                    enctype="multipart/form-data">
                    @method('put')
                    <div class="card-body">
                        @csrf
                        <div class="row">

                            <div class="col-md-6 mb-2 ">
                                <label for="kode_ruangan" class="form-label">Kode Ruangan</label>
                                <input type="text" class="form-control @error('kode_ruangan') is-invalid @enderror"
                                    id="kode_ruangan" name="kode_ruangan" placeholder="Kode Ruangan"
                                    value="{{ old('kode_ruangan', $ruangan->kode_ruangan) }}" readonly>
                                @error('kode_ruangan')
                                    <small class="invalid-feedback">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-2 ">
                                <label for="nama_ruangan" class=" form-label">Nama Ruangan</label>
                                <input type="text" class="form-control @error('nama_ruangan') is-invalid @enderror"
                                    id="nama_ruangan" name="nama_ruangan" placeholder="Nama Ruangan"
                                    value="{{ old('nama_ruangan', $ruangan->nama_ruangan) }}" required>
                                @error('nama_ruangan')
                                    <small class="invalid-feedback">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-2 ">
                                <label for="lantai" class="form-label">Lantai</label>
                                <input type="number" class="form-control @error('lantai') is-invalid @enderror"
                                    id="lantai" name="lantai" placeholder="lantai" required
                                    value="{{ old('lantai', $ruangan->lantai) }}">
                                @error('lantai')
                                    <small class="invalid-feedback">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div>
                            {{-- <div class="col-md-6 mb-2 ">
                                <label for="tipe" class="form-label">Tipe</label>
                                <input type="text" class="form-control @error('tipe') is-invalid @enderror"
                                    id="tipe" name="tipe" placeholder="Lokasi"
                                    value="{{ old('tipe', $ruangan->tipe) }}">
                                @error('tipe')
                                    <small class="invalid-feedback">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div> --}}

                            <div class="col-md-6 mb-2 ">
                                <label for="kapasitas" class=" form-label">Kapasitas</label>
                                <input type="text" class="form-control @error('kapasitas') is-invalid @enderror"
                                    id="kapasitas" name="kapasitas" placeholder="Kapasitas"
                                    value="{{ old('kapasitas', $ruangan->kapasitas) }}">
                            </div>
                            {{-- <div class="col-md-6 mb-2 ">
                                <label for="luas" class=" form-label">Luas</label>
                                <input type="text" class="form-control @error('luas') is-invalid @enderror"
                                    id="luas" name="luas" placeholder="Luas "
                                    value="{{ old('luas', $ruangan->luas) }}">
                                @error('luas')
                                    <small class="invalid-feedback">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div> --}}

                            <div class="col-md-6 mb-2 ">
                                <label for="kondisi" class=" form-label">Kondisi</label>
                                <select name="kondisi" id="kondisi"
                                    class="form-control @error('kondisi') is-invalid @enderror">
                                    <option value="">-Kondisi Ruangan-</option>
                                    <option {{ old('kondisi', $ruangan->kondisi) == 'baik' ? 'selected' : '' }}>Baik
                                    </option>
                                    <option {{ old('kondisi', $ruangan->kondisi) == 'rusak_berat' ? 'selected' : '' }}>
                                        Rusak
                                        Berat</option>
                                    <option {{ old('kondisi', $ruangan->kondisi) == 'rusak_ringan' ? 'selected' : '' }}>
                                        Rusak Ringan</option>
                                </select>
                                @error('kondisi')
                                    <small class="invalid-feedback">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-2 ">
                                <label for="gedung_id" class=" form-label">Gedung</label>
                                <select name="gedung_id" id="gedung_id"
                                    class="form-control @error('gedung_id') is-invalid @enderror">
                                    <option value="">-Pilih Gedung-</option>
                                    @foreach ($gedungs as $gedung)
                                        <option value="{{ $gedung->id }}"
                                            {{ old('kondisi', $ruangan->gedung_id) == $gedung->id ? 'selected' : '' }}>
                                            {{ $gedung->nama }}</option>
                                    @endforeach
                                </select>
                                @error('gedung_id')
                                    <small class="invalid-feedback">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-2 ">
                                <label for="unitkerja_id" class=" form-label">Unit Kerja</label>
                                <select name="unitkerja_id" id="unitkerja_id"
                                    class="form-control @error('unitkerja_id') is-invalid @enderror">
                                    <option value="">-Pilih Unit Kerja-</option>
                                    @foreach ($unitkerjas as $uk)
                                        <option value="{{ $uk->id }}"
                                            {{ old('kondisi', $ruangan->unitkerja_id) == $uk->id ? 'selected' : '' }}>
                                            {{ $uk->kode }} - {{ $uk->nama }}
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
                                <label for="bisa_pinjam" class=" form-label">Bisa Pinjam</label>
                                <select name="bisa_pinjam" id="bisa_pinjam"
                                    class="form-control @error('bisa_pinjam') is-invalid @enderror">
                                    <option value="">-Pilih-</option>
                                    <option value="0"
                                        {{ old('bisa_pinjam', $ruangan->bisa_pinjam) == '0' ? 'selected' : '' }}>Tidak
                                    </option>
                                    <option value="1"
                                        {{ old('bisa_pinjam', $ruangan->bisa_pinjam) == '1' ? 'selected' : '' }}>Bisa
                                    </option>
                                </select>
                                @error('bisa_pinjam')
                                    <small class="invalid-feedback">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div>

                            {{-- <div class="col-md-6 mb-2 ">
                                <label for="status" class=" form-label">Status</label>
                                <select name="status" id="status"
                                    class="form-control @error('status') is-invalid @enderror">
                                    <option value="">-Pilih Status-</option>
                                    <option value="1"
                                        {{ old('kondisi', $ruangan->status) == '1' ? 'selected' : '' }}>Aktif</option>
                                    <option value="2"
                                        {{ old('kondisi', $ruangan->status) == '2' ? 'selected' : '' }}>Dihapus</option>
                                    <option value="3"
                                        {{ old('kondisi', $ruangan->status) == '3' ? 'selected' : '' }}>Diperbaiki</option>
                                </select>
                                @error('status')
                                    <small class="invalid-feedback">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div> --}}

                            <div class="col-md-6 mb-2 ">
                                <label for="foto_ruangan" class="form-label">Foto Ruangan</label>
                                <input type="file" id="success-input-4"
                                    class="form-control @error('foto_ruangan') is-invalid @enderror" name="foto_ruangan"
                                    accept="image/*" onchange="previewImage()">

                                @if ($ruangan->foto_ruangan)
                                    <img src="{{ asset('storage/ruangans/' . $ruangan->foto_ruangan) }}"
                                        class="img-preview img-fluid mb-3 d-block" width="250px">
                                @else
                                    <img class="img-preview img-fluid mb-3" width="250px">
                                @endif
                                @error('foto_ruangan')
                                    <small class="invalid-feedback">
                                        {{ $message }}
                                    </small>
                                @enderror
                                <img class="img-preview img-fluid mb-3" width="250px">
                            </div>
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
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
