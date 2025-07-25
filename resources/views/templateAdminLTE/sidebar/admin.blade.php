<li class="px-nav-item px-nav-dropdown {{ request()->is('master*') ? 'px-open' : '' }}">
    <a href="javascript:;"><i class="px-nav-icon fa fa-hdd-o"></i>
        <span class="px-nav-label">Peminjaman</span>
    </a>
    <ul class="px-nav-dropdown-menu">
        <li class="px-nav-item {{ request()->is('master/peminjaman*') ? 'active' : '' }}">
            <a href="{{ route('admin.peminjaman.index') }}">
                <span class="px-nav-label">
                    <i class="dropdown-icon px-nav-icon fa-brands fa-stack-overflow"></i>
                    Peminjaman Aset</span>
            </a>
        </li>
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

        <li class="px-nav-item {{ request()->is('master/laporan*') ? 'active' : '' }}">
            <a href="{{ route('admin.laporan') }}">
                <span class="px-nav-label">
                    <i class="dropdown-icon px-nav-icon fa fa-book"></i>
                    Laporan Peminjaman</span>
            </a>
        </li>

    </ul>
</li>

<li class="px-nav-item px-nav-dropdown {{ request()->is('master*') ? 'px-open' : '' }}">
    <a href="javascript:;"><i class="px-nav-icon fa fa-hdd-o"></i>
        <span class="px-nav-label">Master Data</span>
    </a>
    <ul class="px-nav-dropdown-menu">
        <li class="px-nav-item {{ request()->is('master/barang*') ? 'active' : '' }}">
            <a href="{{ route('admin.barang.index') }}">
                <span class="px-nav-label">
                    <i class="dropdown-icon px-nav-icon fa fa-boxes-stacked"></i>
                    Barang</span>
            </a>
        </li>
        {{-- <li class="px-nav-item {{ request()->is('master/kegiatan*') ? 'active' : '' }}">
            <a href="{{ route('admin.kegiatan.index') }}">
                <span class="px-nav-label">
                    <i class="dropdown-icon px-nav-icon ion-ios-pulse-strong"></i>
                    Kegiatan</span>
            </a>
        </li> --}}
        <li class="px-nav-item {{ request()->is('master/gedung*') ? 'active' : '' }}">
            <a href="{{ route('admin.gedung.index') }}">
                <span class="px-nav-label">
                    <i class="dropdown-icon px-nav-icon fa fa-building"></i>
                    Gedung</span>
            </a>
        </li>
        <li class="px-nav-item {{ request()->is('master/gedung*') ? 'active' : '' }}">
            <a href="{{ route('admin.ruangan.index') }}">
                <span class="px-nav-label">
                    <i class="dropdown-icon px-nav-icon fa fa-brands fa-chromecast"></i>
                    Ruangan</span>
            </a>
        </li>



        <li class="px-nav-item {{ request()->is('master/unit*') ? 'active' : '' }}">
            <a href="{{ route('admin.unit.index') }}">
                <span class="px-nav-label">
                    <i class="dropdown-icon px-nav-icon fa fa-briefcase"></i>
                    Unit Kerja</span>
            </a>
        </li>
    </ul>
</li>

<li class="px-nav-item {{ request()->is('master*') ? 'active' : '' }}">
    <a href="{{ route('admin.scan.qrcode') }}"><i class="px-nav-icon fa fa-qrcode"></i>
        <span class="px-nav-label">Scan Qr Code</span>
    </a>
</li>

<li class="px-nav-item {{ request()->is('master/user*') ? 'active' : '' }}">
    <a href="{{ route('admin.user.index') }}">
        <span class="px-nav-label">
            <i class="dropdown-icon px-nav-icon fa fa-users"></i>
            User</span>
    </a>
</li>
