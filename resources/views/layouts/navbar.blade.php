<nav class="main-header navbar navbar-expand navbar-white navbar-light text-sm">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fa fa-bars"></i></a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <li class="nav-item">
            <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" style="font-size: 11px;">
                {{ __('LOGOUT') }} &nbsp;
                <span class="fa fa-sign-out-alt"></span>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </a>
        </li>
        <!-- Messages Dropdown Menu -->

        {{-- <li class="nav-item dropdown"> TESTING
            <a class="nav-link btn btn-sm" data-toggle="dropdown" href="#" aria-expanded="true">
                <i class="fa fa-user"></i> <i class="fa fa-caret-down"></i>
             </a>
             <div class="dropdown-menu dropdown-menu-lg">

                <a href="#" class="dropdown-item">
                    <!-- Message Start -->
                    <div class="media">
                        <img src="{{ asset('images/user.png')}}" alt="User Avatar" class="img-size-50 mr-3 img-circle">
                        <div class="media-body">
                            <h3 class="dropdown-item-title">
                                {{ Auth::user()->fname }} {{ Auth::user()->lname }}
                            </h3>
                            <p class="text-sm text-secondary">ID # : {{ Auth::user()->empno }}</p>
                        </div>
                    </div>
                    <!-- Message End -->
                </a>

                <div class="dropdown-divider"></div>
                <div class="dropdown-divider"></div>
                <div class="dropdown-divider"></div>

                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" style="font-size: 10px; font-weight: bold;">
                    {{ __('LOGOUT') }}
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </a>

            </div>
        </li> TESTING --}}

        {{-- <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
               <i class="fa fa-sign-out"></i> <small class="text-uppercase">Logout</small>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">

                <a href="#" class="dropdown-item">
                    <!-- Message Start -->
                    <div class="media">
                        <img src="{{ asset('images/user.png')}}" alt="User Avatar" class="img-size-50 mr-3 img-circle">
                        <div class="media-body">
                            <h3 class="dropdown-item-title">
                                {{ Auth::user()->fname }} {{ Auth::user()->lname }}
                            </h3>
                            <p class="text-sm text-secondary">ID # : {{ Auth::user()->empno }}</p>
                        </div>
                    </div>
                    <!-- Message End -->
                </a>

                <div class="dropdown-divider"></div>
                <div class="dropdown-divider"></div>
                <div class="dropdown-divider"></div>

                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" style="font-size: 10px; font-weight: bold;">
                    {{ __('LOGOUT') }}
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </a>

            </div>
        </li> --}}
    </ul>
</nav>