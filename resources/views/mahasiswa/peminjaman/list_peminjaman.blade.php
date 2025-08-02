<div class="table-responsive">
    <table class="table table-responsive table-striped table-bordered" id="datatables">
        <thead style="text-align: center !important">
            <tr>
                <th width="5%">No</th>
                <th>Kegiatan</th>
                <th>Waktu</th>
                <th>Peminjaman</th> {{-- <-- KOLOM BARU --}}
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

                    {{-- KOLOM WAKTU --}}
                    <td style="min-width:100px; white-space: nowrap;" class="small">
                        <strong class="text-success">Mulai:</strong>
                        <div>
                            {{ \Carbon\Carbon::parse($peminjaman->waktu_peminjaman)->isoFormat('D MMM Y, hh:mm') }}
                        </div>

                        <strong class="text-danger mt-2">Selesai:</strong>
                        <div>
                            {{ \Carbon\Carbon::parse($peminjaman->waktu_pengembalian)->isoFormat('D MMM Y, hh:mm') }}
                        </div>
                    </td>
                    <td>
                        {{-- Tampilkan Ruangan jika ada --}}
                        @if ($peminjaman->ruangan)
                            <div>
                                <strong>Ruang:</strong> {{ $peminjaman->ruangan->nama_ruangan }}
                            </div>
                            {{-- Tampilkan status kunci jika relevan --}}
                            @if (in_array($peminjaman->status_peminjaman, ['disetujui', 'aktif', 'selesai']))
                                <span class="badge bg-blue-lt">
                                    Status :
                                    {{ Str::title(str_replace('_', ' ', $peminjaman->status_ruangan ?? 'Disetujui')) }}
                                </span>
                            @endif
                        @endif

                        {{-- Tampilkan Tombol Detail Barang jika ada barang yang dipinjam --}}
                        @if (!$peminjaman->detail_peminjaman->isEmpty())
                            <div class="{{ $peminjaman->ruangan ? 'mt-2' : '' }}">
                                <strong>Barang:</strong>
                                <span class="btn badge bg-azure text-white btn-detail" data-id="{{ $peminjaman->id }}">
                                    <i class="fa fa-eye me-1"></i>Detail Barang
                                </span>
                            </div>
                        @endif

                        {{-- Tampilkan pesan jika tidak ada aset sama sekali --}}
                        @if (!$peminjaman->ruangan && $peminjaman->detail_peminjaman->isEmpty())
                            <span class="text-muted">- Tidak ada aset -</span>
                        @endif
                    </td>

                    <td>
                        @foreach ($peminjaman->persetujuan_peminjaman as $persetujuan)
                            @php
                                $unit = $persetujuan->unit_kerja->kode;
                                // $user = $persetujuan->user?->name;
                                $status = $persetujuan->status;
                                $statusText = '';
                                    // $statusText = "<b>Tata Usaha $unit</b> $status" . ($user ? " oleh $user" : '');
                                    $statusText = "$status <b>Tata Usaha $unit</b> ";

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
                                <small class="text-xs badge bg-warning text-warning-fg">
                                    <i class="fa fa-spinner fa-spin"></i> menunggu
                                    konfirmasi
                                </small>
                            @break

                            @case('disetujui')
                                <small class="text-xs badge bg-azure text-blue-fg">
                                    <i class="fa fa-check"></i> Disetujui
                                </small>
                            @break

                            @case('ditolak')
                                <small class="text-xs badge bg-red text-red-fg">
                                    <i class="fa fa-times"></i>
                                    Ditolak
                                </small>
                            @break

                            @case('aktif')
                                <small class="text-xs badge bg-orange text-orange-fg">
                                    <i class="fa fa-clock"></i> Aktif/Sedang Dipinjam
                                </small>
                            @break

                            @case('selesai')
                                <small class="text-xs badge bg-green text-green-fg">
                                    <i class="fa fa-check-double"></i> Selesai/Dikembalikan
                                </small>
                            @break

                            @default
                        @endswitch
                    </td>
                    <td>

                        @if ($peminjaman->status_peminjaman == 'menunggu')
                            <a href="{{ route('mahasiswa.peminjaman.edit', encrypt($peminjaman->id)) }}"
                                class="badge bg-green text-green-fg btn btn-edit" data-id="{{ $peminjaman->id }}"><i
                                    class="fas fa-edit me-1"></i>
                                Edit
                            </a>
                            <button type="submit" id="btn-delete" data-id="{{ encrypt($peminjaman->id) }}"
                                class="badge bg-red text-red-fg mt-2"><i class="fa fa-times"></i>
                                hapus
                            </button>
                        @endif

                        @if (in_array($peminjaman->status_peminjaman, ['disetujui', 'aktif', 'selesai']))
                            <a href="{{ route('mahasiswa.peminjaman.cetak', encrypt($peminjaman->id)) }}"
                                target="__blank" class="badge bg-yellow text-yellow-fg btn btn-add"
                                data-id="{{ $peminjaman->id }}"><i class="fa fa-print me-1"></i>
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
