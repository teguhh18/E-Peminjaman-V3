@extends('templateAdminLTE/home')
@section('sub-breadcrumb', 'Halaman Utama')
@section('content')
    <div class="row">

        <div class="col-md-3">
            <a href="/master/kegiatan" class="box">
                <div class="box-row">
                    <div class="box-cell p-x-3 p-y-1 bg-primary">
                        <div class="pull-xs-left font-weight-semibold font-size-12">Jumlah Keseluruhan Aset</div>
                    </div>
                </div>
                <div class="box-row">
                    <div class="box-cell p-x-3 p-y-2 bg-primary darken">
                        <i class="box-bg-icon middle left font-size-52 fa fa-boxes-stacked"></i>
                        <div class="pull-xs-right font-weight-semibold font-size-24 line-height-1">
                            {{ $data['jml_aset'] }}</div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-3">
            <a href="/master/kegiatan" class="box">
                <div class="box-row">
                    <div class="box-cell p-x-3 p-y-1 bg-danger">
                        <div class="pull-xs-left font-weight-semibold font-size-12">Jumlah Nominal Aset</div>
                    </div>
                </div>
                <div class="box-row">
                    <div class="box-cell p-x-3 p-y-2 bg-danger darken">
                        <i class="box-bg-icon middle left font-size-52 fa fa-money"></i>
                        <div class="pull-xs-right font-weight-semibold font-size-24 line-height-1">
                            Rp.{{ number_format($data['jml_nominal_aset'], 0, ',', '.') }}</div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-3">
            <a href="/master/kegiatan" class="box">
                <div class="box-row">
                    <div class="box-cell p-x-3 p-y-1 bg-warning">
                        <div class="pull-xs-left font-weight-semibold font-size-12">Jumlah Gedung</div>
                    </div>
                </div>
                <div class="box-row">
                    <div class="box-cell p-x-3 p-y-2 bg-warning darken">
                        <i class="box-bg-icon middle left font-size-52 fa fa-building"></i>
                        <div class="pull-xs-right font-weight-semibold font-size-24 line-height-1">
                            {{ $data['jml_gedung'] }}</div>
                    </div>
                </div>
            </a>
        </div>


        <div class="col-md-3">
            <a href="/master/kegiatan" class="box">
                <div class="box-row">
                    <div class="box-cell p-x-3 p-y-1 bg-success">
                        <div class="pull-xs-left font-weight-semibold font-size-12">Jumlah Ruangan</div>
                    </div>
                </div>
                <div class="box-row">
                    <div class="box-cell p-x-3 p-y-2 bg-success darken">
                        <i class="box-bg-icon middle left font-size-52 fa-brands fa-chromecast"></i>
                        <div class="pull-xs-right font-weight-semibold font-size-24 line-height-1">
                            {{ $data['jml_ruangan'] }}</div>
                    </div>
                </div>
            </a>
        </div>
    </div>
    @include('admin.home.grafik')
@endsection
