<div class="table-responsive">
    <table class="table table-striped table-bordered " id="datatables">
        <thead style="text-align: center !important">
            <tr>
                <th width="5%">No</th>
                <th>Peminjaman</th>
                <th>Waktu</th>
                <th>Detail Peminjaman</th>
                <th>Status Approval</th>
                <th>Status Peminjaman</th>

            </tr>
        </thead>
        <tbody>

            @foreach ($riwayatPeminjaman as $riwayat)
                <tr class="odd gradeX ">
                    <td class="center">{{ $loop->iteration }}</td>

                    <td class="center">
                        <div><b>Peminjam :</b> {{ Str::title($riwayat->user->name) }}</div>
                        <div><b> Kegiatan :</b> {{ $riwayat->kegiatan }}</div>
                    </td>

                    {{-- KOLOM WAKTU --}}
                    <td style="min-width:100px; white-space: nowrap;" class="small">
                        <strong class="text-success">Mulai:</strong>
                        <div>
                            {{ \Carbon\Carbon::parse($riwayat->waktu_peminjaman)->isoFormat('D MMM Y, hh:mm') }}
                        </div>

                        <strong class="text-danger mt-2">Selesai:</strong>
                        <div>
                            {{ \Carbon\Carbon::parse($riwayat->waktu_pengembalian)->isoFormat('D MMM Y, hh:mm') }}
                        </div>
                    </td>
                    <td>
                        {{-- Tampilkan Ruangan jika ada --}}
                        @if ($riwayat->ruangan)
                            <div>
                                <strong>Ruang:</strong> {{ $riwayat->ruangan->nama_ruangan }}
                            </div>
                            {{-- Tampilkan status kunci jika relevan --}}
                            @if (in_array($riwayat->status_peminjaman, ['disetujui', 'aktif', 'selesai']))
                                <span class="btn badge bg-blue-lt" id="btn-status-ruangan"
                                    data-id="{{ $riwayat->id }}">
                                    Status :
                                    {{ Str::title(str_replace('_', ' ', $riwayat->status_ruangan ?? 'Disetujui')) }}
                                </span>
                            @endif
                        @endif

                        {{-- Tampilkan Tombol Detail Barang jika ada barang yang dipinjam --}}
                        @if (!$riwayat->detail_peminjaman->isEmpty())
                            <div class="{{ $riwayat->ruangan ? 'mt-2' : '' }}">
                                <strong>Barang:</strong><br>
                                <button class="btn btn-sm btn-info btn-detail rounded" data-id="{{ $riwayat->id }}">
                                    <i class="fa fa-eye me-1"></i>Detail Barang
                                </button>
                            </div>
                        @endif

                        {{-- Tampilkan pesan jika tidak ada aset sama sekali --}}
                        @if (!$riwayat->ruangan && $riwayat->detail_peminjaman->isEmpty())
                            <span class="text-muted">- Tidak ada aset -</span>
                        @endif
                    </td>


                    <td>
                        @foreach ($riwayat->persetujuan_peminjaman as $persetujuan)
                            @php
                                $role = $persetujuan->approval_role;
                                $unit = $persetujuan->unit_kerja->kode ?? $role;
                                $user = $persetujuan->user?->name;
                                $status = $persetujuan->status;
                                $statusText = '';

                                // Tampilan status approve berdasarkan role
                                if ($role === 'kerumahtanggan') {
                                    $statusText = "<b>$unit</b> $status" . ($user ? " oleh $user" : '');
                                } elseif ($role === 'kaprodi') {
                                    $statusText =
                                        '<b>kaprodi ' .
                                        ($persetujuan->peminjaman->user->prodi->kode_prodi ?? '') .
                                        '</b> ' .
                                        $status .
                                        ($user ? " oleh $user" : '');
                                } else {
                                    $statusText = "<b>Tata Usaha $unit</b> $status" . ($user ? " oleh $user" : '');
                                }

                                // Tampilan warna approve berdasarkan statusnya
                                switch ($persetujuan->status) {
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
                            <span class="badge {{ $bg }} d-inline-block mb-1 text-light">
                                <i class="{{ $icon }}"></i> {!! $statusText !!}
                            </span><br>
                        @endforeach
                    </td>


                    <td>
                        @switch($riwayat->status_peminjaman)
                            @case('menunggu')
                                <small class="text-xs badge bg-yellow text-yellow-fg">
                                    <i class="fa fa-spinner fa-spin"></i> menunggu
                                    konfirmasi
                                </small>
                            @break

                            @case('disetujui')
                                <small class="text-xs badge bg-azure text-azure-fg">
                                    <i class="fa fa-info"></i> Disetujui
                                </small>
                            @break

                            @case('ditolak')
                                <small class="text-xs badge bg-red text-red-fg">
                                    <i class="fa fa-times"></i>
                                    Ditolak
                                </small>
                            @break

                            @case('aktif')
                                <small class="text-xs badge bg-warning text-warning-fg">
                                    <i class="fa fa-clock"></i> Aktif/Sedang Dipinjam
                                </small>
                            @break

                            @case('selesai')
                                <small class="text-xs badge bg-green text-green-fg">
                                    <i class="fa fa-check-double "></i> Selesai/Dikembalikan
                                </small>
                            @break

                            @default
                        @endswitch
                    </td>

                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@push('js')
    <script>
        $(document).ready(function() {
            $('#datatables').DataTable();
        });
    </script>
@endpush
