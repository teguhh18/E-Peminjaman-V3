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
                        <h4 class="mb-3">1. Informasi Peminjam<small class="text-muted"> (Sesuaikan di bagian
                                profil)</small></h4>
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
        $(document).ready(function() {
            // ===================================================================
            // 1. STATE & INISIALISASI AWAL
            // ===================================================================

            // Variabel untuk menyimpan state
            let daftarBarang = @json($barangPeminjaman ?? []);
            let listApprover = (@json($approvers ?? [])).map(approver => ({
                ...approver,
                is_manual: true
            }));
            let manuallyRemovedApprovers = [];

            // Setup CSRF token untuk semua request AJAX
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Inisialisasi Select2
            $('#nama_id').select2({
                placeholder: 'Pilih Opsi',
                theme: 'bootstrap-5',
                allowClear: true,
                width: '100%'
            });
            $('#ruangan_id').select2({
                placeholder: 'Pilih Opsi',
                // theme: 'bootstrap-5',
                allowClear: true,
                width: '100%'
            });

            // Mengatur batasan input date
            // --- Minimal 3 hari dari sekarang ---
            const minDate = new Date();
            minDate.setDate(new Date().getDate() + 3);

            const year = minDate.getFullYear();
            const month = String(minDate.getMonth() + 1).padStart(2, '0');
            const day = String(minDate.getDate()).padStart(2, '0');
            const minDateTimeString = `${year}-${month}-${day}T00:00`;
            $('#waktu_peminjaman, #waktu_pengembalian').attr('min', minDateTimeString);

            // Memuat data awal saat halaman dibuka
            updateTabelBarang();
            getAndUpdateApprovers();
            const initialUserId = $('#nama_id').val();
            if (initialUserId) {
                fetchAndFillUserData(initialUserId);
            }

            // ===================================================================
            // 2. EVENT HANDLERS (PENANGAN AKSI PENGGUNA)
            // ===================================================================

            // Update data user saat peminjam dipilih
            $('#nama_id').on('change', function() {
                fetchAndFillUserData($(this).val());
            });

            // Update approver saat ruangan atau waktu berubah
            $('#ruangan_id, #waktu_peminjaman, #waktu_pengembalian').on('change', getAndUpdateApprovers);

            // Buka modal untuk tambah barang
            $(document).on("click", "#btnTambahBarang", handleModalBarang);

            // Proses penambahan barang dari modal
            $(document).on('submit', '#form-tambah-barang', handleTambahBarang);

            // Hapus barang dari daftar
            $(document).on('click', '.btn-hapus-barang', function() {
                const barangId = $(this).data('id');
                daftarBarang = daftarBarang.filter(item => item.id != barangId);
                updateTabelBarang();
                getAndUpdateApprovers();
            });

            // Buka modal untuk pilih approver
            $(document).on("click", "#btnPilihApprover", handleModalApprover);
            // ===================================================================
            // 3. FUNGSI-FUNGSI BANTUAN (HELPER FUNCTIONS)
            // ===================================================================

            /** Menampilkan notifikasi error menggunakan SweetAlert */
            function sweetAlert(message) {
                Swal.fire({
                    title: "Info!",
                    text: message,
                    icon: "error"
                });
            }

            // --- Fungsi untuk Manajemen Barang ---

            /** Menampilkan modal untuk menambah barang */
            function handleModalBarang() {
                $.get("{{ route('mahasiswa.peminjaman.modal-barang') }}")
                    .done(function(data) {
                        $('#modalContainer').html(data.html);
                        const modalEl = $('#modalTambahBarang');
                        modalEl.modal('show');

                        $('#barang_id').select2({
                            placeholder: 'Pilih Barang',
                            allowClear: true,
                            dropdownParent: modalEl,
                            theme: 'bootstrap-5',
                            width: '100%',
                        }).on('change', cekKetersediaanBarang);

                        // Gunakan event handler jQuery yang konsisten untuk membersihkan modal
                        modalEl.on('hidden.bs.modal', function() {
                            $(this).remove();
                        });
                    });
            }

            /** Memvalidasi dan menambah barang ke `daftarBarang` */
            function handleTambahBarang(e) {
                e.preventDefault();
                const barangId = $('#barang_id').val();
                const barangText = $('#barang_id option:selected').text();
                const jumlah = parseInt($('#jumlah').val());

                if (!barangId || !jumlah || jumlah < 1) {
                    return sweetAlert("Barang dan jumlah wajib diisi!");
                }
                if (daftarBarang.some(item => item.id == barangId)) {
                    return sweetAlert("Barang sudah ditambahkan!");
                }

                daftarBarang.push({
                    id: barangId,
                    nama: barangText,
                    jumlah: jumlah,
                    barang_id: barangId,
                    jumlah_barang: jumlah
                });
                updateTabelBarang();
                getAndUpdateApprovers();
                // Tutup modal
                $('#modalTambahBarang').modal('hide');
            }

            /** Merender ulang tabel daftar barang */
            function updateTabelBarang() {
                let html = '';
                daftarBarang.forEach((item, index) => {
                    html += `
                <tr>
                    <td>${index + 1}</td>
                    <td>${item.nama}<input type="hidden" name="barang_id[]" value="${item.barang_id || item.id}"></td>
                    <td>${item.jumlah}<input type="hidden" name="jumlah_barang[]" value="${item.jumlah_barang || item.jumlah}"></td>
                    <td><button type="button" class="btn btn-danger btn-sm btn-hapus-barang" data-id="${item.id}">Hapus</button></td>
                </tr>`;
                });
                $('#daftarBarang').html(html);
            }

            /** Mengecek ketersediaan stok barang pada tanggal yang dipilih */
            function cekKetersediaanBarang() {
                const id = $('#barang_id').val();
                const mulai = $('#waktu_peminjaman').val();
                const sampai = $('#waktu_pengembalian').val();
                const jumlahInput = $('#jumlah');

                if (!id || !mulai || !sampai) {
                    jumlahInput.prop('disabled', true).attr('placeholder', 'Pilih waktu & barang');
                    if (id && (!mulai || !sampai)) sweetAlert("Pilih waktu peminjaman terlebih dahulu.");
                    return;
                }

                $.get("{{ route('cek.ketersediaan.barang') }}", {
                        barang_id: id,
                        waktu_peminjaman: mulai,
                        waktu_pengembalian: sampai
                    })
                    .done(function(res) {
                        jumlahInput.prop('disabled', false).attr('max', res.stok_tersedia);
                        jumlahInput.attr('placeholder', `Maks. ${res.stok_tersedia} unit`);
                        if (res.stok_tersedia < 1) {
                            sweetAlert("Stok tidak tersedia pada waktu yang dipilih.");
                            jumlahInput.prop('disabled', true);
                        }
                    })
                    .fail(() => sweetAlert("Gagal mengecek ketersediaan barang."));
            }

            // --- Fungsi untuk Manajemen Approver ---

            /** Mengambil saran approver dari server dan memperbarui daftar */
            function getAndUpdateApprovers() {
                const ruanganId = $('#ruangan_id').val();
                const barangIds = daftarBarang.map(item => item.id);
                const mulai = $('#waktu_peminjaman').val();
                const sampai = $('#waktu_pengembalian').val();

                if (!ruanganId && barangIds.length === 0) {
                    listApprover = listApprover.filter(approver => approver.is_manual === true);
                    renderTabelApprover();
                    return;
                }

                $.get("{{ route('mahasiswa.peminjaman.list-approver') }}", {
                    ruangan_id: ruanganId,
                    barang_id: barangIds,
                    waktu_peminjaman: mulai,
                    waktu_pengembalian: sampai
                }).done(function(res) {
                    const suggestions = res.approvers;
                    listApprover = listApprover.filter(approver => approver.is_manual === true);

                    suggestions.forEach(suggestion => {
                        const isInList = listApprover.some(item => item.id == suggestion.id);
                        const wasRemoved = manuallyRemovedApprovers.includes(parseInt(suggestion
                            .id));
                        if (!isInList && !wasRemoved) {
                            listApprover.push({
                                ...suggestion,
                                is_manual: false
                            });
                        }
                    });
                    renderTabelApprover();
                });
            }

            /** Merender ulang tabel daftar approver */
            function renderTabelApprover() {
                let html = '';
                listApprover.forEach((item, index) => {
                    html += `
                <tr>
                    <td>${index + 1}</td>
                    <td>${item.nama}<input type="hidden" name="approver_id[]" value="${item.id}"></td>
                </tr>`;
                });
                $('#list-approver').html(html);
            }
        });
    </script>
@endpush
