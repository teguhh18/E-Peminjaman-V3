@extends('layouts.tabler-admin.master')
@section('content')
    <div class="row row-cards">
        {{-- ======================= KARTU PEMINJAMAN ======================= --}}
        <div class="col-sm-12 col-md-6 col-lg-4">
            <div class="card h-100">
                <div class="row g-0 h-100">
                    {{-- Bagian Kiri: Panel Ikon --}}
                    <div class="col-3" style="background-image: linear-gradient(to bottom, #467fcf 0%, #2462c4 100%);">
                        <div class="d-flex align-items-center justify-content-center h-100">
                            <i class="fa fa-tasks text-white" style="font-size: 2.5rem; opacity: 0.8;"></i>
                        </div>
                    </div>

                    {{-- Bagian Kanan: Konten dan Data --}}
                    <div class="col-9">
                        {{-- Tambahkan d-flex flex-column h-100 untuk kontrol vertikal --}}
                        <div class="card-body d-flex flex-column h-100">
                            {{-- Filter Dropdown di pojok kanan atas --}}
                            <div class="d-flex justify-content-end">
                                <div class="dropdown bg-azure">
                                    <button type="button" class="btn btn-sm btn-ghost-secondary dropdown-toggle text-white"
                                        data-bs-toggle="dropdown">
                                        Filter Status
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item filter-status" href="#" data-status="semua">Semua</a>
                                        <a class="dropdown-item filter-status" href="#"
                                            data-status="menunggu">Menunggu</a>
                                        <a class="dropdown-item filter-status" href="#"
                                            data-status="disetujui">Disetujui</a>
                                        <a class="dropdown-item filter-status" href="#" data-status="aktif">Aktif</a>
                                        <a class="dropdown-item filter-status" href="#"
                                            data-status="selesai">Selesai</a>
                                    </div>
                                </div>
                            </div>

                            {{-- Konten utama yang akan mengisi sisa ruang --}}
                            <div class="flex-grow-1 d-flex flex-column justify-content-center">
                                <div class="h1 fw-bold text-primary mb-0" id="status-count">{{ $count }}</div>
                                <div class="text-muted" id="status-title">{{ $count_title }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ======================= KARTU BARANG ======================= --}}
        <div class="col-sm-12 col-md-6 col-lg-4">
            <div class="card h-100">
                <div class="row g-0 h-100">
                    {{-- Bagian Kiri: Panel Ikon --}}
                    <div class="col-3" style="background-image: linear-gradient(to bottom, #467fcf 0%, #2462c4 100%);">
                        <div class="d-flex align-items-center justify-content-center h-100">
                            <i class="fa fa-cube text-white" style="font-size: 2.5rem; opacity: 0.8;"></i>
                        </div>
                    </div>

                    {{-- Bagian Kanan: Konten dan Data --}}
                    <div class="col-9">
                        {{-- Tambahkan d-flex flex-column h-100 untuk kontrol vertikal --}}
                        <div class="card-body d-flex flex-column h-100">
                            {{-- Konten utama yang akan mengisi sisa ruang --}}
                            <div class="flex-grow-1 d-flex flex-column justify-content-center">
                                <div class="h1 fw-bold text-primary mb-0">{{ $count_barang }}</div>
                                <div class="text-muted">Total Barang</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- ======================= KARTU RUANGAN ======================= --}}
        <div class="col-sm-12 col-md-6 col-lg-4">
            <div class="card h-100">
                <div class="row g-0 h-100">
                    {{-- Bagian Kiri: Panel Ikon --}}
                    <div class="col-3" style="background-image: linear-gradient(to bottom, #467fcf 0%, #2462c4 100%);">
                        <div class="d-flex align-items-center justify-content-center h-100">
                            <i class="fa fa-building text-white" style="font-size: 2.5rem; opacity: 0.8;"></i>
                        </div>
                    </div>

                    {{-- Bagian Kanan: Konten dan Data --}}
                    <div class="col-9">
                        {{-- Tambahkan d-flex flex-column h-100 untuk kontrol vertikal --}}
                        <div class="card-body d-flex flex-column h-100">
                            {{-- Konten utama yang akan mengisi sisa ruang --}}
                            <div class="flex-grow-1 d-flex flex-column justify-content-center">
                                <div class="h1 fw-bold text-primary mb-0">{{ $count_ruangan }}</div>
                                <div class="text-muted">Total Ruangan</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-header">
            <h2>
                Jadwal Peminjaman Disetujui & Aktif
            </h2>
        </div>
        <div class="card-body">
            <div id="calendar"></div>
        </div>
    </div>

    {{-- MODAL DETAIL JADWAL/EVENT --}}
        <div class="modal fade" id="detailJadwal" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitle">Detail Acara</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="modalBody">
                            <p><strong>Nama:</strong> <span id="nama"></span></p>
                            <p><strong>Ruangan:</strong> <span id="ruangan"></span></p>
                            <p><strong>Mulai:</strong> <span id="eventStart"></span></p>
                            <p><strong>Selesai:</strong> <span id="eventEnd"></span></p>
                            <div>
                                <strong>Barang:</strong>
                                <ul id="daftar-barang" style="margin-top: 5px; padding-left: 20px;">
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
@endsection

