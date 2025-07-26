{{-- Tampilkan layout untuk halaman perbarui-profil berdasarkan level --}}
@php
    $layout = (auth()->user()->level === 'mahasiswa') 
                ? 'layouts.tabler-front.master' 
                : 'layouts.tabler-admin.master';
@endphp

@extends($layout)

@section('content')
<div class="page-header">
    <div class="container">
        <h1 class="page-title">Perbarui Profil Anda</h1>
    </div>
</div>

<div class="page-body">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <form class="card" action="{{ route('profil_update', encrypt($user->id)) }}" method="POST" enctype="multipart/form-data">
                    @method('put')
                    @csrf
                    <div class="card-body">
                        @if (session('msg'))
                            <div class="alert alert-success" role="alert">
                                {{ session('msg') }}
                            </div>
                        @endif
                        @if ($errors->any())
                            <div class="alert alert-danger" role="alert">
                                <h4 class="alert-title">Oops, terjadi kesalahan...</h4>
                                <div class="text-muted">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif

                        <h3 class="card-title">Detail Profil</h3>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Nama</label>
                                <input type="text" class="form-control" value="{{ $user->name }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Username / NPM</label>
                                <input type="text" class="form-control" value="{{ $user->username }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $user->email) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">No. Telepon (Format: 628...)</label>
                                <input type="text" class="form-control @error('no_telepon') is-invalid @enderror" id="no_telepon" name="no_telepon" value="{{ old('no_telepon', $user->no_telepon) }}">
                            </div>
                        </div>

                        <hr class="my-4">

                        <h3 class="card-title">Foto & Tanda Tangan</h3>
                        <div class="row g-3">
                            {{-- Preview Foto --}}
                            <div class="col-md-6">
                                <div class="form-label">Foto Profil</div>
                                <div class="mb-3 p-2">
                                    <img src="{{ $user->foto ? asset('storage/users/' . $user->foto) : 'https://via.placeholder.com/300' }}" 
                                         alt="Preview Foto" class="img-preview avatar avatar-xl mb-2 rounded">
                                </div>
                                <input type="file" class="form-control" name="foto" id="foto" accept="image/*">
                                <small class="form-hint">Maks. 2MB. Format: JPG, PNG, WEBP</small>
                            </div>

                            {{-- Preview Tanda Tangan --}}
                            <div class="col-md-6">
                                <div class="form-label">Tanda Tangan</div>
                                <div class="mb-3 p-2 border rounded" style="background-color: #f8f9fa;">
                                    <img src="{{ $user->tanda_tangan ? asset('storage/tanda_tangan/' . $user->tanda_tangan) : 'https://via.placeholder.com/300x150' }}" 
                                         alt="Preview Tanda Tangan" class="tanda-tangan-preview img-fluid mb-2 d-block mx-auto" style="max-height: 80px;">
                                </div>
                                <input type="file" class="form-control" name="tanda_tangan" id="tanda_tangan" accept="image/*">
                                <small class="form-hint">Maks. 2MB. Tanda tangan dengan background transparan disarankan.</small>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer text-end">
                        <a href="{{ route('home.index') }}" class="btn">Kembali</a>
                        <button type="submit" class="btn btn-warning"><i class="ti ti-device-floppy me-1"></i> Update Profil</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        // --- FUNGSI PREVIEW GAMBAR UNIVERSAL ---
        function setupImagePreview(inputId, previewClass) {
            const imageInput = document.getElementById(inputId);
            const imgPreview = document.querySelector(previewClass);

            if (!imageInput) return;

            imageInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        imgPreview.src = e.target.result;
                    }
                    reader.readAsDataURL(this.files[0]);
                }
            });
        }

        // Terapkan fungsi ke foto profil dan tanda tangan
        setupImagePreview('foto', '.img-preview');
        setupImagePreview('tanda_tangan', '.tanda-tangan-preview');

        // --- FUNGSI VALIDASI NO. TELEPON ---
        const phoneInput = document.getElementById('no_telepon');
        if (phoneInput) {
            phoneInput.addEventListener('input', function(e) {
                let value = e.target.value;
                // Hanya izinkan angka
                value = value.replace(/\D/g, '');
                // Ubah '08' di awal menjadi '628'
                if (value.startsWith('08')) {
                    value = '628' + value.slice(2);
                }
                e.target.value = value;
            });
        }
    });
</script>
@endpush
