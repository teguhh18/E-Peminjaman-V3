@if ($dataMahasiswa->count() > 1)
    <div class="table-responsive">
        <table class="table table-striped table-bordered" id="datatables">
            <thead>
                <tr class="bg bg-warning">
                    <th class="text-center" width="5%">No</th>
                    <th class="text-center" width="10%">Aksi</th>
                    <th class="text-center">NPM</th>
                    <th class="text-center">Nama Mahasiswa</th>
                    <th class="text-center">Program Studi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($dataMahasiswa as $key)
                    <tr class="odd gradeX">
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td class="text-center">
                            <form method="post" action="{{ route('admin.mahasiswa.update', $key->user_id) }}">
                                @csrf
                                @method('patch')
                                <button type="submit" onclick="return confirm('Klik OK untuk Reset Password!')"
                                    class="btn btn-sm btn-warning btn-xs btn-update">
                                    <i class="fa fa-key"></i> Reset Password
                                </button>
                            </form>
                        </td>
                        <td class="text-center">{{ $key->npm_mahasiswa }}</td>
                        <td>{{ $key->nama_mahasiswa }}</td>
                        <td>{{ $key->nama_program_studi }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
