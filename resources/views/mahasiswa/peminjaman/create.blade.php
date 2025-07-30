@extends('layouts.tabler-front.master')
@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-8">
            {{-- Notifikasi --}}
            <div id="respon">
                @if (session()->has('msg'))
                    <div class="alert {{ session('class') ?? 'alert-info' }} alert-dismissible" role="alert">
                        {{ session('msg') }}
                        <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
                    </div>
                @endif
            </div>

            <form action="{{ route('mahasiswa.peminjaman.store') }}" method="post" id="form-peminjaman">
                @csrf
                <div class="card">
                    {{-- HEADER KARTU DENGAN WARNA MERAH --}}
                    <div class="card-header bg-red text-white">
                        <h3 class="card-title mb-0 text-white">
                            <i class="ti ti-file-plus me-2"></i>
                            Formulir Pengajuan Peminjaman
                        </h3>
                    </div>

                    <div class="card-body">
                        {{-- BAGIAN 1: INFORMASI PEMINJAM (READ-ONLY) --}}
                        <h4 class="mb-3">1. Informasi Peminjam<small class="text-muted"> (Sesuaikan di bagian profil)</small></h4>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama</label>
                                <input type="text" class="form-control" value="{{ Str::title($user->name) }}" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Program Studi</label>
                                <input type="text" class="form-control"
                                    value="{{ Str::title($user->mahasiswa->nama_program_studi) }}" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">NPM</label>
                                <input type="text" class="form-control" value="{{ $user->username }}" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">No. Telepon</label>
                                <input type="text" name="no_telepon" class="form-control"
                                    value="{{ $user->no_telepon ?? '' }}" placeholder="Masukkan no. telepon aktif">
                            </div>
                        </div>

                        <hr class="my-4">

                        {{-- BAGIAN 2: DETAIL PENGAJUAN --}}
                        <h4 class="mb-3">2. Detail Pengajuan</h4>
                        <div class="mb-3">
                            <label for="kegiatan" class="form-label required">Nama Kegiatan</label>
                            <input type="text" name="kegiatan" id="kegiatan" class="form-control" required
                                value="{{ old('kegiatan') }}" placeholder="Contoh: Rapat Koordinasi Himpunan">
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="waktu_peminjaman" class="form-label required">Waktu Mulai</label>
                                <input type="datetime-local" name="waktu_peminjaman" id="waktu_peminjaman"
                                    class="form-control" required value="{{ old('waktu_peminjaman') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="waktu_pengembalian" class="form-label required">Waktu Selesai</label>
                                <input type="datetime-local" name="waktu_pengembalian" id="waktu_pengembalian"
                                    class="form-control" required value="{{ old('waktu_pengembalian') }}">
                            </div>
                        </div>

                        <hr class="my-4">

                        {{-- BAGIAN 3: ASET YANG DIPINJAM --}}
                        <h4 class="mb-3">3. Aset yang Dipinjam <small class="text-muted">(Opsional)</small></h4>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="ruangan_id" class="form-label">Pilih Ruangan</label>
                                <select name="ruangan_id" id="ruangan_id" class="form-select">
                                    <option value="">- Tidak pinjam ruangan -</option>
                                    @foreach ($dataRuangan as $ruangan)
                                        <option value="{{ $ruangan->id }}">{{ $ruangan->kode_ruangan }} -
                                            {{ $ruangan->nama_ruangan }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Pilih Barang</label>
                                <div>
                                    <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#modalTambahBarang" id="btnTambahBarang">
                                        <i class="fa fa-plus me-1"></i> Tambah Barang
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered" id="tabel-barang-terpilih">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Barang</th>
                                        <th>Jumlah</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="daftarBarang"></tbody>
                            </table>
                        </div>


                        <hr class="my-4">

                        {{-- BAGIAN 4: ALUR PERSETUJUAN --}}
                        <h4 class="mb-3">4. Alur Persetujuan</h4>
                        <p class="text-muted">Daftar approver akan ditentukan secara otomatis oleh sistem berdasarkan aset yang Anda pinjam.</p>
                        <div class="table-responsive">
                            <table class="table table-bordered" id="approver">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Bagian</th>
                                    </tr>
                                </thead>
                                <tbody id="table-list-approver"></tbody>
                            </table>
                        </div>
                    </div>

                    {{-- FOOTER KARTU: TOMBOL AKSI --}}
                    <div class="card-footer text-end">
                        <a href="{{ route('home') }}" class="btn btn-secondary btn-sm">
                            <i class="fa fa-arrow-left me-1"></i>
                            Kembali
                        </a>
                        <button type="submit" class="btn btn-red btn-sm">
                            <i class="fa fa-floppy-o me-1"></i>Simpan
                        </button>
                    </div>
                </div>
            </form>
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
        </script>
        <script>
            $(document).ready(function() {
                // Select2 untuk pilih ruangan
                $('#ruangan_id').select2({
                    placeholder: 'Pilih Ruangan',
                    allowClear: true,
                    // theme: 'bootstrap-5',
                    width: '100%',
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
            // Jalankan Fungsi saat ada perubahan pada waktu dan ruangan
            $('#waktu_peminjaman, #waktu_pengembalian, #ruangan_id').on('change', cekKetersediaanRuangan);
        </script>

        <script>
            let daftarBarang = []; //untuk simpan data barang yang dipilih
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
                updateTableApprover();
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
                        <input type="hidden" id="list_barang_id" name="barang_id[]" value="${item.id}"> 
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
                updateTableApprover();
            }

        
             $('#ruangan_id').on('change', updateTableApprover);
             function updateTableApprover(){
                const ruanganId = $('#ruangan_id').val();
                let barangId = [];
                const barang = daftarBarang;
                let html = '';
                barang.forEach((item) => {
                    barangId.push(item.id)
                })

                $.get("{{ route('mahasiswa.peminjaman.list-approver') }}", {
                        ruangan_id: ruanganId,
                        barang_id: barangId, //Array
                    }, function(res) {
                        approver = res.approvers
                        approver.forEach((item, index) => {
                    // tampilkan data dan membuat input hidden untuk request ke controller
                    html += `
                <tr>
                    <td>${index + 1}</td>
                    <td>
                        ${item.nama}
                        <input type="hidden" id="list_apprrover_id" name="approver_id[]" value="${item.id}"> 
                    </td>
                </tr>`;
                });
                $('#table-list-approver').html(html);
                    });

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
                                    // console.log(res)
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
