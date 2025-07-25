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
                    <div class="card-title">List Penggunaan Ruangan</div>

                    <div class="card-actions d-flex justify-content-end align-items-end gap-2 flex-wrap">
                        <a href="{{ route('mahasiswa.peminjaman.create') }}" class="btn btn-success">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-category-plus">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M4 4h6v6h-6zm10 0h6v6h-6zm-10 10h6v6h-6zm10 3h6m-3 -3v6" />
                            </svg>
                            Buat Peminjaman
                        </a>


                        <a href="{{ route('mahasiswa.peminjaman.index') }}" class="btn btn-warning">
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
                        @foreach ($listPeminjaman as $item)
                            <div class="col-md-3 mb-4">
                                <div class="card h-100 shadow-sm">
                                    <div class="card-header bg-primary text-white">
                                        <h5 class="card-title mb-0">{{ $item->kegiatan }}</h5>
                                    </div>
                                    <div class="card-body">
                                        @if ($item->ruangan_id != null)
                                            <p class="mb-1"><strong>Ruangan:</strong>
                                                {{ $item->ruangan->nama_ruangan ?? '-' }}</p>
                                        @endif
                                        <p class="mb-1"><strong>Peminjam:</strong> {{ $item->user->name ?? '-' }}</p>
                                        <p class="mb-0"><strong>Mulai:</strong>
                                            {{ \Carbon\Carbon::parse($item->waktu_peminjaman)->format('d M Y H:i') }}
                                        </p>
                                        <p class="mb-0"><strong>Selesai:</strong>
                                            {{ \Carbon\Carbon::parse($item->waktu_pengembalian)->format('d M Y H:i') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>

        </div>
    </div>
@endsection
