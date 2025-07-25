<div class="table-responsive">
    <table class="table table-responsive table-striped table-bordered" id="datatables">
        <thead style="text-align: center !important">
            <tr>
                <th width="5%">No</th>
                <th>Kegiatan</th>
                <th>Waktu</th>
                <th>Ruangan</th>
                <th>Status Approval</th>
                <th>Status Peminjaman</th>
                <th width="10%">Aksi</th>

            </tr>
        </thead>
        <tbody>

            @foreach ($dataPeminjaman as $peminjaman)
                <tr class="odd gradeX ">
                    <td class="center">{{ $loop->iteration }}</td>

                    <td class="center">
                        <div><b> Kegiatan :</b> {{ $peminjaman->kegiatan }}</div>
                    </td>

                    <td>
                        <div>
                            <strong class="text-success">
                                Mulai:
                            </strong>
                            <span>
                                {{ \Carbon\Carbon::parse($peminjaman->waktu_peminjaman)->isoFormat(' D MMMM YYYY HH:mm') }}
                            </span>
                        </div>
                        <div>
                            <strong class="text-danger">
                                Selesai:
                            </strong>
                            <span>
                                {{ \Carbon\Carbon::parse($peminjaman->waktu_pengembalian)->isoFormat(' D MMMM YYYY HH:mm') }}
                            </span>
                        </div>
                    </td>
                    <td>
                        @if ($peminjaman->ruangan != null)
                            {{-- BAGIAN INI DILIHAT OLEH SEMUA ORANG --}}
                            <div>{{ $peminjaman->ruangan->nama_ruangan }}</div>
                            <div>{{ $peminjaman->ruangan->gedung->nama }}, Lt. {{ $peminjaman->ruangan->lantai }}</div>

                            {{-- Tampilkan status kunci jika peminjaman sudah disetujui atau aktif --}}
                            @if (in_array($peminjaman->status_peminjaman, ['disetujui', 'aktif', 'selesai']))
                                <div class="mt-1">
                                    @switch($peminjaman->status_ruangan)
                                        @case('disetujui')
                                            <small class="text-xs badge bg-success text-yellow-fg">
                                                <i class="fa fa-check"></i> disetujui
                                            </small>
                                        @break

                                        @case('kunci_diambil')
                                        <small class="text-xs badge bg-yellow text-yellow-fg">
                                                <i class="fa fa-exclamation-circle"></i> Kunci Diambil
                                            </small>
                                        @break

                                        @case('kunci_dikembalikan')
                                         <small class="text-xs badge bg-blue text-yellow-fg">
                                                <i class="fa fa-check-double"></i> Kunci Dikembalikan
                                            </small>
                                        @break

                                        @case('bermasalah')
                                        <small class="text-xs badge bg-red text-yellow-fg">
                                                <i class="fa fa-exclamation-triangle"></i> Bermasalah
                                            </small>
                                        @break

                                        @default
                                            <small class="text-xs badge bg-yellow text-yellow-fg">
                                                <i class="fa fa-spinner fa-spin"></i> menunggu
                                                konfirmasi
                                            </small>
                                    @endswitch
                                </div>
                            @endif
                        @else
                            <small class="text-muted">Tidak Pinjam Ruangan</small>
                        @endif
                    </td>

                    <td>
                        @foreach ($peminjaman->persetujuan_peminjaman as $persetujuan)
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
                        @switch($peminjaman->status_peminjaman)
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
                    <td>
                        <button class="badge bg-azure text-azure-fg btn-detail mb-1" id="btn-detail"
                            data-id="{{ $peminjaman->id }}">
                            <i class="far fa-eye"></i> Detail Barang
                        </button>
                        @if ($peminjaman->status_peminjaman == 'menunggu')
                            <a href="{{ route('mahasiswa.peminjaman.edit', encrypt($peminjaman->id)) }}"
                                class="badge bg-green text-green-fg btn btn-edit" data-id="{{ $peminjaman->id }}"><i
                                    class="fas fa-edit"></i>
                                Edit</a>
                            <form action="{{ route('mahasiswa.peminjaman.destroy', $peminjaman->id) }}" method="post"
                                id="deleteForm" style="display: inline-block">
                                @csrf
                                @method('delete')
                                <button type="submit" onclick="confirmDelete(event)"
                                    class="badge bg-red text-red-fg mt-2"><i class="fa fa-times"></i>
                                    hapus</button>

                            </form>
                        @endif

                        @if (in_array($peminjaman->status_peminjaman, ['disetujui', 'aktif', 'selesai']))
                            <a href="{{ route('mahasiswa.peminjaman.cetak', encrypt($peminjaman->id)) }}"
                                target="__blank" class="badge bg-yellow text-yellow-fg btn btn-add"
                                data-id="{{ $peminjaman->id }}"><i class="fa fa-print"></i>
                                Cetak</a>
                        @endif
</div>
{{-- <div class="mt-1">
                        <a href="https://www.instagram.com/pustik127/" target="_blank"
                            class="badge bg-teal text-teal-fg btn ">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-brand-instagram">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M4 8a4 4 0 0 1 4 -4h8a4 4 0 0 1 4 4v8a4 4 0 0 1 -4 4h-8a4 4 0 0 1 -4 -4z" />
                                <path d="M9 12a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" />
                                <path d="M16.5 7.5v.01" />
                            </svg>
                            Hubungi Pustik127
                        </a>
                    </div> --}}
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
