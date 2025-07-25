<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>E-Peminjaman | Universitas Teknokrat Indonesia</title>
    <link rel="shortcut icon" type="image/x-icon"
        href="https://teknokrat.ac.id/wp-content/uploads/2022/04/UNIVERSITASTEKNOKRAT-e1647677057867-768x774-min.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
        integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    @include('layouts.css')
    @stack('css')
</head>

<body class="d-flex flex-column h-100">
    @include('layouts.navbar')
    <main class="flex-shrink-0">
        @yield('content')
    </main>
    @include('layouts.footer')
    @stack('js')
</body>

</html>
