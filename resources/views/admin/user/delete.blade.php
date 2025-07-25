<div class="modal fade modal-blur modal-warning in" id="modal_show" role="dialog" style="padding:0;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-orange text-orange-fg">
                <div class="modal-title">Hapus Data Ini?</div><i class="fa fa-warning"></i>
            </div>

            <div class="modal-body">Klik Button Hapus untuk menghapus data.</div>
            <div class="modal-footer">
                <form method="post" action="{{ route('admin.user.destroy', $dataUser->id) }}" id="idForm">
                    @csrf
                    @method('delete')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                    <button type="button" class="btn btn-default" data-bs-dismiss="modal">Tutup</button>
                </form>
            </div>
        </div>
    </div>
</div>
