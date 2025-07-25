@if ($dataPeminjaman->isEmpty())
    <div class="alert alert-info" role="alert">
        <div class="d-flex">
            <div>
                <!-- Download SVG icon from http://tabler-icons.io/i/info-circle -->
                <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24"
                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0"></path>
                    <path d="M12 9h.01"></path>
                    <path d="M11 12h1v4h1"></path>
                </svg>
            </div>
            <div>
                Data Tidak Ditemukan!
            </div>
        </div>
    </div>
@else
    @foreach ($dataPeminjaman as $peminjaman)
        <div class="card mb-2">
            <div class="card-body p-2 px-3">
                <h3 class="text-danger mb-1"><strong>Kegiatan :</strong>
                    {{ $peminjaman->kegiatan }}
                </h3>

                <div>
                    <i class="fa fa-calendar"></i>
                    <strong>
                        Mulai :
                    </strong>
                    {{ \Carbon\Carbon::parse($peminjaman->waktu_peminjaman)->format('d M Y H:i') }}
                </div>

                <div><i class="fa fa-calendar-check"></i><strong>
                        Selesai :
                    </strong> {{ \Carbon\Carbon::parse($peminjaman->waktu_pengembalian)->format('d M Y H:i') }}
                </div>
                <div>
                    <i class="fa fa-building"></i>
                    <strong>
                        Ruangan :
                    </strong>
                    {{ $peminjaman->ruangan->nama_ruangan ?? '-' }}
                </div>
                <div class="bet">
                    @php
                        $bg = '';
                        $konfirmasi = '';
                        if ($peminjaman->konfirmasi == 'menunggu') {
                            $bg = 'orange';
                        } elseif ($peminjaman->konfirmasi == 'disetujui') {
                            $bg = 'green';
                        } elseif ($peminjaman->konfirmasi == 'ditolak') {
                            $bg = 'red';
                        } elseif ($peminjaman->konfirmasi == 4) {
                            $bg = 'azure';
                        } else {
                            $bg = 'green';
                        }
                    @endphp

                    <span class="btn badge bg-{{ $bg }} text-{{ $bg }}-fg "
                        style="margin-bottom: 4px !important; margin-top: 4px !important" id="btn-konfirmasi" data-id="{{ encrypt($peminjaman->id) }}">
                        Status Peminjaman : {{ $peminjaman->konfirmasi }}
                    </span><br>
                    <span class="badge bg-blue text-blue-fg btn btn-detail" id="btn-detail" data-id="{{ $peminjaman->id }}"><i
                            class="fa fa-eye"></i>
                        detail barang
                    </span>
                    @if ($peminjaman->konfirmasi == 'menunggu')
                        <a href="{{ route('mahasiswa.peminjaman.edit', encrypt($peminjaman->id)) }}"
                            class="badge bg-green text-green-fg btn btn-edit" data-id="{{ $peminjaman->id }}"><i
                                class="fas fa-edit"></i>
                            Edit</a>
                        <form action="{{ route('mahasiswa.peminjaman.destroy', $peminjaman->id) }}" method="post"
                            id="deleteForm" style="display: inline-block">
                            @csrf
                            @method('delete')
                            <button type="submit" onclick="confirmDelete(event)" class="badge bg-red text-red-fg"><i
                                    class="fa fa-times"></i>
                                hapus</button>

                        </form>
                    @endif

                    @if ($peminjaman->konfirmasi == 'disetujui')
                        <a href="{{ route('mahasiswa.peminjaman.cetak', encrypt($peminjaman->id)) }}" target="__blank"
                            class="badge bg-yellow text-yellow-fg btn btn-add" data-id="{{ $peminjaman->id }}"><i
                                class="fa fa-print"></i>
                            Cetak</a>
                    @endif
                </div>
                <div class="mt-1">
                    <a href="https://www.instagram.com/pustik127/" target="_blank"
                        class="badge bg-teal text-teal-fg btn ">
                        Hubungi : Pustik127 <svg xmlns="http://www.w3.org/2000/svg"
                            class="icon icon-tabler icon-tabler-brand-instagram" width="44" height="44"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M4 4m0 4a4 4 0 0 1 4 -4h8a4 4 0 0 1 4 4v8a4 4 0 0 1 -4 4h-8a4 4 0 0 1 -4 -4z" />
                            <path d="M12 12m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                            <path d="M16.5 7.5l0 .01" />
                        </svg></a>
                </div>
            </div>
        </div>
        <div id="tempat-modal"></div>
    @endforeach

@endif

<script>
    // Tombol Detail Barang
            $(document).on("click", ".btn-detail", function() {
                    var id = $(this).attr("data-id");
                    var url = "{{ route('mahasiswa.peminjaman.detail', ':id_data') }}";
                    url = url.replace(":id_data", id);
                    $.ajax({
                            method: "GET",
                            url: url,
                        })
                        .done(function(data) {
                            $('#tempat-modal').html(data.html);
                            $('#modal_detail').modal('show');
                        })
                })

    function confirmDelete(event) {
        // Menampilkan pesan konfirmasi
        var confirmation = confirm("Apakah Anda yakin ingin menghapus data ini?");


        if (confirmation) {
            // Lakukan submit form
            $(event.target).closest('form').submit();
        } else {
            // Batalkan aksi default klik tombol
            event.preventDefault();
        }
    }
</script>
