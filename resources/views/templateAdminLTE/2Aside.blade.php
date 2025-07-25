@if (Auth::user()->level == 'admin' || Auth::user()->level == 'baak')
    <nav class="px-nav px-nav-left">
        <button type="button" class="px-nav-toggle" data-toggle="px-nav">
            <span class="px-nav-toggle-arrow"></span>
            <span class="navbar-toggle-icon"></span>
            <span class="px-nav-toggle-label font-size-11">HIDE MENU</span>
        </button>

        <ul class="px-nav-content">
            <li class="px-nav-item {{ request()->is('dashboard') ? 'active' : '' }}">
                <a href="{{ route('home.index') }}"><i class="px-nav-icon fa fa-home"></i><span
                        class="px-nav-label">Dashboard</span></a>
            </li>
            @if (Auth::user()->level == 'admin')
                @include('templateAdminLTE.sidebar.admin')
            @endif

            @if (Auth::user()->level == 'baak')
                <li class="px-nav-item px-nav-dropdown {{ request()->is('master*') ? 'px-open' : '' }}">
                    <a href="javascript:;"><i class="px-nav-icon fa fa-hdd-o"></i>
                        <span class="px-nav-label">Peminjaman</span>
                    </a>
                    <ul class="px-nav-dropdown-menu">
                        <li class="px-nav-item {{ request()->is('master/booking*') ? 'active' : '' }}">
                            <a href="{{ route('admin.booking.index') }}">
                                <span class="px-nav-label">
                                    <i class="dropdown-icon px-nav-icon fa-brands fa-stack-overflow"></i>
                                    Peminjaman Ruangan</span>
                            </a>
                        </li>
                        <li class="px-nav-item {{ request()->is('master/jadwal*') ? 'active' : '' }}">
                            <a href="{{ route('admin.jadwal') }}">
                                <span class="px-nav-label">
                                    <i class="dropdown-icon px-nav-icon fa fa-calendar"></i>
                                    Jadwal Ruangan</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="px-nav-item{{ request()->is('master*') ? 'active' : '' }}">
                    <a href="{{ route('admin.scan.qrcode') }}"><i class="px-nav-icon fa fa-qrcode"></i>
                        <span class="px-nav-label">Scan Qr Code</span>
                    </a>
                </li>
            @endif
        </ul>
    </nav>
@endif
