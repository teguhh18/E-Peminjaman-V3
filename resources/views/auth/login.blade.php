<!doctype html>

<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Login | E-Peminjaman</title>
    <!-- CSS files -->
    <link href="tabler/css/tabler.min.css?1692870487" rel="stylesheet" />
    <link href="tabler/css/tabler-flags.min.css?1692870487" rel="stylesheet" />
    <link href="tabler/css/tabler-payments.min.css?1692870487" rel="stylesheet" />
    <link href="tabler/css/tabler-vendors.min.css?1692870487" rel="stylesheet" />
    <link href="tabler/css/demo.min.css?1692870487" rel="stylesheet" />

    <link rel="icon" href="{{ asset('logo.png') }}" type="image/x-icon">
    <style>
        @import url('https://rsms.me/inter/inter.css');

        :root {
            --tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
        }

        body {
            font-feature-settings: "cv03", "cv04", "cv11";
        }
    </style>
</head>

<body class=" d-flex flex-column"
    style="background: rgb(64,165,120);
background: linear-gradient(339deg, rgba(64,165,120,1) 0%, rgba(156,219,166,1) 30%, rgba(80,180,152,1) 77%, rgba(0,103,105,1) 100%);">
    <script src="tabler/js/demo-theme.min.js?1692870487"></script>
    <div class="page
    page-center">
        <div class="container container-tight py-4">
            <div class="text-center mb-4">
                <a href="#" class="navbar-brand navbar-brand-autodark">
                    <img src="logo-brand.png" width="100%" height="52" alt="Tabler" class="navbar-brand-image"
                        style="height: 5rem !important">
                </a>
            </div>
            <div class="card card-md">
                <div class="card-body">
                    <h2 class="h2 text-center mb-4">E-Peminjaman</h2>
                    @if (session('status'))
                        <div class="alert alert-info alert-dark">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            {{ session('status') }}
                        </div>
                    @endif
                    <form action="{{ route('login') }}" method="post">
                        @method('post')
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <div class="input-group input-group-flat">
                                <span class="input-group-text">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="icon icon-tabler icon-tabler-user-square" width="44" height="44"
                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M9 10a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" />
                                        <path d="M6 21v-1a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v1" />
                                        <path
                                            d="M3 5a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v14a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-14z" />
                                    </svg>
                                </span>
                                <input type="text" value="{{ old('email') }}" name="email"
                                    class="form-control  @error('email') is-invalid @enderror" placeholder="Username"
                                    autofocus>

                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">
                                Password

                            </label>
                            <div class="input-group input-group-flat">
                                <span class="input-group-text">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="icon icon-tabler icon-tabler-lock-square" width="44" height="44"
                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path
                                            d="M8 11m0 1a1 1 0 0 1 1 -1h6a1 1 0 0 1 1 1v3a1 1 0 0 1 -1 1h-6a1 1 0 0 1 -1 -1z" />
                                        <path d="M10 11v-2a2 2 0 1 1 4 0v2" />
                                        <path
                                            d="M4 4m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" />
                                    </svg>
                                </span>
                                <input type="password" name="password" class="form-control " placeholder="Password">

                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-footer">
                            <button type="submit" class="btn btn-primary w-100">Sign in</button>
                        </div>
                    </form>
                </div>
                <div class="hr-text"></div>

            </div>

        </div>
    </div>
    <!-- Libs JS -->
    <!-- Tabler Core -->
    <script src="tabler/js/tabler.min.js?1692870487" defer></script>
    <script src="tabler/js/demo.min.js?1692870487" defer></script>
</body>

</html>
