@extends('layouts.tabler-admin.master')
@section('sub-breadcrumb', 'Scan Qr Code')
@section('content')

    <h1>Scan QR Code</h1>
    <div class="card mx-auto" style="max-width: 840px;">
        <div class="card-header text-center">
            <strong>QR Code Scanner</strong>
        </div>
        <div class="card-body d-flex justify-content-center">
            <div id="reader"></div>
            <button id="startButton" class="btn btn-primary mt-3">Mulai Scan</button>

        </div>
        <div class="card-footer text-center">
            <div id="scanError" class="text-danger ms-3"></div>
        </div>
    </div>

    @push('js')
        <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const startButton = document.getElementById('startButton');
                const readerElement = document.getElementById('reader');

                // 1. Deklarasikan variabel scanner.
                let html5QrcodeScanner;

                const onScanSuccess = (decodedText, decodedResult) => {
                    // console.log("Hasil scan:", decodedText);
                    // Setelah berhasil scan, clear camera dan scanner
                    html5QrcodeScanner.clear().then(_ => {
                        const checkUrlRoute = "{{ route('scan.pinjamRuangan.checkId', ':id') }}"; //route untuk mengecek ID booking/peminjaman
                        const checkUrl = checkUrlRoute.replace(':id', decodedText);
                        $.ajax({
                                method: "GET",
                                url: checkUrl,
                            })
                            .done(function(data) {
                                // console.log("Hasil check:", data.exists);
                                
                                if (data.exists) {  // Jika ID booking/peminjaman ada
                                    // Buka tab baru edit booking pakai id dari QR Code
                                    const editUrlBase = "{{ route('admin.booking.index') }}";
                                    const finalUrl = `${editUrlBase}/${decodedText}/edit`;

                                    window.open(finalUrl, '_blank'); // Buka tab baru

                                    // Tampilkan lagi tombol "Mulai Scan"
                                    startButton.style.display = 'block';
                                    readerElement.innerHTML = ''; // Kosongkan div reader
                                } else {
                                    // Jika ID booking/Peminjaman tidak ada
                                    document.getElementById('scanError').innerText =
                                        "Gagal memindai QR Code. QR Code tidak valid.";
                                    startButton.style.display = 'block';
                                }
                            })

                    }).catch(error => {
                        console.error("Gagal membersihkan scanner.", error);
                        startButton.style.display = 'block'; // Tampilkan tombol jika ada error
                    });
                };

                const onScanFailure = (error) => {
                    //
                };

                // Event listener untuk tombol "Mulai Scan"
                startButton.addEventListener('click', () => {
                    // 2. Buat instance BARU setiap kali tombol di-klik
                    html5QrcodeScanner = new Html5QrcodeScanner(
                        "reader", {
                            fps: 10,
                            qrbox: {
                                width: 300,
                                height: 300
                            }
                        },
                        false
                    );

                    // Render scanner
                    html5QrcodeScanner.render(onScanSuccess, onScanFailure);

                    // Sembunyikan tombol setelah scan dimulai
                    startButton.style.display = 'none';
                });
            });
        </script>
    @endpush

@endsection
