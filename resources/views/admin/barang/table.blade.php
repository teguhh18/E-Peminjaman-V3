<div class="table-responsive">
    <table class="table table-striped table-bordered " id="datatables">
        <thead style="text-align: center !important">
            <tr>
                <th width="5%">No</th>
                <th width="10%">Aksi</th>
                <th>Kode Barang</th>
                <th>Label Aset</th>
                <th>Lokasi Aset</th>
                <th>Unit Penanggung Jawab</th>
                {{-- <th>Tanggal Pembelian</th>
                <th>Nilai Perolehan (Rp)</th> --}}
                <th>Kondisi</th>
                <th>Status</th>
    
            </tr>
        </thead>
        <tbody>
            @php
                // dd($dataBarang);
            @endphp
            {{-- {{ dd($dataBarang) }} --}}
            @foreach ($dataBarang as $barang)
                <tr class="odd gradeX ">
                    <td class="center">{{ $loop->iteration }}</td>
                    <td class="center">
                        <a href="{{ route('admin.barang.edit', $barang->id) }}" class="btn btn-primary btn-sm btn-update">
                            <i class="fa fa-edit"></i>
                        </a>
                        <a href="javascript:;" data-id="{{ $barang->id }}" class="btn btn-danger btn-sm btn-delete">
                            <i class="fa fa-trash"></i>
                        </a>
                    </td>
                    <td class="center"><b><u>{{ $barang->kode }}</u></b></td>
                    <td class="center">{{ $barang->nama }}</td>
                    <td class="">
                        <i class="fas fa-angle-right"></i> Lantai : {{ $barang->ruangan->lantai }} <br>
                        <i class="fas fa-angle-right"></i> Gedung : {{ $barang->ruangan->gedung->nama }} <br>
                        <i class="fas fa-angle-right"></i> Ruang : {{ $barang->ruangan->nama_ruangan }} <br>
                    </td>
                    <td>{{ $barang->unitkerja->nama ?? '-'}}</td>
                    {{-- <td>{{ date('d-m-Y', strtotime($barang->tgl_perolehan)) }}</td> --}}
                    {{-- <td>Rp.{{ number_format($barang->harga_perolehan, 0, ',', '.') }}</td> --}}
                    @php
                        if ($barang->kondisi == 1) {
                            $kondisi = 'Baik';
                        } elseif ($barang->kondisi == 2) {
                            $kondisi = 'Rusak Ringan';
                        } else {
                            $kondisi = 'Rusak Berat';
                        }
    
                        if ($barang->status == 1) {
                            $status = 'Aktif';
                        } elseif ($barang->status == 2) {
                            $status = 'Dihapuskan';
                        } else {
                            $status = 'Diperbaiki';
                        }
                    @endphp
                    <td>{{ $kondisi }}</td>
                    <td>{{ $status }}</td>
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