@push('js')
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(document).ready(function() {
            $(document).on("click", ".filter-status", function(e) {
                e.preventDefault(); // Mencegah link '#' melompat ke atas halaman/url

                // Gunakan .data() untuk mengambil atribut data-status
                var status_filter = $(this).data("status");

                const titleElement = $('#status-title');
                const countElement = $('#status-count');

                // Tampilkan status loading
                titleElement.html('<i class="fa fa-spinner fa-spin"></i> Memuat...');
                countElement.text('');

                $.get("{{ route('admin.dashboard.filter_status') }}", {
                    status: status_filter,
                }, function(res) {
                    // Format judul agar lebih rapi
                    let titleText = res.count_title === 'semua' ?
                        'Total Semua Peminjaman' :
                        'Peminjaman ' + res.count_title;

                    // Update tampilan kartu dengan data baru dari controller
                    titleElement.text(titleText);
                    countElement.text(res.count);

                }).fail(function() {
                    titleElement.text('Gagal memuat data');
                    alert('Terjadi kesalahan saat mengambil data.');
                });
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                slotMinTime: '7:00:00',
                slotMaxTime: '21:00:00',
                events: @json($events),
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                selectable: true,
                eventClick: function(info) {
                    info.jsEvent.preventDefault();

                    // Mengambil data dari event yang diklik
                    let event = info.event;

                    // Menyiapkan format tanggal yang mudah dibaca
                    let options = {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    };
                    let startDate = event.start ? event.start.toLocaleString('id-ID', options) :
                        'Tidak ada';
                    let endDate = event.end ? event.end.toLocaleString('id-ID', options) : 'Tidak ada';

                    // if (event.extendedProps.detail) {
                    //     const details = event.extendedProps.detail
                    // }
                    // Memasukkan data event ke dalam elemen modal
                    document.getElementById('modalTitle').innerText = event.title;
                    document.getElementById('eventStart').innerText = startDate;
                    document.getElementById('eventEnd').innerText = endDate;

                    // Untuk data custom, gunakan 'extendedProps'
                    document.getElementById('ruangan').innerText = event.extendedProps.ruangan != null ?
                        event.extendedProps.ruangan : '-';
                    document.getElementById('nama').innerText = event.extendedProps.mahasiswa;
                    // document.getElementById('barang').innerText = event.extendedProps.mahasiswa;
                    if (event.extendedProps.detail) {
                        const details = event.extendedProps.detail;
                        const listContainer = document.getElementById(
                        'daftar-barang'); // Target elemen <ul>

                        // Kosongkan daftar sebelumnya untuk menghindari duplikasi
                        listContainer.innerHTML = '';

                        if (details.length > 0) {
                            // 1. Buat string HTML untuk setiap item dalam format <li>...</li>
                            const listItemsHTML = details.map(detail => {
                                const namaBarang = detail.barang ? detail.barang.nama :
                                    'Nama tidak diketahui';
                                return `<li>${namaBarang} (${detail.jml_barang})</li>`;
                            }).join(''); // 2. Gabungkan semua string <li> menjadi satu blok HTML

                            // 3. Masukkan blok HTML ke dalam <ul>
                            listContainer.innerHTML = listItemsHTML;
                        } else {
                            // Jika tidak ada barang, tampilkan placeholder dalam format list
                            listContainer.innerText = '-';
                        }
                    }
                    // Menampilkan modal
                    var myModal = new bootstrap.Modal(document.getElementById('detailJadwal'), {
                        keyboard: false
                    });
                    myModal.show();
                },

                eventContent: function(arg) {
                    let title = arg.event.title;
                    let ruangan = arg.event.extendedProps.ruangan;

                    // Gunakan kelas utilitas Bootstrap untuk styling
                    let newHtml = `
                                    <div class="p-1">
                                        <div class="fw-bold text-truncate">${title}</div>
                                        <div class="small text-truncate">${ruangan || ''}</div>
                                    </div>
                                    `;

                    return {
                        html: newHtml
                    };
                },
            });
            calendar.render();
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#ruangan_id').select2({

            });
        });
    </script>
@endpush
