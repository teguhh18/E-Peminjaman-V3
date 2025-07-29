@extends('layouts.tabler-admin.master')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Jadwal Peminjaman</div>
                    <div class="card-actions">
                        <form method="get" class="row align-items-end">
                            @csrf
                            <div class="col-md-8 mb-0">
                                <label for="ruangan_id" class="form-label">Pilih Ruangan</label>
                                <select name="ruangan_id" id="ruangan_id"
                                    class="form-control @error('ruangan_id') is-invalid @enderror">
                                    <option value="">-Semua Ruangan-</option>
                                    @foreach ($ruangan as $key)
                                        <option value="{{ $key->id }}"
                                            {{ request('ruangan_id') == $key->id ? 'selected' : '' }}>
                                            {{ $key->nama_ruangan }}</option>
                                    @endforeach
                                </select>
                                @error('ruangan_id')
                                    <small class="invalid-feedback">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-0">
                                <button type="submit" class="btn btn-primary btn-sm w-100 mt-4 mt-md-0">
                                    <i class="fa fa-filter me-1"></i>Filter
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <div id="calendar"></div>
                </div>
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
    </div>
@endsection
@push('js')
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
                    // console.log(info.event.title);
                    // console.log(info.event.start);
                    // console.log(info.event.end);
                    // console.log(info.event.extendedProps.ruangan);
                    // console.log(info.event.extendedProps.mahasiswa);
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
