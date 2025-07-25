@include('templateAdminLTE.1Header')
@include('templateAdminLTE.2Aside')
@include('templateAdminLTE.3Navbar')

<div class="px-content">
    <ol class="breadcrumb page-breadcrumb">
        <li><a href="{{ route('home.index') }}">Home</a></li>
        <li class="active">@yield('sub-breadcrumb')</li>
    </ol>
    @yield('content')
</div>
@yield('modal')
@include('templateAdminLTE.4Footer')
@include('templateAdminLTE.5Javascript')
