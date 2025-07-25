@extends('layouts.tabler-admin.master')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="row">
        <div class="col-md-12">
            {{-- <div id="respon">
                @if (session()->has('msg'))
                    <div class="alert {{ session('class') }} alert-dark">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        {{ session('msg') }}
                    </div>
                @endif
            </div> --}}
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Jadwal Penggunaan Barang</div>
                    <div class="card-actions">
                        <form method="get" class="row align-items-end">
                            @csrf
                            <div class="col-md-8 mb-0">
                                <label for="barang_id" class="form-label">Pilih Barang</label>
                                {{-- <select name="barang_id" id="barang_id"
                                    class="form-control @error('barang_id') is-invalid @enderror">
                                    <option value="">-Barang-</option>
                                    @foreach ($barang as $key)
                                        <option value="{{ $key->id }}"
                                            {{ request('barang_id') == $key->id ? 'selected' : '' }}>
                                            {{ $key->nama }}</option>
                                    @endforeach
                                </select> --}}
                                <select name="barang_id" id="barang_id" class="form-control form-control-sm" required>
                                    <option value=" " selected>Pilih</option>
                                    @foreach ($barang as $key)
                                        <option value="{{ $key->id }}"
                                            {{ request('barang_id') == $key->id ? 'selected' : '' }}>
                                            {{ $key->nama }}</option>
                                    @endforeach
                                </select>
                                @error('barang_id')
                                    <small class="invalid-feedback">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-0">
                                <button type="submit" class="btn btn-primary w-100 mt-4 mt-md-0">
                                    <i class="fa fa-filter"></i>&nbsp; Filter
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
                        <h5 class="modal-title" id="modalTitle">Detail Pinjam Barang</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="modalBody">
                            <p><strong>Barang:</strong> <span id="barang"></span></p>
                            <p><strong>Mulai:</strong> <span id="eventStart"></span></p>
                            <p><strong>Selesai:</strong> <span id="eventEnd"></span></p>
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

                    // Memasukkan data event ke dalam elemen modal
                    document.getElementById('modalTitle').innerText = event.title;
                    document.getElementById('eventStart').innerText = startDate;
                    document.getElementById('eventEnd').innerText = endDate;

                    // Untuk data custom, gunakan 'extendedProps'
                    document.getElementById('barang').innerText = event.extendedProps.nama_barang;

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
            $('#barang_id').select2();
        });
    </script>
@endpush
