<div class="table-responsive">
    <table class="table table-striped table-bordered " id="datatables">
        <thead style="">
            <tr>
                <th width="5%">No</th>
                <th width="5%">Aksi</th>
                <th>Peminjaman</th>
                <th>Waktu</th>
                <th>Detail Peminjaman</th>
                <th>Status Approval</th>
                <th width="5%">Status Peminjaman</th>

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

                        <a style="margin-top: 5px;" href="{{ route('admin.peminjaman.edit', encrypt($booking->id)) }}"
                            class="btn btn-sm btn-warning">
                            <i class="fa fa-edit"></i> &nbsp; Edit
                        </a>
                    </td>
                    <td class="center">
                        <div><b>Peminjam :</b> {{ $booking->user->name }}</div>
                        <div><b> Kegiatan :</b> {{ $booking->kegiatan }}</div>
                    </td>

                    {{-- KOLOM WAKTU --}}
                    <td style="min-width:100px; white-space: nowrap;" class="small">
                        <strong class="text-success">Mulai:</strong>
                        <div>
                            {{ \Carbon\Carbon::parse($booking->waktu_peminjaman)->isoFormat('D MMM Y, hh:mm') }}
                        </div>

                        <strong class="text-danger mt-2">Selesai:</strong>
                        <div>
                            {{ \Carbon\Carbon::parse($booking->waktu_pengembalian)->isoFormat('D MMM Y, hh:mm') }}
                        </div>
                    </td>
                    <td>
                        {{-- Tampilkan Ruangan jika ada --}}
                        @if ($booking->ruangan)
                            <div>
                                <strong>Ruang:</strong> {{ $booking->ruangan->nama_ruangan }}
                            </div>
                            {{-- Tampilkan status ruagan jika relevan --}}
                            @if (in_array($booking->status_peminjaman, ['disetujui', 'aktif', 'selesai']))
                                <span class="badge bg-blue-lt">
                                    Status :
                                    {{ Str::title(str_replace('_', ' ', $booking->status_ruangan ?? 'Disetujui')) }}
                                </span>
                            @endif
                        @endif

                        {{-- Tampilkan Tombol Detail Barang jika ada barang yang dipinjam --}}
                        @if (!$booking->detail_peminjaman->isEmpty())
                            <div class="{{ $booking->ruangan ? 'mt-2' : '' }}">
                                <strong>Barang:</strong>
                                <button class="btn btn-sm btn-info btn-detail rounded" data-id="{{ $booking->id }}">
                                    <i class="fa fa-eye me-1"></i>Detail Barang
                                </button>
                            </div>
                        @endif

                        {{-- Tampilkan pesan jika tidak ada aset sama sekali --}}
                        @if (!$booking->ruangan && $booking->detail_peminjaman->isEmpty())
                            <span class="text-muted">- Tidak ada aset -</span>
                        @endif
                    </td>

                    <td>
                        @foreach ($booking->persetujuan_peminjaman as $persetujuan)
                            @php
                                $unit = $persetujuan->unit_kerja->kode;
                                $user = $persetujuan->user?->name;
                                $status = $persetujuan->status;
                                $statusText = '';

                               
                                $statusText = "<b>Tata Usaha $unit</b> $persetujuan->status" . ($user ? " oleh $user" : '');
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

                            @if ($persetujuan->unitkerja_id == auth()->user()->unitkerja_id)
                                <span class="btn badge {{ $bg }} d-inline-block mb-1 text-light"
                                    id="btn-konfirmasi" data-id="{{ $persetujuan->id }}">
                                    <i class="{{ $icon }}"></i> {!! $statusText !!}
                                </span><br>
                            @else
                                <span class="badge {{ $bg }} d-inline-block mb-1 text-light"
                                    id="btn-konfirmasi" data-id="{{ $persetujuan->id }}">
                                    <i class="{{ $icon }}"></i> {!! $statusText !!}
                                </span><br>
                            @endif
                        @endforeach
                    </td>
                    <td>
                        @switch($booking->status_peminjaman)
                            @case('menunggu')
                                <small class="text-xs badge bg-yellow text-yellow-fg mt-1">
                                    <i class="fa fa-spinner fa-spin"></i> menunggu
                                    konfirmasi
                                </small>
                            @break

                            @case('disetujui')
                                <small class="text-xs badge bg-azure text-azure-fg mt-1">
                                    <i class="fa fa-info"></i> Disetujui
                                </small>
                            @break

                            @case('ditolak')
                                <small class="text-xs badge bg-red text-red-fg mt-1">
                                    <i class="fa fa-xmark "></i>
                                    Ditolak
                                </small>
                            @break

                            @case('aktif')
                                <small class="text-xs badge bg-warning text-warning-fg mt-1">
                                    <i class="fa fa-xmark "></i> Aktif/Sedang Dipinjam
                                </small>
                            @break

                            @case('selesai')
                                <small class="text-xs badge bg-green text-green-fg mt-1">
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
