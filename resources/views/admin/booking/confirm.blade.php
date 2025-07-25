{{-- Modal --}}
<div class="modal fade modal-warning" id="modal_show" tabindex="-1" role="dialog" aria-labelledby="modal_show_label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_show_label"><i class="fa fa-warning"></i> Konfirmasi Peminjaman</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" action="{{ route('admin.booking.update', $dataPinjam->id) }}" id="idForm">
                    @csrf
                    @method('put')

                    <div class="form-group @error('status') has-error @enderror">
                        <label for="status" class="control-label">Status</label>
                        <select name="status" id="status" class="form-control">
                            <option value="">-Ubah Status-</option>
                            {{-- <option value="menunggu" {{ $dataPinjam->status == menunggu ? 'selected' : '' }}>Terima
                            </option> --}}
                            <option value="disetujui" {{ $dataPinjam->status == "disetujui" ? 'selected' : '' }}>Setuju
                            </option>
                            <option value="ditolak" {{ $dataPinjam->status == "ditolak" ? 'selected' : '' }}>Tolak
                            </option>
                        </select>
                        @error('status')
                            <small class="form-message text-danger">
                                {{ $message }}
                            </small>
                        @enderror

                        <input type="hidden" value="{{ $dataPinjam->peminjaman_id }}" name="peminjaman_id">
                    </div>

                    <div class="form-group @error('catatan') has-error @enderror">
                        <label for="catatan" class="control-label">Catatan</label>
                        <input class="form-control" type="text" name="catatan" id="catatan" value="{{ old('value', $dataPinjam->catatan) }}">
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
