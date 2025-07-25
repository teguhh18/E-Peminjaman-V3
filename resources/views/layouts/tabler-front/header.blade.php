<header class="navbar navbar-expand-md d-print-none bg-danger text-light">
    <div class="container-xl">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu"
            aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <h1 class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3">
            <a href="{{ route('home') }}">
                <img src="{{ asset('logo.png') }}" width="110" height="32" alt="Tabler"
                    class="navbar-brand-image text-white">
                <span class="text-white">E-Peminjaman</span>
            </a>
        </h1>
        <div class="navbar-nav flex-row order-md-last">

            <div class="nav-item dropdown">
                <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown"
                    aria-label="Open user menu">
                    @if (auth()->user()->foto)
                        <img src="{{ asset('storage/users/' . auth()->user()->foto) }}" alt="{{ auth()->user()->name }}"
                            class="avatar avatar-sm">
                    @else
                        <span class="avatar avatar-sm"
                            style="background-image: url({{ asset('img/user.png') }})"></span>
                    @endif
                    <div class="d-none d-xl-block ps-2">
                        <div>{{ auth()->user()->name }}</div>
                        <div class="mt-1 small text-dark">{{ auth()->user()->username }}</div>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <a href="{{ route('profil') }}" class="dropdown-item">Profil</a>
                    <a href="{{ route('perbarui_password') }}" class="dropdown-item">Ubah Password</a>
                    <a href="{{ route('logout') }}" class="dropdown-item"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
