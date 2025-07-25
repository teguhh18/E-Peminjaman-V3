<table class="table table-striped table-bordered " id="datatables">
    <thead style="text-align: center !important">
        <tr>
            <th width="5%">No</th>
            <th width="10%">Aksi</th>
            <th>Detail Peminjaman</th>
            <th>Waktu</th>
            <th>Ruangan</th>
            <th>Status Approval</th>
            <th>Status Peminjaman</th>

        </tr>
    </thead>
    <tbody>

        @foreach ($dataBooking as $booking)
            <tr class="odd gradeX ">
                <td class="center">{{ $loop->iteration }}</td>
                <td>
                   

                    {{-- Tombol Whatsapp --}}
                    <a style="margin-top: 5px;"
                        href="https://api.whatsapp.com/send?phone={{ $booking->no_peminjam }}&text=Pesan"
                        target="_blank" class="btn btn-sm btn-success">
                        <i class="fa fa-comment"></i> &nbsp; WhatsApp
                    </a>

                    {{-- Hanya Admin Bisa Edit --}}
                    <a style="margin-top: 5px;" href="{{ route('admin.peminjaman.edit', encrypt($booking->id)) }}"
                        class="btn btn-sm btn-warning">
                        <i class="fa fa-edit"></i> &nbsp; Edit
                    </a>
                </td>
                <td class="center">
                    <div><b>Peminjam :</b> {{ $booking->user->name }}</div>
                    <div><b> Kegiatan :</b> {{ $booking->kegiatan }}</div>
                </td>

                <td>
                    <div>
                        <strong class="text-success">
                            Mulai:
                        </strong>
                        <span>
                            {{ \Carbon\Carbon::parse($booking->waktu_peminjaman)->isoFormat(' D MMMM YYYY [pukul] HH:mm') }}
                        </span>
                    </div>
                    <div>
                        <strong class="text-danger">
                            Selesai:
                        </strong>
                        <span>
                            {{ \Carbon\Carbon::parse($booking->waktu_pengembalian)->isoFormat(' D MMMM YYYY [pukul] HH:mm') }}
                        </span>
                    </div>
                </td>
                <td>
                    @if ($booking->ruangan != null)
                        {{-- BAGIAN INI DILIHAT OLEH SEMUA ORANG --}}
                        <div>{{ $booking->ruangan->nama_ruangan }}</div>
                        <div>{{ $booking->ruangan->gedung->nama }}, Lt. {{ $booking->ruangan->lantai }}</div>

                        {{-- Tampilkan status kunci jika peminjaman sudah disetujui atau aktif --}}
                        @if (in_array($booking->status_peminjaman, ['disetujui', 'aktif', 'selesai']))
                            <div class="mt-1">
                                <span class="badge bg-primary text-light">
                                    Status:
                                    {{ $booking->status_ruangan ?? 'Disetujui' }}
                                </span>
                            </div>
                        @endif
                    @else
                        <span class="text-muted">- Tidak Pinjam Ruangan -</span>
                    @endif
                </td>

                <td>
                    @foreach ($booking->persetujuan_peminjaman as $persetujuan)
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
                        @if ($persetujuan->approval_role === 'kaprodi')
                            <span class="btn badge {{ $bg }} d-inline-block mb-1 text-light" id="btn-konfirmasi" data-id="{{ $persetujuan->id }}">
                            <i class="{{ $icon }}"></i> {!! $statusText !!}
                        </span><br>
                        @else
                        <span class="badge {{ $bg }} d-inline-block mb-1 text-light">
                            <i class="{{ $icon }}"></i> {!! $statusText !!}
                        </span><br>
                        @endif
                        
                    @endforeach
                    </td>


                <td>

                
                <button class="badge bg-azure text-azure-fg btn-detail mb-1" id="btn-detail"
                    data-id="{{ $booking->id }}">
                    <i class="far fa-eye"></i> Detail Barang
                </button>
                @switch($booking->status_peminjaman)
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
                            <i class="fa fa-xmark "></i>
                            Ditolak
                        </small>
                    @break

                    @case('aktif')
                        <small class="text-xs badge bg-warning text-warning-fg">
                            <i class="fa fa-xmark "></i> Aktif/Sedang Dipinjam
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
@push('js')
    <script>
        $(document).ready(function() {
            $('#datatables').DataTable();
        });
    </script>
@endpush
