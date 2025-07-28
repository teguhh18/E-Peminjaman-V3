<div class="modal fade modal-alert modal-info in" id="modal_detail" role="dialog" style="padding:0;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">Detail Barang</div>
            </div>
            <div class="modal-body">
                @if ($detailPeminjaman->isEmpty())
                    <div class="text-center py-4">
                        <div class="mb-3">
                            <i class="fas fa-box-open fa-2x text-muted"></i>
                        </div>
                        <h4 class="text-muted mb-3">Tidak Ada Barang Yang Dipinjam</h4>
                    </div>
                @else
                    <table class="table table-striped table-bordered " id="datatables">
                        <thead style="text-align: center !important">
                            <tr>
                                <th width="5%">No</th>
                                <th>Nama Barang</th>
                                <th>Jumlah</th>
                                <th>Lokasi Barang</th>
                                <th>Status</th>
                                {{-- <th>Aksi</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($detailPeminjaman as $detail)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $detail->barang->nama }}</td>
                                    <td>{{ $detail->jml_barang }}</td>
                                    <td>
                                        Ruang : {{ $detail->barang->ruangan->nama_ruangan }} <br>
                                        Gedung : {{ $detail->barang->ruangan->gedung->nama }} <br>
                                        Unit Kerja : {{ $detail->barang->ruangan->unitkerja->nama }}
                                    </td>
                                    <td>
                                        {{-- Set Tampilan warna approve dan icon berdasarkan statusnya --}}
                                        @switch ($detail->status)
                                            @case ('disetujui')
                                                @php
                                                    $bg = 'bg-success';
                                                    $icon = 'fa fa-check-circle me-1';
                                                @endphp
                                            @break

                                            @case ('dikembalikan')
                                                @php
                                                    $bg = 'bg-primary';
                                                    $icon = 'fa fa-check-circle me-1';
                                                @endphp
                                            @break

                                            @case ('diambil')
                                                @php
                                                    $bg = 'bg-warning';
                                                    $icon = 'fa fa-exclamation-circle me-1';
                                                @endphp
                                            @break

                                            @case ('bermasalah')
                                                @php
                                                    $bg = 'bg-danger';
                                                    $icon = 'fa fa-exclamation-triangle me-1';
                                                @endphp
                                            @break

                                            @default
                                                @php
                                                    $bg = 'bg-warning';
                                                    $icon = 'fa fa-spinner fa-spin';
                                                @endphp
                                        @endswitch
                                        @php
                                            $user = auth()->user();
                                            $isMyApproval = false;

                                            if ($user->level === 'admin') {
                                               $isMyApproval = true;
                                            }
                                            elseif ($user->level === 'baak' && $user->unitkerja_id == $detail->barang->unitkerja_id) {
                                                $isMyApproval = true;
                                            }
                                        @endphp

                                        @if ($isMyApproval && $detail->peminjaman->status_peminjaman != 'menunggu')
                                            <span class="btn badge {{ $bg }} d-inline-block mb-1 text-light" id="btn-status-barang" data-id="{{ $detail->id }}">
                                                <i class="{{ $icon }}"></i> {{ $detail->status ?? 'menunggu' }}
                                            </span>
                                        @else
                                            {{-- Tampilkan Status berdasarkan hasil Switch Case --}}
                                            <span class="badge {{ $bg }} d-inline-block mb-1 text-light">
                                                <i class="{{ $icon }}"></i>
                                                {{ $detail->status ?? 'menunggu' }}
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
