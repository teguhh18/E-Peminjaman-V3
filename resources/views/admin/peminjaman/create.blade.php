@extends('layouts.tabler-admin.master')
@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-12">
            {{-- Notifikasi --}}
            <div id="respon">
                @if (session()->has('msg'))
                    <div class="alert {{ session('class') ?? 'alert-info' }} alert-dismissible" role="alert">
                        {{ session('msg') }}
                        <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
                    </div>
                @endif
            </div>

            <form action="{{ route('admin.peminjaman.store') }}" method="post" id="form-peminjaman">
                @csrf
                <div class="card">
                    {{-- HEADER KARTU DENGAN WARNA MERAH --}}
                    <div class="card-header bg-blue text-white">
                        <h3 class="card-title mb-0 text-white">
                            <i class="ti ti-file-plus me-2"></i>
                            Formulir Pengajuan Peminjaman
                        </h3>
                    </div>

                    <div class="card-body">
                        {{-- BAGIAN 1: INFORMASI PEMINJAM (READ-ONLY) --}}
                        <h4 class="mb-3">1. Informasi Peminjam</h4>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nama_id" class="form-label">Nama</label>
                                <select name="nama_id" id="nama_id"
                                    class="form-control @error('nama_id') is-invalid @enderror">
                                    <option value="">-Peminjam-</option>
                                    @foreach ($mahasiswa as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            {{-- INPUT HIDDEN USER ID (Diisi dari id user/peminjam yang dipilih) --}}
                            <input type="hidden" name="user_id" id="user_id">
                            <div class="col-md-6 mb-3">
                                <label for="prodi" class="form-label">Program Studi</label>
                                <input type="text" name="prodi" id="prodi" class="form-control" required readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="username" class="form-label">NPM</label>
                                <input type="text" name="username" id="username" class="form-control" disabled>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="no_telepon" class="form-label">No Telepon</label>
                                <input type="text" name="no_telepon" id="no_telepon" class="form-control numbers-only"
                                    required>
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
                        <button type="button" class="btn btn-primary btn-sm mt-1 mb-1" data-bs-toggle="modal"
                            data-bs-target="#modalTambahBarang" id="btnPilihApprover">
                            Pilih Approver
                        </button>
                        <small class="text-muted">Daftar approver akan ditentukan secara otomatis oleh sistem berdasarkan
                            aset
                            yang Anda pinjam.</small>
                        <div class="table-responsive">
                            <table class="table table-bordered" id="approver">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Bagian</th>
                                    </tr>
                                </thead>
                                <tbody id="list-approver"></tbody>
                            </table>
                        </div>
                    </div>

                    {{-- FOOTER KARTU: TOMBOL AKSI --}}
                    <div class="card-footer text-start">
                        <a href="{{ route('admin.booking.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fa fa-arrow-left me-1"></i>
                            Kembali
                        </a>
                        <button type="submit" class="btn btn-blue btn-sm">
                            <i class="fa fa-save me-1"></i>Simpan
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
    $(document).ready(function() {
        // =================================================================================
        // SETUP & INITIALIZATIONS
        // =================================================================================

        // Global state variables
        let daftarBarang = []; // Menyimpan data barang yang dipilih
        let listApprover = []; // Menyimpan data approver yang dipilih
        let manuallyRemovedApprovers = []; // Menyimpan approver yang dihapus manual

        // Setup CSRF token untuk semua request AJAX
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Inisialisasi Select2
        $('#ruangan_id').select2({
            placeholder: 'Pilih Ruangan',
            allowClear: true,
            width: '100%',
        });

        $('#nama_id').select2({
            placeholder: 'Pilih Peminjam',
            allowClear: true,
            width: '100%',
            theme: 'bootstrap-5',
        });

        // Mengatur batasan tanggal minimal untuk input waktu
        const minDate = new Date();
        const year = minDate.getFullYear();
        const month = String(minDate.getMonth() + 1).padStart(2, '0');
        const day = String(minDate.getDate()).padStart(2, '0');
        const minDateTimeString = `${year}-${month}-${day}T00:00`;

        $('#waktu_peminjaman').attr('min', minDateTimeString);
        $('#waktu_pengembalian').attr('min', minDateTimeString);

        // =================================================================================
        // HELPER FUNCTIONS
        // =================================================================================

        // Fungsi untuk menampilkan SweetAlert error
        function sweetAlertError(message) {
            Swal.fire({
                title: "Info!",
                text: message,
                icon: "error"
            });
        }


        // =================================================================================
        // USER & RUANGAN LOGIC
        // =================================================================================

        // Mengisi data user (prodi, NPM, no. telepon) secara otomatis
        $('#nama_id').on('change', function() {
            const id = $(this).val();
            if (!id) return;

            $.get("{{ route('get.user') }}", { user_id: id })
                .done(function(res) {
                    const namaProdi = res.mahasiswa ? res.mahasiswa.nama_program_studi : '';
                    $('#prodi').val(namaProdi);
                    $('#username').val(res.username);
                    $('#no_telepon').val(res.no_telepon);
                })
                .fail(function() {
                    sweetAlertError("Gagal mengambil data user.");
                });
        });

        // Cek ketersediaan ruangan
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
                        sweetAlertError("Ruangan sudah dibooking di hari dan jam yang sama. Silahkan pilih waktu atau ruangan lain.");
                        $('#ruangan_id').val('').trigger('change');
                    }
                });
            }
        }


        // =================================================================================
        // BARANG (ITEMS) LOGIC
        // =================================================================================

        // Cek ketersediaan stok barang
        function cekKetersediaanBarang() {
            const id = $('#barang_id').val();
            const mulai = $('#waktu_peminjaman').val();
            const sampai = $('#waktu_pengembalian').val();
            const jumlahInput = $('#jumlah');

            if (!id) {
                jumlahInput.prop('disabled', true).attr('placeholder', 'Pilih barang terlebih dahulu');
                return;
            }

            if (!mulai || !sampai) {
                 sweetAlertError("Gagal mengecek ketersediaan barang. Pilih Hari dan Jam Peminjaman Terlebih Dahulu");
                 $('#barang_id').val('').trigger('change');
                 return;
            }
            
            $.get("{{ route('cek.ketersediaan.barang') }}", {
                barang_id: id,
                waktu_peminjaman: mulai,
                waktu_pengembalian: sampai
            })
            .done(function(res) {
                jumlahInput.prop('disabled', false);
                jumlahInput.attr('max', res.stok_tersedia);
                jumlahInput.attr('placeholder', `Maksimal ${res.stok_tersedia} unit`);

                if (res.stok_tersedia < 1) {
                    sweetAlertError("Stok tidak tersedia untuk hari dan jam yang Anda pilih.");
                    jumlahInput.prop('disabled', true);
                }
            })
            .fail(function() {
                sweetAlertError("Gagal mengecek ketersediaan barang.");
                jumlahInput.prop('disabled', true);
            });
        }

        // Menampilkan daftar barang yang dipilih ke dalam tabel
        function updateTabelBarang() {
            let html = '';
            daftarBarang.forEach((item, index) => {
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

        // Menghapus barang dari daftar
        window.hapusBarang = function(id) {
            daftarBarang = daftarBarang.filter(item => item.id != id);
            updateTabelBarang();
            updateTabelApprover(); // Perbarui approver karena daftar barang berubah
        }

        // Buka modal untuk menambah barang
        $(document).on("click", "#btnTambahBarang", function() {
            $.get("{{ route('mahasiswa.peminjaman.modal-barang') }}")
                .done(function(data) {
                    $('#modalContainer').html(data.html);
                    const modalEl = document.getElementById('modalTambahBarang');
                    const myModal = new bootstrap.Modal(modalEl);
                    myModal.show();

                    // Inisialisasi Select2 untuk barang di dalam modal
                    $('#barang_id').select2({
                        placeholder: 'Pilih Barang',
                        allowClear: true,
                        dropdownParent: $('#modalTambahBarang'),
                        theme: 'bootstrap-5',
                        width: '100%',
                    });
                    
                    // Event listener untuk cek stok saat barang atau waktu berubah
                    $('#barang_id').on('change', cekKetersediaanBarang);

                    // Membersihkan modal dari DOM setelah ditutup
                    modalEl.addEventListener('hidden.bs.modal', () => $(modalEl).remove());
                });
        });

        // Menambah barang ke daftar saat form modal disubmit
        $(document).on('submit', '#form-tambah-barang', function(e) {
            e.preventDefault();
            const barangId = $('#barang_id').val();
            const barangText = $('#barang_id option:selected').text();
            const jumlah = parseInt($('#jumlah').val());

            if (!barangId || !jumlah || jumlah < 1) {
                sweetAlertError("Barang dan jumlah wajib diisi!");
                return;
            }

            if (daftarBarang.some(item => item.id == barangId)) {
                sweetAlertError("Barang sudah ditambahkan!");
                return;
            }

            daftarBarang.push({ id: barangId, nama: barangText, jumlah: jumlah });
            updateTabelBarang();
            updateTabelApprover();

            // Tutup modal
            const modalInstance = bootstrap.Modal.getInstance(document.getElementById('modalTambahBarang'));
            if (modalInstance) {
                modalInstance.hide();
            }
        });


        // =================================================================================
        // APPROVER LOGIC
        // =================================================================================

        // Merender tabel approver berdasarkan data di `listApprover`
        function renderTabelApprover() {
            let html = '';
            listApprover.forEach((item, index) => {
                html += `
                    <tr>
                        <td>${index + 1}</td>
                        <td>
                            ${item.nama}
                            <input type="hidden" name="approver_id[]" value="${item.id}">
                        </td>
                        <td>
                            <button type="button" class="btn btn-danger btn-sm" onclick="hapusApprover(${item.id})">Hapus</button>
                        </td>
                    </tr>`;
            });
            $('#list-approver').html(html);
        }

        // Mengupdate daftar approver berdasarkan ruangan dan barang yang dipilih
        function updateTabelApprover() {
            const ruanganId = $('#ruangan_id').val();
            const barangIds = daftarBarang.map(item => item.id);

            $.get("{{ route('mahasiswa.peminjaman.list-approver') }}", {
                ruangan_id: ruanganId,
                barang_id: barangIds
            }, function(res) {
                const suggestions = res.approvers;
                
                // 1. Filter approver: pertahankan yang ditambahkan manual
                listApprover = listApprover.filter(approver => approver.is_manual === true);

                // 2. Gabungkan dengan saran baru dari server
                suggestions.forEach(suggestion => {
                    const isAlreadyInList = listApprover.some(item => item.id == suggestion.id);
                    const wasManuallyRemoved = manuallyRemovedApprovers.includes(suggestion.id);

                    if (!isAlreadyInList && !wasManuallyRemoved) {
                        suggestion.is_manual = false; // Tandai sebagai saran sistem
                        listApprover.push(suggestion);
                    }
                });
                
                // 3. Render ulang tabel
                renderTabelApprover();
            });
        }
        
        // Menghapus approver dari daftar
        window.hapusApprover = function(id) {
            // Catat ID approver yang dihapus manual agar tidak muncul lagi sebagai saran
            if (!manuallyRemovedApprovers.includes(id)) {
                manuallyRemovedApprovers.push(id);
            }
            // Hapus dari daftar utama dan render ulang tabel
            listApprover = listApprover.filter(item => item.id != id);
            renderTabelApprover();
        }

        // Buka modal untuk memilih approver
        $(document).on("click", "#btnPilihApprover", function() {
            $.get("{{ route('admin.peminjaman.modal-approver') }}")
                .done(function(data) {
                    $('#modalContainer').html(data.html);
                    const modalEl = document.getElementById('modalPilihApprover');
                    const myModal = new bootstrap.Modal(modalEl);
                    myModal.show();

                    $('#approver_id').select2({
                        placeholder: 'Pilih Approver',
                        allowClear: true,
                        dropdownParent: $('#modalPilihApprover'),
                        theme: 'bootstrap-5',
                        width: '100%',
                    });
                    
                    modalEl.addEventListener('hidden.bs.modal', () => $(modalEl).remove());
                });
        });

        // Menambah approver pilihan manual ke daftar
        $(document).on('click', '#btn-submit-approver', function(e) {
            e.preventDefault();
            const approverId = $('#approver_id').val();
            const approverName = $('#approver_id option:selected').text();

            if (!approverId) {
                sweetAlertError("Pilih approver terlebih dahulu.");
                return;
            }

            if (listApprover.some(item => item.id == approverId)) {
                sweetAlertError("Approver yang dipilih sudah ditambahkan!");
                return;
            }

            // Tambahkan ke daftar dengan penanda 'manual'
            listApprover.push({ id: approverId, nama: approverName, is_manual: true });
            renderTabelApprover();

            const modalInstance = bootstrap.Modal.getInstance(document.getElementById('modalPilihApprover'));
            if (modalInstance) {
                modalInstance.hide();
            }
        });


        // =================================================================================
        // EVENT LISTENERS
        // =================================================================================

        // Jalankan fungsi cek ketersediaan saat input waktu atau ruangan berubah
        $('#waktu_peminjaman, #waktu_pengembalian, #ruangan_id').on('change', cekKetersediaanRuangan);

        // Jalankan fungsi update approver saat ruangan berubah
        $('#ruangan_id').on('change', updateTabelApprover);
    });
</script>
@endpush
