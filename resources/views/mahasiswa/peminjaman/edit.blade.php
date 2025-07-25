@extends('layouts.tabler-front.master')
@section('content')
    <div class="row">
        <div class="col-md-12 ">
            <h1 class="text-center m-b-0">{{ $title }}</h1>
            <p></p>
        </div>

        <div id="respon">
            @if (session()->has('msg'))
                <div class="alert alert-warning">
                    {{ session('msg') }}
                    <button type="button" class="close" data-bs-dismiss="alert">×</button>
                </div>
            @endif
        </div>

        <div class="card">
            <div class="card-body">
                <a class="btn btn-primary mb-2" href="{{ route('mahasiswa.peminjaman.index') }}"><i
                        class="fa fa-arrow-left"></i> Kembali</a>
                <div class="card mb-2">

                </div>
                <div class="card">
                    <div class="card-header bg-primary text-blue-fg">
                        <strong>Edit Form Peminjaman Ruangan & Peralatan</strong>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('mahasiswa.peminjaman.update', encrypt($peminjaman->id)) }}" method="post"
                            id="form-peminjaman">
                            @csrf
                            @method('PUT')
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <fieldset class="form-group">
                                        <label for="waktu_peminjaman" class="form-label">Waktu Peminjaman</label>
                                        <input type="datetime-local" name="waktu_peminjaman" id="waktu_peminjaman"
                                            class="form-control" placeholder="Pilih tanggal dan jam" required
                                            value="{{ old('waktu_peminjaman', $peminjaman->waktu_peminjaman) }}">
                                    </fieldset>
                                </div>

                                <div class="col-md-6">
                                    <fieldset class="form-group">
                                        <label for="name" class="form-label">Nama</label>
                                        <input type="text" name="name" id="name" class="form-control" disabled
                                            value="{{ old('name', $user->name) }}">
                                    </fieldset>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <fieldset class="form-group">
                                        <label for="waktu_pengembalian" class="form-label">Sampai Dengan</label>
                                        <input type="datetime-local" name="waktu_pengembalian" id="waktu_pengembalian"
                                            class="form-control" required
                                            value="{{ old('waktu_pengembalian', $peminjaman->waktu_pengembalian) }}">
                                    </fieldset>
                                </div>

                                <div class="col-md-6">
                                    <fieldset class="form-group">
                                        <label for="prodi" class="form-label">Program Studi</label>
                                        <input type="text" name="prodi" id="prodi" class="form-control" required
                                            value="" disabled>
                                    </fieldset>
                                </div>

                            </div>

                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <label for="ruangan" class="form-label">Pilih Ruangan</label>
                                    <small class="text-danger">Kosongkan Jika hanya ingin pinjam barang
                                        saja</small>
                                    <select name="ruangan_id" id="ruangan_id"
                                        class="form-control @error('ruangan_id') is-invalid @enderror">
                                        <option value="">-Pilih Ruangan-</option>
                                        @foreach ($dataRuangan as $ruangan)
                                            <option value="{{ $ruangan->id }}"
                                                {{ $peminjaman->ruangan_id == $ruangan->id ? 'selected' : '' }}>
                                                {{ $ruangan->kode_ruangan }} - {{ $ruangan->nama_ruangan }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('ruangan_id')
                                        <small class="invalid-feedback">
                                            {{ $message }}
                                        </small>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <fieldset class="form-group">
                                        <label for="username" class="form-label">NPM</label>
                                        <input type="text" name="username" id="username" class="form-control"
                                            value="{{ $user->username }}" disabled>
                                    </fieldset>
                                </div>

                            </div>

                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <fieldset class="form-group">
                                        <label for="kegiatan" class="form-label">Kegiatan</label>
                                        <input type="text" name="kegiatan" id="kegiatan" class="form-control" required
                                            value="{{ old('kegiatan', $peminjaman->kegiatan) }}">
                                    </fieldset>
                                </div>
                                <div class="col-md-6">
                                    <fieldset class="form-group">
                                        <label for="no_telepon" class="form-label">No Telepon</label>
                                        <input type="text" name="no_telepon" id="no_telepon" class="form-control numbers-only"
                                            required value="{{ $user->no_telepon ?? '' }}">
                                    </fieldset>
                                </div>
                            </div>

                            <!-- Tombol untuk memunculkan modal -->
                            <button type="button" class="btn btn-primary mt-3 mb-1" data-bs-toggle="modal"
                                data-bs-target="#modalTambahBarang" id="btnTambahBarang">
                                Tambah Barang
                            </button>

                            <!-- Tabel daftar barang terpilih -->
                            <div>
                                <small class="text-danger">Kosongkan Jika hanya ingin pinjam ruangan
                                    saja</small>
                            </div>
                            <table class="table table-bordered" id="tabel-barang-terpilih">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Barang</th>
                                        <th>Jumlah</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="daftarBarang">

                                </tbody>
                            </table>

                            <button type="submit" href="" class="btn btn-primary mt-2"><i
                                    class="fa fa-save"></i>
                                Simpan</button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
        {{-- Tempat Modal --}}
        <div id="modalContainer"></div>
    @endsection

    @push('js')
        <script>
            // Mengatur batasan input date minimal 3 hari kedepan
            document.addEventListener('DOMContentLoaded', function() {
                // 1. Ambil elemen input date
                const waktuPinjamInput = document.getElementById('waktu_peminjaman');
                const waktuSelesaiInput = document.getElementById('waktu_pengembalian');

                // --- Minimal 3 hari dari sekarang ---
                const minDate = new Date();
                minDate.setDate(new Date().getDate() + 3);

                const year = minDate.getFullYear();
                const month = String(minDate.getMonth() + 1).padStart(2, '0');
                const day = String(minDate.getDate()).padStart(2, '0');
                const minDateTimeString = `${year}-${month}-${day}T00:00`;

                // Atur 'min' untuk KEDUA input saat halaman pertama kali dimuat
                waktuPinjamInput.min = minDateTimeString;
                waktuSelesaiInput.min = minDateTimeString;
            });
            $(document).ready(function() {
                // Select2 untuk pilih ruangan
                $('#ruangan_id').select2({
                    placeholder: 'Pilih Ruangan',
                    allowClear: true,
                    theme: 'bootstrap-5'
                });
            });
        </script>

        <script>
            // Cek ketersedian ruangan
            function cekKetersediaanRuangan() {
                const ruanganId = $('#ruangan_id').val();
                const mulai = $('#waktu_peminjaman').val();
                const sampai = $('#waktu_pengembalian').val();

                if (ruanganId && mulai && sampai) {
                    $.get("{{ route('cek.ketersediaan.ruangan') }}", {
                        ruangan_id: ruanganId,
                        waktu_peminjaman: mulai,
                        waktu_pengembalian: sampai
                    }, function(res) {
                        if (res.available) {
                           // Panggil SweetAlert
                            const message =
                                "Ruangan sudah dibooking di hari dan jam yang sama. Silahkan pilih waktu lain atau ruangan lain.";
                            sweetAlert(message);
                            $('#ruangan_id').val('').trigger('change');
                        }
                    });
                }
            }
            $('#waktu_peminjaman, #waktu_pengembalian, #ruangan_id').on('change', cekKetersediaanRuangan);
        </script>

        <script>
            let daftarBarang = @json($barangPeminjaman);
            // Panggil fungsi ini sekali saat halaman dimuat untuk menampilkan data awal
            updateTabelBarang();
            // Saat form tambah barang dikirim
            $(document).on('submit', '#form-tambah-barang', function(e) {
                e.preventDefault();
                const barangId = $('#barang_id').val(); // value id barang dari barang yang dipilih
                const barangText = $('#barang_id option:selected').text();
                const jumlah = parseInt($('#jumlah').val());

                // form modal tidak boleh ada yang kosong
                if (!barangId || !jumlah || jumlah < 1) {
                   // Panggil SweetAlert
                    const message = "Barang dan Jumlah Wajib Diisi";
                    sweetAlert(message);
                }

                // Cek duplikat data/ apakah barang sudah ditambahkan
                if (daftarBarang.some(item => item.id == barangId)) {
                    // Panggil SweetAlert
                    const message = "Barang Sudah Ditambahkan";
                    sweetAlert(message);
                }

                // Tambahkan ke array & tampilkan ke tabel
                daftarBarang.push({
                    id: barangId,
                    nama: barangText,
                    jumlah: jumlah
                });

                updateTabelBarang(); // panggi fungsi updateTabelBarang

                // Reset/kosongkan input modal
                $('#barang_id').val('').trigger('change');
                $('#jumlah').val('');

                // tutup modal
                const modalEl = document.getElementById('modalTambahBarang');
                const modalInstance = bootstrap.Modal.getInstance(modalEl);
                // Cukup panggil hide(), biarkan Bootstrap mengurus backdrop
                if (modalInstance) {
                    modalInstance.hide();
                }
            });

            // fungsi untuk update table jika ada yang ditambahkan/dihapus
            function updateTabelBarang() {
                let html = '';
                daftarBarang.forEach((item, index) => {
                    // tampilkan data dan membuat input hidden untuk request ke controller
                    html += `
                <tr>
                    <td>${index + 1}</td>
                    <td>
                        ${item.nama}
                        <input type="hidden" name="barang_id[]" value="${item.id}"> 
                    </td>
                    <td>
                        ${item.jumlah}
                        <input type="hidden" name="jumlah_barang[]" value="${item.jumlah}">
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm" onclick="hapusBarang(${item.id})">Hapus</button>
                    </td>
                </tr>`;
                });
                $('#daftarBarang').html(html);
            }

            // fungsi hapus barang
            function hapusBarang(id) {
                daftarBarang = daftarBarang.filter(item => item.id != id);
                updateTabelBarang(); // panggil fungsi updateTabelBarang
            }
        </script>

        <script>
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $(document).ready(function() {
                $(document).on("click", "#btnTambahBarang", function() { // tombol tambah barang di klik
                    $.ajax({
                            url: "{{ route('mahasiswa.peminjaman.modal-barang') }}",
                            method: 'GET',
                        })
                        .done(function(data) {
                            $('#modalContainer').html(data.html);
                            const modalEl = document.getElementById('modalTambahBarang');

                            // Buat instance baru setiap kali, dan biarkan Bootstrap menanganinya.
                            const myModal = new bootstrap.Modal(modalEl);
                            myModal.show();

                            // Dengarkan event 'hidden.bs.modal' untuk membersihkan DOM setelah modal tertutup
                            modalEl.addEventListener('hidden.bs.modal', event => {
                                // Hapus elemen modal dari DOM untuk mencegah duplikasi
                                $(modalEl).remove();
                            });

                            // Select2 untuk pilih barang di modal
                            $('#barang_id').select2({
                                placeholder: 'Pilih Barang',
                                allowClear: true,
                                dropdownParent: $('#modalTambahBarang'),
                                theme: 'bootstrap-5',
                                width: '100%',
                            });

                            // // Atur max jumlah berdasarkan stok barang
                            $('#barang_id').on('change', function() {
                                const id = $(this).val();
                                const mulai = $('#waktu_peminjaman').val();
                                const sampai = $('#waktu_pengembalian').val();
                                const jumlahInput = $('#jumlah'); // Simpan elemen input jumlah
                                if (!id) return;

                                $.get("{{ route('cek.ketersediaan.barang') }}", {
                                    barang_id: id,
                                    waktu_peminjaman: mulai,
                                    waktu_pengembalian: sampai
                                }, function(res) {
                                    jumlahInput.prop('disabled', false);
                                    // Atur batas maksimal input jumlah sesuai stok tersedia
                                    if (res.stok_tersedia < 1) {
                                       // Panggil SweetAlert
                                        const message = "Stok Tidak Tersedia/Habis Untuk Hari dan Jam yang Kamu Pilih";
                                        sweetAlert(message);
                                    }
                                    jumlahInput.attr('max', res.stok_tersedia);

                                    // Beri placeholder sesuai stok yang tersedia
                                    jumlahInput.attr('placeholder',
                                        `Maksimal ${res.stok_tersedia} unit`);

                                }).fail(function() {
                                    jumlahInput.prop('disabled', false);
                                    // Panggil SweetAlert
                                    const message = "Gagal mengecek ketersediaan barang, Pilih Hari dan Jam Terlebih Dahulu";
                                    sweetAlert(message);
                                });
                            });
                        })
                })
                 // Fungsi untuk SweetAlert
                function sweetAlert(message) {
                    const alert = Swal.fire({
                        title: "Info!",
                        text: message,
                        icon: "error"
                    });
                    return alert
                }
            });
        </script>
    @endpush
