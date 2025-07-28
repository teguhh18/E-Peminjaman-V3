@push('js')
    <script>
        $(document).ready(function() {
            // ===================================================================
            // 1. STATE & INISIALISASI AWAL
            // ===================================================================
            let daftarBarang = @json($barangPeminjaman ?? []);
            let listApprover = @json($approvers ?? []);
            let manuallyRemovedApprovers = []; // Untuk mencatat ID approver yang dihapus manual

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('#ruangan_id, #nama_id').select2({
                theme: 'bootstrap-5',
                allowClear: true,
                placeholder: 'Pilih salah satu'
            });

            // --- Inisialisasi Awal ---
            renderTabelBarang();
            renderTabelApprover(); // Tampilkan approver dari DB saat halaman pertama kali dimuat

            // ===================================================================
            // 2. EVENT HANDLERS
            // ===================================================================

            // Pemicu untuk mendapatkan saran approver baru
            $('#nama_id, #ruangan_id').on('change', getSystemApprovers);

            // -- Manajemen Barang --
            $(document).on("click", "#btnTambahBarang", handleModalBarang);
            $(document).on('submit', '#form-tambah-barang', handleTambahBarang);
            $(document).on('click', '.btn-hapus-barang', function() {
                const barangId = parseInt($(this).data('id'));
                daftarBarang = daftarBarang.filter(item => parseInt(item.id) !== barangId);
                renderTabelBarang();
                getSystemApprovers(); // Update approver setelah barang dihapus
            });

            // -- Manajemen Approver --
            $(document).on("click", "#btnPilihApprover", handleModalApprover);
            $(document).on('click', '#btn-submit-approver', handleTambahApprover);
            $(document).on('click', '.btn-hapus-approver', function() {
                const approverId = parseInt($(this).data('id'));
                hapusApprover(approverId);
            });

            // ===================================================================
            // 3. FUNGSI-FUNGSI BANTUAN
            // ===================================================================

            function sweetAlert(message, icon = 'error') {
                const alert = Swal.fire({
                    title: "Info!",
                    text: message,
                    icon: "error"
                });
                return alert
            }

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
            daftarBarang.push({
                id: barangId,
                nama: barangText,
                jumlah: jumlah
            });
            renderTabelBarang();
            getSystemApprovers(); // Update approver setelah barang ditambah
            $('#modalTambahBarang').modal('hide');
        }

        function renderTabelBarang() {
            let html = '';
            daftarBarang.forEach((item, index) => {
                html += `
                <tr>
                    <td>${index + 1}</td>
                    <td>${item.nama}<input type="hidden" name="barang_id[]" value="${item.id}"></td>
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

        function handleTambahApprover(e) {
            e.preventDefault();
            const approverId = $('#approver_id').val();
            const approverName = $('#approver_id option:selected').text();
            if (!approverId || listApprover.some(item => item.id == approverId)) return;

            // Tandai approver yang ditambah manual
            listApprover.push({
                id: approverId,
                nama: approverName,
                is_manual: true
            });
            renderTabelApprover();
            $('#modalPilihApprover').modal('hide');
        }

        function getSystemApprovers() {
            const userId = $('#nama_id').val();
            const ruanganId = $('#ruangan_id').val();
            const barangIds = daftarBarang.map(item => item.id);
            if (!userId) return;

            $.get("{{ route('mahasiswa.peminjaman.list-approver') }}", {
                user_id: userId,
                ruangan_id: ruanganId,
                barang_id: barangIds,
            }).done(function(res) {
                const suggestions = res.approvers;

                // 1. Hapus saran sistem yang lama, TAPI pertahankan yang ditambah manual
                listApprover = listApprover.filter(approver => approver.is_manual === true);

                // 2. Tambahkan saran baru dari server
                suggestions.forEach(suggestion => {
                    const isAlreadyInList = listApprover.some(item => item.id == suggestion.id);
                    const wasManuallyRemoved = manuallyRemovedApprovers.includes(parseInt(suggestion
                        .id));

                    if (!isAlreadyInList && !wasManuallyRemoved) {
                        suggestion.is_manual = false; // Tandai sebagai saran sistem
                        listApprover.push(suggestion);
                    }
                });
                renderTabelApprover();
            });
        }

        function hapusApprover(id) {
            const approverId = parseInt(id);
            // Catat ID yang dihapus ke "daftar hitam" agar tidak disarankan lagi
            if (!manuallyRemovedApprovers.includes(approverId)) {
                manuallyRemovedApprovers.push(approverId);
            }
            listApprover = listApprover.filter(item => parseInt(item.id) !== approverId);
            renderTabelApprover();
        }

        function renderTabelApprover() {
            let html = '';
            listApprover.forEach((item, index) => {
                html += `
                <tr>
                    <td>${index + 1}</td>
                    <td>${item.nama}<input type="hidden" name="approver_id[]" value="${item.id}"></td>
                    <td><button type="button" class="btn btn-danger btn-sm btn-hapus-approver" data-id="${item.id}">Hapus</button></td>
                </tr>`;
            });
            $('#list-approver').html(html);
        }
        });
    </script>
@endpush
