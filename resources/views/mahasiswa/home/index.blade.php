@extends('layouts.tabler-front.master')

@section('content')
    <style>
        .card-interactive {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card-interactive:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 30px -10px rgba(0, 0, 0, 0.3),
                0 0 25px rgba(231, 76, 60, 0.25);
            /* Bayangan hitam untuk kedalaman + cahaya merah untuk tema */
        }
    </style>
    <div class="row">
        <div class="col-md-12">
            {{-- Notifikasi --}}
            <div id="respon">
                @if (session()->has('msg'))
                    <div class="alert {{ session('class') ?? 'alert-info' }} alert-dismissible" role="alert">
                        <div class="d-flex">
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path d="M5 12l5 5l10 -10"></path>
                                </svg>
                            </div>
                            <div>
                                {{ session('msg') }}
                            </div>
                        </div>
                        <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
                    </div>
                @endif
            </div>

            {{-- Header Halaman --}}
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">List Peminjaman Aktif</h3>
                    <div class="card-actions d-flex gap-2">
                        <a href="{{ route('mahasiswa.peminjaman.create') }}" class="btn btn-red btn-sm">
                            <i class="fa fa-plus me-1"></i>
                            Buat Peminjaman
                        </a>
                        <a href="{{ route('mahasiswa.peminjaman.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fa fa-history me-1"></i>
                            Riwayat Peminjaman
                        </a>
                    </div>
                </div>
            </div>

            {{-- Konten List Peminjaman --}}
            <div class="card-body p-0">
                <div class="row">
                    @forelse ($listPeminjaman as $peminjaman)
                        <div class="col-md-6 col-lg-3 mb-4">
                            {{-- Kartu dengan tema gelap untuk kontras maksimal --}}
                            <div class="card h-100 shadow-lg border-0 card-interactive"
                                style="background-color: #2c3e50; color: #ecf0f1;">

                                <div class="card-body d-flex flex-column">
                                    {{-- Bagian utama yang fleksibel untuk mengisi ruang --}}
                                    <div class="flex-grow-1">
                                        {{-- Judul Kegiatan --}}
                                        <h3 class="card-title text-white mb-1">Kegiatan : {{ $peminjaman->kegiatan }}</h3>

                                        {{-- Info Ruangan --}}
                                        <p class="text-muted-light mb-4" style="color: #bdc3c7;">
                                            <i class="fa fa-building me-1"></i>
                                            Ruangan : {{ $peminjaman->ruangan->nama_ruangan ?? '-' }}
                                        </p>

                                        {{-- Visualisasi Waktu --}}
                                        <div class="d-flex align-items-center">
                                            {{-- Garis Waktu Visual --}}
                                            <div class="me-3">
                                                <div class="d-flex flex-column align-items-center">
                                                    <i class="ti ti-circle-filled"
                                                        style="color: #e74c3c; font-size: 0.8rem;"></i>
                                                    <div class="bg-red" style="width: 2px; height: 40px; opacity: 0.5;">
                                                    </div>
                                                    <i class="fa fa-circle" style="color: #e74c3c; font-size: 0.8rem;"></i>
                                                </div>
                                            </div>
                                            {{-- Detail Waktu --}}
                                            <div class="w-100">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span>Mulai</span>
                                                    <span
                                                        class="fw-bold">{{ \Carbon\Carbon::parse($peminjaman->waktu_peminjaman)->isoFormat('HH:mm') }}</span>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center text-muted-light"
                                                    style="color: #bdc3c7;">
                                                    <small>{{ \Carbon\Carbon::parse($peminjaman->waktu_peminjaman)->isoFormat('dddd, D MMM Y') }}</small>
                                                </div>
                                                <hr class="my-2" style="border-color: rgba(255,255,255,0.1);">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span>Selesai</span>
                                                    <span
                                                        class="fw-bold">{{ \Carbon\Carbon::parse($peminjaman->waktu_pengembalian)->isoFormat('HH:mm') }}</span>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center text-muted-light"
                                                    style="color: #bdc3c7;">
                                                    <small>{{ \Carbon\Carbon::parse($peminjaman->waktu_pengembalian)->isoFormat('dddd, D MMM Y') }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Tombol Aksi --}}
                                    {{-- <div class="mt-4 text-center">
                                        <a href="#" class="btn btn-red w-100 btn-detail"
                                            data-id="{{ $peminjaman->id }}">
                                            <i class="fa fa-eye me-1"></i>Detail
                                        </a>
                                    </div> --}}
                                </div>
                            </div>
                        </div>
                    @empty
                        {{-- Tampilan jika tidak ada data --}}
                        <div class="col-12">
                            <div class="text-center py-5">
                                <i class="ti ti-files-off" style="font-size: 3rem; color: #adb5bd;"></i>
                                <h3 class="mt-3">Tidak Ada Peminjaman Aktif</h3>
                                <p class="text-muted">Silakan buat peminjaman baru untuk memulai.</p>
                                <a href="{{ route('mahasiswa.peminjaman.create') }}" class="btn btn-red mt-2">
                                    <i class="ti ti-plus me-1"></i>
                                    Buat Peminjaman Baru
                                </a>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
