<div class="modal fade" id="modal_konfirmasi" tabindex="-1" aria-labelledby="modal_info" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title " id="modal_info">Info Status Peminjaman</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                @foreach ($persetujuan as $item)
                    @php
                        $role = $item->approval_role;
                        $unit = $item->unit_kerja->kode ?? $role;
                        $user = $item->user?->name;
                        $status = $item->status;
                        $statusText = '';

                        // Tampilan status approve berdasarkan role
                        if ($role === 'kerumahtanggan') {
                            $statusText = "<b>$unit</b> $status" . ($user ? " oleh $user" : '');
                        } elseif ($role === 'kaprodi') {
                            $statusText =
                                '<b>kaprodi ' .
                                ($item->peminjaman->user->prodi->kode_prodi ?? '') .
                                '</b> ' .
                                $status .
                                ($user ? " oleh $user" : '');
                        } else {
                            $statusText = "<b>Tata Usaha $unit</b> $status" . ($user ? " oleh $user" : '');
                        }

                        // Tampilan warna approve berdasarkan statusnya
                        switch ($item->status) {
                            case 'disetujui':
                                $bg = 'bg-success';
                                $icon = 'fa fa-check-circle me-1';
                                break;
                            case 'ditolak':
                                $bg = 'bg-danger';
                                $icon = 'fa fa-times me-1';
                                break;
                            default:
                                $bg = 'bg-warning';
                                $icon = 'fa fa-spinner fa-spin';
                        }
                    @endphp
                    <span class="btn badge {{ $bg }} d-inline-block mb-1 text-light" id="btn-konfirmasi"
                        data-id="{{ $item->id }}">
                        <i class="{{ $icon }}"></i> {!! $statusText !!}
                    </span><br>
                @endforeach
                <div class="modal-footer">
                    <button type="button" id="modalClose" class="btn btn-secondary"
                        data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
</div>
