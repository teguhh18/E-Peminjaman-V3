<div class="modal fade modal-blur modal-warning in" id="modal_show" role="dialog" style="padding:0;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-orange-fg">
                <div class="modal-title">Hapus Data Ini?</div>
            </div>

            <div class="modal-body">Klik Button Hapus untuk menghapus data.</div>
            <div class="modal-footer">
                <form method="post" action="{{ route('admin.barang.destroy', $dataBarang->id) }}" id="idForm">
                    @csrf
                    @method('delete')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                </form>
            </div>
        </div>
    </div>
</div>
