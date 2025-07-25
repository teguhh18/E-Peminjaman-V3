<table class="table table-striped table-bordered" id="datatables">
    <thead>
        <tr>
            <th class="text-center" width="5%">No</th>
            <th class="text-center" width="10%">Aksi</th>
            <th>Kode Unit Kerja</th>
            <th>Nama Unit Kerja</th>

        </tr>
    </thead>
    <tbody>
        @foreach ($unitKerja as $key)
            <tr class="odd gradeX">
                <td class="text-center">{{ $loop->iteration }}</td>
                <td class="text-center">
                    <a href="{{ route('admin.unit.edit', $key->id) }}" class="btn btn-primary btn-sm btn-update">
                        <i class="fa fa-edit"></i>
                    </a>

                    <a href="javascript:;" data-id="{{ $key->id }}" class="btn btn-danger btn-sm btn-delete">
                        <i class="fa fa-trash"></i>
                    </a>
                </td>
                <td>{{ $key->kode }}</td>
                <td>{{ $key->nama }}</td>


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
