<div class="table-responsive">
    <table class="table table-striped table-bordered " id="datatables">
        <thead style="text-align: center !important">
            <tr>
                <th class="text-center" width="5%">No</th>
                <th class="text-center" width="10%">Aksi</th>
                <th class="text-center">Ruangan</th>
                <th class="text-center">Gedung</th>
                <th>Lantai</th>
                <th class="text-center">Kapasitas (Orang)</th>
                <th>Bisa Dipinjam</th>
                {{-- <th>Kondisi Ruangan</th> --}}
                <th>Penanggung Jawab</th>
            </tr>
        </thead>
        <tbody> @php
            // dd($dataRuangan);
        @endphp
            @foreach ($dataRuangan as $ruang)
                <tr class="odd gradeX">
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td class="text-center">
                        <a href="{{ route('admin.ruangan.edit', $ruang->id) }}" class="btn btn-primary btn-sm btn-update">
                            <i class="fa fa-edit"></i>
                        </a>
                        <a href="javascript:;" data-id="{{ $ruang->id }}" class="btn btn-danger btn-sm btn-delete">
                            <i class="fa fa-trash"></i>
                        </a>
                    </td>
    
                    <td>
                        <b>{{ $ruang->kode_ruangan }}</b> <br>
                        {{ $ruang->nama_ruangan }}
                    </td>
                    <td>
                        {{-- <b>{{ $ruang->gedung->kode }}</b> <br> --}}
                        {{ $ruang->gedung->nama }}
                    </td>
                    <td>Lantai {{ $ruang->lantai }}</td>
                    <td class="text-center"> {{ $ruang->kapasitas }}</td>
                    <td> {{ $ruang->bisa_pinjam === 1 ? "Bisa" : "Tidak Bisa" }}</td>
                    {{-- <td> {{ $ruang->kondisi }}</td> --}}
                    @php
                        // dd($ruang->unitkerja->nama);
                    @endphp
                    <td>{{ $ruang->unitkerja->nama }}</td>
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
