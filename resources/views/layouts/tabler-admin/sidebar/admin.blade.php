<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle show" href="#navbar-base" data-bs-toggle="dropdown" data-bs-auto-close="false"
        role="button" aria-expanded="false">
        <span
            class="nav-link-icon d-md-none d-lg-inline-block"><!-- Download SVG icon from http://tabler-icons.io/i/package -->
            <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-books"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 4m0 1a1 1 0 0 1 1 -1h2a1 1 0 0 1 1 1v14a1 1 0 0 1 -1 1h-2a1 1 0 0 1 -1 -1z" /><path d="M9 4m0 1a1 1 0 0 1 1 -1h2a1 1 0 0 1 1 1v14a1 1 0 0 1 -1 1h-2a1 1 0 0 1 -1 -1z" /><path d="M5 8h4" /><path d="M9 16h4" /><path d="M13.803 4.56l2.184 -.53c.562 -.135 1.133 .19 1.282 .732l3.695 13.418a1.02 1.02 0 0 1 -.634 1.219l-.133 .041l-2.184 .53c-.562 .135 -1.133 -.19 -1.282 -.732l-3.695 -13.418a1.02 1.02 0 0 1 .634 -1.219l.133 -.041z" /><path d="M14 9l4 -1" /><path d="M16 16l3.923 -.98" />
            </svg>
        </span>
        <span class="nav-link-title">
            Peminjaman
        </span>
    </a>
   
    <div class="dropdown-menu show">
        <div class="dropdown-menu-columns">
            <div class="dropdown-menu-column">
                <a class="dropdown-item" href="{{ route('admin.booking.index') }}">
                    Peminjaman
                </a>
            </div>
        </div>
         <div class="dropdown-menu-columns">
            <div class="dropdown-menu-column">
                <a class="dropdown-item" href="{{ route('admin.peminjaman.index') }}">
                    Riwayat Peminjaman
                </a>
            </div>
        </div>
         <div class="dropdown-menu-columns">
            <div class="dropdown-menu-column">
                <a class="dropdown-item" href="{{ route('admin.jadwal') }}">
                    Jadwal Ruangan
                </a>
            </div>
        </div>
    </div>
</li>

<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle show" href="#navbar-base" data-bs-toggle="dropdown" data-bs-auto-close="false"
        role="button" aria-expanded="false">
        <span
            class="nav-link-icon d-md-none d-lg-inline-block"><!-- Download SVG icon from http://tabler-icons.io/i/package -->
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24"
                stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M12 3l8 4.5l0 9l-8 4.5l-8 -4.5l0 -9l8 -4.5" />
                <path d="M12 12l8 -4.5" />
                <path d="M12 12l0 9" />
                <path d="M12 12l-8 -4.5" />
                <path d="M16 5.25l-8 4.5" />
            </svg>
        </span>
        <span class="nav-link-title">
            Master Data
        </span>
    </a>
    <div class="dropdown-menu show">
        <div class="dropdown-menu-columns">
            <div class="dropdown-menu-column">
                <a class="dropdown-item {{ request()->is('master/barang*') ? 'active' : '' }}" href="{{ route('admin.barang.index') }}">
                    Barang
                </a>
            </div>
        </div>
        <div class="dropdown-menu-columns">
            <div class="dropdown-menu-column">
                <a class="dropdown-item" href="{{ route('admin.ruangan.index') }}">
                    Ruangan
                </a>
            </div>
        </div>
        <div class="dropdown-menu-columns">
            <div class="dropdown-menu-column">
                <a class="dropdown-item" href="{{ route('admin.gedung.index') }}">
                    Gedung
                </a>
            </div>
        </div>
         <div class="dropdown-menu-columns">
            <div class="dropdown-menu-column">
                <a class="dropdown-item" href="{{ route('admin.unit.index') }}">
                    Unit Kerja
                </a>
            </div>
        </div>
    </div>
</li>

<li class="nav-item {{ request()->is('master/user*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('admin.user.index') }}">
        <span
            class="nav-link-icon d-md-none d-lg-inline-block"><!-- Download SVG icon from http://tabler-icons.io/i/home -->
            <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-users"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" /><path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /><path d="M16 3.13a4 4 0 0 1 0 7.75" /><path d="M21 21v-2a4 4 0 0 0 -3 -3.85" />
            </svg>
        </span>
        <span class="nav-link-title">
            User
        </span>
    </a>
</li>
<li class="nav-item {{ request()->is('master/mahasiswa*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('admin.mahasiswa.index') }}">
        <span
            class="nav-link-icon d-md-none d-lg-inline-block"><!-- Download SVG icon from http://tabler-icons.io/i/home -->
            <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-school"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M22 9l-10 -4l-10 4l10 4l10 -4v6" /><path d="M6 10.6v5.4a6 3 0 0 0 12 0v-5.4" />
            </svg>
        </span>
        <span class="nav-link-title">
            Mahasiswa
        </span>
    </a>
</li>
