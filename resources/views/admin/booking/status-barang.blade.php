{{-- Modal --}}
<div class="modal fade modal-warning" id="status_barang" tabindex="-1" role="dialog" aria-labelledby="status_barang_label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="status_barang_label"><i class="fa fa-warning"></i>Status Barang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" action="{{ route('admin.pengembalian.update', $statusBarang->id) }}" id="idForm">
                    @csrf
                    @method('put')

                    <div class="form-group @error('status') has-error @enderror">
                        <label for="status" class="control-label">Status</label>
                        <select name="status" id="status" class="form-control">
                            <option value="">-Ubah Status-</option>
                            <option value="disetujui" {{ $statusBarang->status == "disetujui" ? 'selected' : '' }}>Disetujui
                            </option>
                            <option value="diambil" {{ $statusBarang->status == 'diambil' ? 'selected' : '' }}>Diambil
                            </option>
                            <option value="dikembalikan" {{ $statusBarang->status == "dikembalikan" ? 'selected' : '' }}>Dikembalikan
                            </option>
                            <option value="bermasalah" {{ $statusBarang->status == "bermasalah" ? 'selected' : '' }}>Bermasalah
                            </option>
                        </select>
                        @error('status')
                            <small class="form-message text-danger">
                                {{ $message }}
                            </small>
                        @enderror

                        <input type="hidden" value="{{ $statusBarang->peminjaman_id }}" name="peminjaman_id">
                    </div>

                    <div class="form-group @error('catatan') has-error @enderror">
                        <label for="catatan" class="control-label">Catatan</label>
                        <input class="form-control" type="text" name="catatan" id="catatan" value="{{ old('value', $statusBarang->catatan) }}">
                        @error('catatan')
                            <small class="form-message text-danger">
                                {{ $message }}
                            </small>
                        @enderror
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="idForm" class="btn btn-warning">Simpan</button>
                <button type="button" class="btn btn-default" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
