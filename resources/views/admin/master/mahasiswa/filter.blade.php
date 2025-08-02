<form method="get">
    <div class="row">
        <div class="col-md-2">
            <div class="form-group mb-2">
                <select name="angkatan" class="form-control form-control-sm select2" required>
                    <option value="" selected disabled>Pilih</option>
                    @php
                        $now = date('Y');
                    @endphp
                    @for ($i = 2015; $i <= $now; $i++)
                        <option value="{{ substr($i, -2) }}" {{ $angkatan == substr($i, -2) ? 'selected' : '' }}>
                            {{ $i }}
                        </option>
                    @endfor
                </select>
            </div>
        </div>
        <div class="col-md-4 mb-2">
            <div class="form-group">
                <select name="prodi" class="form-control form-control-sm select2" required>
                    <option value="" selected disabled>Pilih</option>
                    @foreach ($dataProdi->data as $key)
                        <option value="{{ $key->id_prodi }}" {{ $idProdi == $key->id_prodi ? 'selected' : '' }}>
                            {{ $key->nama_prodi }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-6 mb-2">
            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-filter me-1"></i>
                    Filter</button>
            </div>
        </div>
    </div>
</form>
