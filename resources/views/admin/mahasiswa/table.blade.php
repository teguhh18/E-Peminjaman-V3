<table class="table table-striped table-bordered" id="datatables">
    <thead>
        <tr>
            <th width="5%">No</th>
            <th width="10%">Aksi</th>
            <th>Nama</th>
            <th>Username</th>
            <th>Email</th>
            <th>Level</th>
            <th>Unit Kerja</th>
            <th>No Telepon</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($dataMahasiswa as $mahasiswa)
            <tr class="odd gradeX">
                <td class="center">{{ $loop->iteration }}</td>
                <td class="center">
                    <a href="{{ route('admin.mahasiswa.edit', $mahasiswa->id) }}"
                        class="btn btn-primary btn-sm btn-update">
                        <i class="fa fa-edit"></i>
                    </a>
                    <a href="javascript:;" data-id="{{ $mahasiswa->id }}" class="btn btn-danger btn-sm btn-delete">
                        <i class="fa fa-trash"></i>
                    </a>
                </td>
                <td>{{ $mahasiswa->name }}</td>
                <td>{{ $mahasiswa->username }}</td>
                <td>{{ $mahasiswa->email }}</td>
                <td>{{ $mahasiswa->level }}</td>
                <td>{{ $mahasiswa->unitkerja->kode ?? '-' }}</td>
                <td>{{ $mahasiswa->no_telepon }}</td>

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
