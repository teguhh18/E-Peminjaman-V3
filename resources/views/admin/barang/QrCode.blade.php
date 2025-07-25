@foreach ($dataBarang as $barang)

<title>{{ $title }}</title>
    <div class="qr-code-container" style="display: inline-block; margin-right: 20px;">
        <p>
            Kode : {{ $barang->kode }}
        </p>
        <p>
            Nama : {{ $barang->nama }}
        </p>

        <div class="visible-print text-center">
            {!! QrCode::size(250)->generate(env('APP_URL') . 'master/barang/'.$barang->id.'/edit'); !!}
        </div>
    </div>
@endforeach
