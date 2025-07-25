<div class="modal fade" id="modalPilihApprover" tabindex="-1" aria-labelledby="modalPilihApproverLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modalPilihApproverLabel">Pilih Approver</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <form id="form-pilih-appover">
                    @csrf
                    <div class="mb-3">
                        <label for="approver_id" class="form-label">Pilih Approver</label>
                        <select name="approver_id" id="approver_id" class="form-select">
                            <option value="">Pilih Approver</option>
                            @foreach ($approvers as $approver)
                                <option value="{{ $approver->id }}">
                                    {{ $approver->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="modalClose" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button id="btn-submit-approver" class="btn btn-primary">Tambah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
