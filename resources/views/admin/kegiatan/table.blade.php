<table class="table table-striped table-bordered" id="datatables">
    <thead>
        <tr class="bg bg-warning">
            <th width="5%">No</th>
            <th width="10%">Aksi</th>
            <th>Kegiatan</th>
            <th>Deskripsi Kegiatan</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($dataKegiatan as $key)
            <tr class="odd gradeX">
                <td class="center">{{ $loop->iteration }}</td>
                <td class="center">
                    <a href="javascript:;" data-id="{{ $key->id }}" class="btn btn-primary btn-xs btn-update">
                        <i class="fa fa-edit"></i>
                    </a>

                    <a href="javascript:;" data-id="{{ $key->id }}" class="btn btn-danger btn-xs btn-delete">
                        <i class="fa fa-trash"></i>
                    </a>
                </td>
                <td>{{ $key->nama_kegiatan }}</td>
                <td>{{ $key->deskripsi_kegiatan }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
