@extends('layouts.tabler-admin.master')
@section('content')
    <div class="row row-cards">
        <div class="col-sm-12 col-md-6 col-lg-4">
            {{-- Desain "Split Card" yang modern --}}
            <div class="card h-100">
                <div class="row g-0">
                    {{-- Bagian Kiri: Panel Ikon dengan Gradien --}}
                    <div class="col-3" style="background-image: linear-gradient(to bottom, #467fcf 0%, #2462c4 100%);">
                        <div class="d-flex align-items-center justify-content-center h-100">
                            <i class="fa fa-tasks text-white" style="font-size: 2.5rem; opacity: 0.8;"></i>
                        </div>
                    </div>

                    {{-- Bagian Kanan: Konten dan Data --}}
                    <div class="col-9">
                        <div class="card-body">
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

                            {{-- Angka Utama (Fokus) --}}
                            <div class="h1 fw-bold text-primary mb-0" id="status-count">{{ $count }}</div>

                            {{-- Judul Dinamis --}}
                            <div class="text-muted" id="status-title">{{ $count_title }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-12 col-md-6 col-lg-4">
            {{-- Desain "Split Card" yang modern --}}
            <div class="card h-100">
                <div class="row g-0">
                    {{-- Bagian Kiri: Panel Ikon dengan Gradien --}}
                    <div class="col-3" style="background-image: linear-gradient(to bottom, #467fcf 0%, #2462c4 100%);">
                        <div class="d-flex align-items-center justify-content-center h-100">
                            <i class="fa fa-cube text-white" style="font-size: 2.5rem; opacity: 0.8;"></i>
                        </div>
                    </div>

                    {{-- Bagian Kanan: Konten dan Data --}}
                    <div class="col-9">
                        <div class="card-body">

                            {{-- Angka Utama (Fokus) --}}
                            <div class="h1 fw-bold text-primary mb-0" id="status-count">{{ $count_barang }}</div>

                            {{-- Judul Dinamis --}}
                            <div class="text-muted">Total Barang</div>
                        </div>
                    </div>
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
@endpush
