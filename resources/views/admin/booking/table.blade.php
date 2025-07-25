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

                    @foreach ($booking->persetujuan_peminjaman as $persetujuan)
                        @php
                            $user = auth()->user();
                            $isMyApproval = false;

                            if (
                                $user->level === 'baak' &&
                                $persetujuan->approval_role === 'baak' &&
                                $persetujuan->unitkerja_id == $user->unitkerja_id
                            ) {
                                $isMyApproval = true;
                            } elseif (
                                $user->level === 'kerumahtanggan' &&
                                $persetujuan->approval_role === 'kerumahtanggan'
                            ) {
                                $isMyApproval = true;
                            } elseif (
                                $user->level === 'kaprodi' &&
                                $persetujuan->approval_role === 'kaprodi' &&
                                $persetujuan->peminjaman->user->prodi_id == $user->prodi_id
                            ) {
                                $isMyApproval = true;
                            }
                        @endphp

                        @if ($isMyApproval)
                            <a href="javascript:;" data-id="{{ $persetujuan->id }}"
                                data-peminjaman-id="{{ $booking->id }}" class="btn btn-info btn-sm btn-confirm mb-1">
                                <i class="fab fa-confluence"></i> &nbsp;
                                Konfirmasi
                            </a>
                        @endif
                    @endforeach
                    {{-- 1. Tampilkan blok ini HANYA JIKA status peminjaman sudah disetujui atau aktif --}}
                    @if (in_array($booking->konfirmasi, [2, 4]))
                        {{-- 2=Disetujui, 4=Sedang Dipinjam --}}

                        {{-- LOGIKA UNTUK TOMBOL STATUS BARANG --}}
                        {{-- Lakukan perulangan untuk setiap barang yang dipinjam --}}
                        @foreach ($booking->detail_peminjaman as $detail)
                            {{-- Cek apakah user yang login adalah staff BAAK/Unit Kerja yang berwenang untuk barang INI --}}
                            @if (auth()->user()->level == 'baak' && auth()->user()->unitkerja_id == $detail->barang->ruangan->unitkerja_id)
                                <a style="margin-top: 5px;" data-id="{{ $detail->id }}"
                                    class="btn btn-sm btn-dark btn-status-barang">
                                    <i class="fa fa-edit"></i> &nbsp;
                                    Status Barang
                                </a>
                            @endif
                        @endforeach
                    @endif

                    {{-- Tombol Whatsapp --}}
                    <a style="margin-top: 5px;"
                        href="https://api.whatsapp.com/send?phone={{ $booking->no_peminjam }}&text=Pesan"
                        target="_blank" class="btn btn-sm btn-success">
                        <i class="fa fa-comment"></i> &nbsp; WhatsApp
                    </a>

                    {{-- Hanya Admin Bisa Edit --}}
                    @if (auth()->user()->level === 'admin')
                        <a style="margin-top: 5px;" href="{{ route('admin.peminjaman.edit', encrypt($booking->id)) }}"
                            class="btn btn-sm btn-warning">
                            <i class="fa fa-edit"></i> &nbsp; Edit
                        </a>
                    @endif
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
                        @if (in_array($booking->konfirmasi, [2, 4, 5]))
                            <div class="mt-1">
                                <span class="badge bg-primary text-light">
                                    Status:
                                    {{ Str::title(str_replace('_', ' ', $booking->status_ruangan ?? 'Disetujui')) }}
                                </span>
                            </div>
                        @endif

                        {{-- ======================================================= --}}
                        {{-- BAGIAN TOMBOL AKSI (HANYA UNTUK YANG BERWENANG) --}}
                        {{-- ======================================================= --}}

                        {{-- Tombol hanya akan muncul jika peminjaman sudah disetujui/aktif DAN user berwenang --}}
                        @if (in_array($booking->konfirmasi, [2, 4]) &&
                                auth()->user()->level == 'baak' &&
                                auth()->user()->unitkerja_id == $booking->ruangan->unitkerja_id)
                            <div class="mt-2">
                                <a href="javascript:;" data-id="{{ $booking->id }}"
                                    class="btn btn-sm btn-primary btn-status-ruangan">
                                    <i class="fa fa-edit"></i> &nbsp; Ubah Status
                                </a>
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
                        <span class="badge {{ $bg }} d-inline-block mb-1 text-light">
                            <i class="{{ $icon }}"></i> {!! $statusText !!}
                        </span><br>
                    @endforeach
                </td>


                <td>
                    <button class="badge bg-azure text-azure-fg btn-detail mb-1" id="btn-detail"
                        data-id="{{ $booking->id }}">
                        <i class="far fa-eye"></i> Detail Barang
                    </button>
                    @switch($booking->konfirmasi)
                        @case(1)
                            <small class="text-xs badge bg-yellow text-yellow-fg">
                                <i class="fa fa-spinner fa-spin"></i> menunggu
                                konfirmasi
                            </small>
                        @break

                        @case(2)
                            <small class="text-xs badge bg-azure text-azure-fg">
                                <i class="fa fa-info"></i> Disetujui
                            </small>
                        @break

                        @case(3)
                            <small class="text-xs badge bg-red text-red-fg">
                                <i class="fa fa-xmark "></i>
                                Ditolak
                            </small>
                        @break

                        @case(4)
                            <small class="text-xs badge bg-warning text-warning-fg">
                                <i class="fa fa-xmark "></i> Sedang Dipinjam
                            </small>
                        @break

                        @case(5)
                            <small class="text-xs badge bg-green text-green-fg">
                                <i class="fa fa-check-double "></i> Selesai
                            </small>
                        @break

                        {{-- @case('dibatalkan')
                            <small class="text-xs badge bg-green text-green-fg">
                                <i class="fa fa-check-double "></i> Dibatalkan
                            </small>
                        @break --}}

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
