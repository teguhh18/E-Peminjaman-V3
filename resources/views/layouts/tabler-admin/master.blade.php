<!doctype html>

<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>{{ isset($title) ? $title : 'E-Peminjaman' }}</title>
    <!-- CSS files -->
    <link rel="shortcut icon" href="{{ asset('logo.png') }}" type="image/x-icon">

    <link href="{{ asset('tabler/css/tabler.min.css?1692870487') }}" rel="stylesheet" />
    <link href="{{ asset('tabler/css/tabler-flags.min.css?1692870487') }}" rel="stylesheet" />
    <link href="{{ asset('tabler/css/tabler-payments.min.css?1692870487') }}" rel="stylesheet" />
    <link href="{{ asset('tabler/css/tabler-vendors.min.css?1692870487') }}" rel="stylesheet" />
    <link href="{{ asset('tabler/css/demo.min.css?1692870487') }}" rel="stylesheet" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"
        integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    {{-- Data Table --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.2/css/dataTables.dataTables.css" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        @import url('https://rsms.me/inter/inter.css');

        :root {
            --tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
        }

        body {
            font-feature-settings: "cv03", "cv04", "cv11";
        }
    </style>
    {{-- Select2 --}}
   <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
</head>

<body>
    <script src="{{ asset('tabler/js/demo-theme.min.js?1692870487') }}"></script>
    <div class="page">
        @include('layouts.tabler-admin.sidebar')
        
        <div class="page-wrapper">
            @include('layouts.tabler-admin.header')
            <div class="page-body">
                <div class="container-xl">
                    @yield('content')
                </div>
            </div>


            @include('layouts.tabler-admin.footer')
        </div>
    </div>

    <!-- Libs JS -->
    <!-- Tabler Core -->
    <script src="{{ asset('tabler/js/tabler.min.js?1692870487') }}" defer></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.2/js/dataTables.js"></script>

    {{-- instajs scan QR --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/instascan/1.0.0/instascan.min.js"></script>

    <!-- sweetalert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-chained/1.0.1/jquery.chained.min.js"
        integrity="sha512-rcWQG55udn0NOSHKgu3DO5jb34nLcwC+iL1Qq6sq04Sj7uW27vmYENyvWm8I9oqtLoAE01KzcUO6THujRpi/Kg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    {{-- 
    <script>
        $('#datatables').dataTable();
        $('#datatables2').dataTable();
    </script> --}}
    {{-- Select2 --}}
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ asset('form-input.js') }}" defer></script>

    @stack('js')
</body>

</html>
