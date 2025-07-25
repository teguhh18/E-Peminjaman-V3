<header>
    <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <img src="https://teknokrat.ac.id/wp-content/themes/education_package/education/images/logo.png"
                    alt="Logo" height="45" />
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse"
                aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <ul class="navbar-nav me-auto mb-2 mb-md-0">
                    <!-- <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Link</a>
                    </li> -->
                </ul>

                <form class="d-flex" role="search" method="get" action="#">
                    {{-- <input class="form-control me-2" name="search" value="{{ request('search') }}" type="search"
                        placeholder="Pencarian game" aria-label="Search">
                    <button class="btn btn-outline-success" type="submit"><i class="fa fa-search"></i></button> --}}
                    <a href="ok" class="btn border-0 text-white">{{ Auth::user()->name }}</a>
                    {{-- <button class="btn btn-outline-success" type="submit"><i class="fa fa-search"></i></button> --}}
                </form>
            </div>
        </div>
    </nav>
</header>
