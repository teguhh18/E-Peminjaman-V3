@extends('layouts.tabler-front.master')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="row">
        <div class="col-md-12">
            <div id="respon">
                @if (session()->has('msg'))
                    <div class="alert {{ session('class') }} alert-dark">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        {{ session('msg') }}
                    </div>
                @endif
            </div>
            <div class="card">
                <div class="card-header">
                    <div class="card-title">List Peminjaman</div>

                    <div class="card-actions d-flex justify-content-end align-items-end gap-2 flex-wrap">
                        <a href="{{ route('mahasiswa.peminjaman.create') }}" class="btn btn-sm btn-teal">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-category-plus">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M4 4h6v6h-6zm10 0h6v6h-6zm-10 10h6v6h-6zm10 3h6m-3 -3v6" />
                            </svg>
                            Buat Peminjaman
                        </a>
                        <a href="{{ route('mahasiswa.peminjaman.index') }}" class="btn btn-sm btn-warning">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-history">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M12 8l0 4l2 2" />
                                <path d="M3.05 11a9 9 0 1 1 .5 4m-.5 5v-5h5" />
                            </svg>
                            Riwayat Peminjaman
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        @forelse ($listPeminjaman as $peminjaman)
                            <div class="col-md-6 col-lg-4 mb-4">
                                {{-- Card utama dengan warna yang sesuai navbar dan shadow --}}
                                <div class="card h-100 shadow-sm border-0 bg-warning-lt">
                                    <div class="card-body d-flex flex-column">
                                        {{-- Judul Kegiatan --}}
                                        <h4 class="card-title text-dark">{{ $peminjaman->kegiatan }}</h4>
                                        <hr class="my-2">

                                        {{-- Detail Peminjaman --}}
                                        <div class="text-muted">
                                            <p class="mb-1">
                                                <i class="ti ti-door-enter me-1"></i>
                                                <strong>Ruangan:</strong> {{ $peminjaman->ruangan->nama_ruangan ?? '-' }}
                                            </p>
                                            <p class="mb-1">
                                                <i class="ti ti-user me-1"></i>
                                                <strong>Peminjam:</strong> {{ $peminjaman->user->name }}
                                            </p>
                                            <p class="mb-1">
                                                <i class="ti ti-calendar-event me-1"></i>
                                                <strong>Mulai:</strong>
                                                {{ \Carbon\Carbon::parse($peminjaman->waktu_peminjaman)->isoFormat('D MMM Y, HH:mm') }}
                                            </p>
                                            <p class="mb-1">
                                                <i class="ti ti-calendar-off me-1"></i>
                                                <strong>Selesai:</strong>
                                                {{ \Carbon\Carbon::parse($peminjaman->waktu_pengembalian)->isoFormat('D MMM Y, HH:mm') }}
                                            </p>
                                        </div>

                                       
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="alert alert-info">
                                    Tidak ada data peminjaman yang aktif saat ini.
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>



            </div>

        </div>
    </div>
@endsection
