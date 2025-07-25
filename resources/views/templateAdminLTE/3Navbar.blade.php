<nav class="navbar px-navbar">
    <div class="navbar-header ">
        <a class="navbar-brand" style="padding:5px 5px 0px 0px" href="{{ route('home.index') }}">
            <img src="{{ asset('img/logo.png') }}" alt="Logo" height="40" />
        </a>
    </div>

    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#px-demo-navbar-collapse"
        aria-expanded="false"><i class="navbar-toggle-icon"></i></button>

    <div class="collapse navbar-collapse" id="px-demo-navbar-collapse">
        <ul class="nav navbar-nav navbar-right">


            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                    aria-expanded="false">
                    @if (auth()->user()->foto)
                        <img src="{{ asset('storage/users/' . auth()->user()->foto) }}" alt=""
                            class="px-navbar-image">
                    @else
                        
                        <img src="{{ asset('img/Tekno.png') }}" alt="" class="px-navbar-image">
                    @endif
                    <span class="hidden-md">{{ auth()->user()->name }}</span>
                </a>
                <ul class="dropdown-menu">
                    <li>
                        <a href="{{ route('profil') }}"><i class="dropdown-icon fa fa-person"></i>&nbsp;&nbsp;Profil</a>
                    </li>
                    <li>
                        <a href="{{ route('perbarui_password') }}"><i
                                class="dropdown-icon fa fa-key"></i>&nbsp;&nbsp;Change Password</a>
                    </li>
                    <li class="divider"></li>
                    <li>
                        <a href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i
                                class="dropdown-icon fa fa-power-off"></i>&nbsp;&nbsp;Log
                            Out</a>
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>

                    </li>


                </ul>
            </li>

        </ul>
    </div>
</nav>
