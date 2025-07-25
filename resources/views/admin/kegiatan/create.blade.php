<div class="modal fade" id="modal_show" role="dialog" style="padding:0;">
    <div class="modal-dialog modal-dialog-scrollable modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">{{ $judul }}</h4>
            </div>
            <form method="post" action="{{ route('admin.kegiatan.store') }}" id="idForm">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Kegiatan</label>
                        <input type="text" name="nama_kegiatan" class="form-control form-control-sm"
                            placeholder="Nama Kegiatan" autocomplete="off" required>
                    </div>
                    <div class="form-group">
                        <label>Deskripsi Kegiatan</label>
                        <input type="text" name="deskripsi" class="form-control form-control-sm"
                            placeholder="Deskripsi Kegiatan" autocomplete="off" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-save"></i>
                        Simpan</button>
                    <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal"><i
                            class="fa fa-times"></i>Tutup</button>
                </div>
            </form>
        </div>
    </div>
</div>
