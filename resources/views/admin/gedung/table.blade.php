<table class="table table-striped table-bordered" id="datatables">
    <thead>
        <tr>
            <th class="text-center" width="5%">No</th>
            <th class="text-center" width="10%">Aksi</th>
            <th>Kode Gedung</th>
            <th>Nama Gedung</th>
            <th class="text-center">Lantai</th>
            {{-- <th>Sumber Dana</th> --}}
            <th>Lokasi</th>
            {{-- <th>Nilai Perolehan (Rp.)</th> --}}
            <th>Tahun Perolehan</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($dataGedung as $key)
            <tr class="odd gradeX">
                <td class="text-center">{{ $loop->iteration }}</td>
                <td class="text-center">
                    <a href="{{ route('admin.gedung.edit', $key->id) }}" class="btn btn-primary btn-sm btn-update">
                        <i class="fa fa-edit"></i>
                    </a>

                    <a href="javascript:;" data-id="{{ $key->id }}" class="btn btn-danger btn-sm btn-delete">
                        <i class="fa fa-trash"></i>
                    </a>
                </td>
                <td>{{ $key->kode }}</td>
                <td>{{ $key->nama }}</td>
                <td class="text-center">{{ $key->jumlah_lantai }}</td>
                {{-- <td>{{ $key->sumber_dana }}</td> --}}
                <td>{{ $key->lokasi }}</td>
                {{-- <td>Rp.{{ number_format($key->besar_dana, 0, ',', '.') }}</td> --}}
                <td>{{ $key->tahun }}</td>
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
