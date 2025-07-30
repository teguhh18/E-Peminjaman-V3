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

            <form action="{{ route('admin.peminjaman.update', encrypt($peminjaman->id)) }}" method="post"
                id="form-peminjaman">
                @csrf
                @method('PUT')
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
                                    class="form-control form-control-sm @error('nama_id') is-invalid @enderror">
                                    <option value="">-Pilih Peminjam-</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}"
                                            {{ $peminjaman->user_id == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}
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
                                value="{{ old('kegiatan', $peminjaman->kegiatan) }}"
                                placeholder="Contoh: Rapat Koordinasi Himpunan">
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="waktu_peminjaman" class="form-label required">Waktu Mulai</label>
                                <input type="datetime-local" name="waktu_peminjaman" id="waktu_peminjaman"
                                    class="form-control" required
                                    value="{{ old('waktu_peminjaman', $peminjaman->waktu_peminjaman) }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="waktu_pengembalian" class="form-label required">Waktu Selesai</label>
                                <input type="datetime-local" name="waktu_pengembalian" id="waktu_pengembalian"
                                    class="form-control" required
                                    value="{{ old('waktu_pengembalian', $peminjaman->waktu_pengembalian) }}">
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
                                        <option value="{{ $ruangan->id }}"
                                            {{ $peminjaman->ruangan_id == $ruangan->id ? 'selected' : '' }}>
                                            {{ $ruangan->kode_ruangan }} - {{ $ruangan->nama_ruangan }}
                                        </option>
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
                        <p class="text-muted">Daftar approver akan ditentukan secara otomatis oleh sistem berdasarkan aset
                            yang Anda pinjam.</p>
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
                            <i class="fa fa-edit me-1"></i>Ubah
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

            // --- Minimal hari ini, disable input dimasa lalu---
            const minDate = new Date();

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
            // ===================================================================
            // 1. STATE & INISIALISASI AWAL
            // ===================================================================

            let daftarBarang = @json($barangPeminjaman ?? []);
            // let dataApprover = @json($approvers ?? []);
            let listApprover = (@json($approvers ?? [])).map(approver => {
                approver.is_manual = true; // Tandai semua approver awal sebagai "manual"
                return approver;
            });
            updateTabelBarang();
            // Fungsi untuk merender tabel barang dan approver dengan data awal
            getAndUpdateApprovers();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Inisialisasi Select2 untuk elemen yang sudah ada di halaman
            $('#ruangan_id, #nama_id').select2({
                placeholder: 'Pilih',
                // theme: 'bootstrap-5',
                allowClear: true,
                width: '100%'
            });

            // Untuk isi input Prodi, NPM/Username, dan NO Telepon Secara Otomatis setelah pilih nama
            // 1. Buat fungsi yang bisa digunakan kembali untuk mengambil dan mengisi data user
            function fetchAndFillUserData(userId) {
                // Jika tidak ada ID yang dipilih (misal memilih "-Pilih Peminjam-"), kosongkan input
                if (!userId) {
                    $('#user_id').val('');
                    $('#prodi').val('');
                    $('#username').val('');
                    $('#no_telepon').val('');
                    return; // Hentikan eksekusi
                }
                // Lakukan panggilan AJAX untuk mendapatkan detail user
                $.get("{{ route('get.user') }}", {
                    user_id: userId,
                }, function(res) {
                    const namaProdi = res.mahasiswa ? res.mahasiswa.nama_program_studi : '';

                    // Gunakan .val() untuk mengisi nilai input form
                    $('#user_id').val(res.id);
                    $('#prodi').val(namaProdi);
                    $('#username').val(res.username);
                    $('#no_telepon').val(res.no_telepon);
                }).fail(function() {
                    const message = "Gagal memuat data detail pengguna.";
                    // Ganti dengan notifikasi pilihan Anda, misalnya sweetAlert(message);
                    alert(message);
                });
            }
            // Pasang event listener 'change' yang akan memanggil fungsi fetchAndFillUserData
            $('#nama_id').on('change', function() {
                const selectedUserId = $(this).val();
                fetchAndFillUserData(selectedUserId);
            });
            // 3. Picu fungsi saat halaman pertama kali dimuat
            const initialUserId = $('#nama_id').val();
            if (initialUserId) {
                fetchAndFillUserData(initialUserId);
            }

            // 2. EVENT HANDLERS (PENANGAN AKSI PENGGUNA)
            // Perbarui daftar approver saat ada perubahan ruangan
            $('#ruangan_id').on('change', getAndUpdateApprovers);

            // -- Manajemen Barang --
            $(document).on("click", "#btnTambahBarang", handleModalBarang);
            $(document).on('submit', '#form-tambah-barang', handleTambahBarang);
            $(document).on('click', '.btn-hapus-barang', function() {
                const barangId = $(this).data('id');
                daftarBarang = daftarBarang.filter(item => item.id != barangId);
                updateTabelBarang();
                getAndUpdateApprovers();
            });

            // -- Manajemen Approver --
            $(document).on("click", "#btnPilihApprover", handleModalApprover);
            $(document).on('click', '#btn-submit-approver', handleTambahApprover);
            $(document).on('click', '.btn-hapus-approver', function() {
                const approverId = parseInt($(this).data('id'));
                listApprover = listApprover.filter(item => parseInt(item.id) !== approverId);
                getAndUpdateApprovers();
            });

            // ===================================================================
            // 3. FUNGSI-FUNGSI BANTUAN (HELPER FUNCTIONS)
            // ===================================================================

            // --- Fungsi untuk Barang ---
            function handleModalBarang() {
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
                                    const message =
                                        "Stok Tidak Tersedia/Habis Untuk Hari dan Jam yang Kamu Pilih";
                                    sweetAlert(message);
                                }
                                jumlahInput.attr('max', res.stok_tersedia);

                                // Beri placeholder sesuai stok yang tersedia
                                jumlahInput.attr('placeholder',
                                    `Maksimal ${res.stok_tersedia} unit`);


                            }).fail(function() {
                                jumlahInput.prop('disabled', false);
                                // Panggil SweetAlert
                                const message =
                                    "Gagal mengecek ketersediaan barang, Pilih Hari dan Jam Terlebih Dahulu";
                                sweetAlert(message);
                            });
                        });


                    })
            }

            function handleTambahBarang(e) {
                e.preventDefault();
                const barangId = $('#barang_id').val(); // value id barang dari barang yang dipilih
                const barangText = $('#barang_id option:selected').text();
                const jumlah = parseInt($('#jumlah').val());

                // form modal tidak boleh ada yang kosong
                if (!barangId || !jumlah || jumlah < 1) {
                    // Panggil SweetAlert
                    const message = "Barang dan jumlah wajib diisi!";
                    sweetAlert(message);
                }

                // Cek duplikat data/ apakah barang sudah ditambahkan
                if (daftarBarang.some(item => item.id == barangId)) {
                    // Panggil SweetAlert
                    Swal.fire({
                        title: "Info!",
                        text: "Barang sudah ditambahkan!",
                        icon: "error"
                    });
                    return;
                }

                // Tambahkan ke array & tampilkan ke tabel
                daftarBarang.push({
                    id: barangId,
                    nama: barangText,
                    jumlah: jumlah
                });

                updateTabelBarang(); // panggi fungsi updateTabelBarang
                getAndUpdateApprovers(); // panggi fungsi updateTabelApprover

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
            }

            function updateTabelBarang() {
                let html = '';
                daftarBarang.forEach((item, index) => {
                    html += `
                <tr>
                    <td>${index + 1}</td>
                    <td>${item.nama}<input type="hidden" id="barang_id[]" name="barang_id[]" value="${item.id}"></td>
                    <td>${item.jumlah}<input type="hidden" name="jumlah_barang[]" value="${item.jumlah}"></td>
                    <td><button type="button" class="btn btn-danger btn-sm btn-hapus-barang" data-id="${item.id}">Hapus</button></td>
                </tr>`;
                });
                $('#daftarBarang').html(html);
            }

            // --- Fungsi untuk Approver ---
            function handleModalApprover() {
                $.get("{{ route('admin.peminjaman.modal-approver') }}")
                    .done(function(data) {
                        $('#modalContainer').html(data.html);
                        $('#modalPilihApprover').modal('show');
                        $('#approver_id').select2({
                            dropdownParent: $('#modalPilihApprover'),
                            theme: 'bootstrap-5'
                        });
                    });
            }

            let manuallyRemovedApprovers = [];
            // fungsi hapus approver
            window.hapusApprover = function(id) {
                const approverId = parseInt(id);
                // Catat ID yang dihapus ke "daftar hitam" agar tidak disarankan lagi
                if (!manuallyRemovedApprovers.includes(approverId)) {
                    manuallyRemovedApprovers.push(approverId);
                }
                // Hapus dari daftar utama
                listApprover = listApprover.filter(item => parseInt(item.id) !== approverId);
                renderTabelApprover();
            }

            function handleTambahApprover(e) {
                e.preventDefault();
                const approverId = $('#approver_id').val(); // value id approver yang dipilih
                const approverName = $('#approver_id option:selected').text();

                // form modal tidak boleh ada yang kosong
                if (!approverId) {
                    // Panggil SweetAlert
                    const message = "Pilih Approver Terlebih Dahulu";
                    sweetAlert(message);
                }

                // Cek duplikat data/apakah approver sudah ditambahkan
                if (listApprover.some(item => item.id == approverId)) {
                    // Panggil SweetAlert
                    Swal.fire({
                        title: "Info!",
                        text: "Approver yang dipilih sudah ditambahkan!",
                        icon: "error"
                    });
                    return;
                }

                // Tambahkan ke daftar dan beri penanda sebagai pilihan MANUAL
                listApprover.push({
                    id: approverId,
                    nama: approverName,
                    is_manual: true
                });
                renderTabelApprover();
                // Reset/kosongkan input modal
                $('#approver_id').val('').trigger('change');

                // tutup modal
                const modalEl = document.getElementById('modalPilihApprover');
                const modalInstance = bootstrap.Modal.getInstance(modalEl);
                // Cukup panggil hide(), biarkan Bootstrap mengurus backdrop
                if (modalInstance) {
                    modalInstance.hide();
                }
            }

            function getAndUpdateApprovers() {
                const ruanganId = $('#ruangan_id').val();
                const barangIds = daftarBarang.map(item => item.id);

                // Ambil saran approver dari server
                $.get("{{ route('mahasiswa.peminjaman.list-approver') }}", {
                    ruangan_id: ruanganId,
                    barang_id: barangIds,
                }).done(function(res) {
                    const suggestions = res.approvers;
                    console.log(suggestions)
                    // 1. Hapus saran sistem yang lama, TAPI pertahankan yang ditambah manual
                    listApprover = listApprover.filter(approver => approver.is_manual === true);

                    // 2. Gabungkan dengan saran baru dari server, hindari duplikasi & yang sudah dihapus manual
                    suggestions.forEach(suggestion => {
                        const isAlreadyInList = listApprover.some(item => item.id == suggestion.id);
                        const wasManuallyRemoved = manuallyRemovedApprovers.includes(parseInt(
                            suggestion.id));

                        if (!isAlreadyInList && !wasManuallyRemoved) {
                            suggestion.is_manual = false; // Tandai sebagai saran sistem
                            listApprover.push(suggestion);
                        }
                    });

                    // 3. Render ulang tabel dengan daftar yang sudah diperbarui
                    renderTabelApprover();
                });
            }

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
