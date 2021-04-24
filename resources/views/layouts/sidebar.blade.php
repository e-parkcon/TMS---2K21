<aside class="main-sidebar sidebar-dark-primary elevation-2 text-sm">
    <a href="#" class="brand-link text-sm">
        {{-- <img src="{{ asset('images/clock.png') }}" alt="IDSI Logo" class="brand-image img-circle elevation-3" style="opacity: .8"> --}}
        <span class="brand-text font-weight-light">Time Management System</span>
    </a>

    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ asset('images/user.png')}}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="ml-3">
                <a href="#" class="d-block text-sm">{{ Auth::user()->lname }}, {{ Auth::user()->fname }}</a>
                <a href="{{ route('auth.details', [Auth::user()->empno]) }}" class="text-info"><small>View Information</small></a>
            </div>
        </div>
        <nav class="mt-2">
            <ul class="nav nav-sidebar flex-column nav-flat nav-legacy" data-widget="treeview" role="menu" data-accordion="false">
                @if (Auth::user()->entity03 == '001' || Auth::user()->entity03 == 'IT0001' || Auth::user()->entity03 == 'P0001' || Auth::user()->entity03 == 'PT0001' || Auth::user()->entity03 == 'A0001' || Auth::user()->entity03 == 'AT0001' || Auth::user()->entity03 == 'MT0001' || Auth::user()->entity03 == 'N0001')
                    <li class="nav-header ml-2 pb-1"><small>QR Code</small></li>
                    <li class="nav-item">
                        <a href="{{ route('health_declaration.page') }}" class="nav-link {{ Request::segment(1) == 'health_declaration' ? 'active' : null }}"">
                            <i class="nav-icon fa fa-qrcode"></i>
                            <p><small>Health Declaration</small></p>
                        </a>
                    </li>
                @endif
                @foreach(Auth::user()->user_menu() as $m)
                    @if($m->menu_code == "-")
                        @if($m->code == "SETT")
                            <li class="nav-header pt-1 pb-1"><small>{{ $m->menu_desc }}</small></li>
                            <li class="nav-item has-treeview">
                                <a href="#" class="nav-link {{ Request::segment(1) == 'utilities' ? 'active' : null }}">
                                    <i class="nav-icon fa fa-cog"></i> <p><small>{{ $m->menu_desc }}</small><i class="right fa fa-angle-left"></i></p>
                                </a>
                                <ul class="nav nav-treeview">
                                    @foreach(Auth::user()->user_menu() as $men)
                                        @if($men->menu_code == "MNTNC")
                                            <li class="nav-item">
                                                <a href="{{ $men->url }}" class="nav-link">
                                                <i class="fa fa-chevron-circle-right nav-icon"></i>
                                                <p><small>{{ $men->menu_desc }}</small></p>
                                                </a>    
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </li>
                        @elseif($m->code == "PND_APP")
                            <li class="nav-header pt-1 pb-1"><small>{{ $m->menu_desc }}</small></li>
                            @foreach(Auth::user()->user_menu() as $men)
                                @if($men->menu_code == "PND_APP")
                                <li class="nav-item">
                                    <a href="{{ $men->url }}" class="nav-link {{ Request::segment(1) == substr($men->url, 1) ? 'active' : null }}">
                                    <i class="fa fa-chevron-circle-right nav-icon"></i>
                                    <p><small>{{ $men->menu_desc }}</small></p>
                                    </a>
                                </li>
                                @endif
                            @endforeach
                        @elseif($m->code == 'APP')
                            <li class="nav-header pt-2"><small>{{ $m->menu_desc }}</small></li>
                            @foreach(Auth::user()->user_menu() as $men)
                                @if($men->menu_code == "APP")
                                    <li class="nav-item">
                                        <a href="{{ $men->url }}" class="nav-link {{ Request::segment(1) == substr($men->url, 1) ? 'active' : null }}">
                                            @if($men->code == "LV")
                                                <i class="nav-icon fa fa-calendar-alt"></i>
                                            @elseif($men->code == "OT")
                                                <i class="nav-icon fa fa-briefcase"></i>
                                            @endif
                                            <p><small>{{ $men->menu_desc }}</small></p>
                                        </a>
                                    </li>
                                @endif
                            @endforeach    
                        @elseif($m->code == 'LOG')  
                            <li class="nav-header pt-1 pb-1"><small>{{ $m->menu_desc }}</small></li>
                            @foreach(Auth::user()->user_menu() as $men)
                                @if($men->menu_code == "LOG")
                                    @if($men->code == "DTR_TR")
                                        <li class="nav-item">
                                            <a href="#" data-toggle="modal" data-target="#{{ $men->code }}" class="nav-link {{ Request::segment(1) == 'dtr_transac' ? 'active' : null }}">
                                            <i class="fa fa-user-clock nav-icon"></i>
                                            <p><small>{{ $men->menu_desc }}</small></p>
                                            </a>
                                        </li>
                                    @elseif($men->code == "INQ")
                                        <li class="nav-item has-treeview">
                                            <a href="#" class="nav-link {{ Request::segment(1) == 'inquiry' ? 'active' : null }}">
                                                <i class="nav-icon fa fa-ellipsis-v"></i> <p><small>{{ $men->menu_desc }}</small> <i class="right fa fa-angle-left"></i></p>
                                            </a>
                                            <ul class="nav nav-treeview">
                                                @foreach(Auth::user()->user_menu() as $test)
                                                    @if($test->menu_code == "INQ")
                                                        <li class="nav-item">
                                                            <a href="{{ $test->url }}" class="nav-link">
                                                                <i class="far fa-circle nav-icon"></i>
                                                                <p><small>{{ $test->menu_desc }}</small></p>
                                                            </a>    
                                                        </li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        </li>
                                    @else
                                        <li class="nav-item">
                                            <a href="{{ $men->url }}" class="nav-link {{ Request::segment(1) == substr($men->url, 1) ? 'active' : null }}">
                                                <i class="fa fa-calendar-check nav-icon"></i>
                                                <p><small>{{ $men->menu_desc }}</small></p>
                                            </a>
                                        </li>
                                    @endif
                                @endif
                            @endforeach 
                        @elseif($m->code == 'RPRT')
                            <li class="nav-header pt-1 pb-1"><small>{{ $m->menu_desc }}</small></li>
                            @foreach(Auth::user()->user_menu() as $men)
                                @if($men->menu_code == "RPRT")
                                    @if($men->code == "HLTH_DCLTN")
                                        <li class="nav-item">
                                            <a href="#" id="{{ $men->code }}" class="nav-link">
                                            <i class="fa fa-file-medical-alt nav-icon"></i>
                                            <p><small>{{ $men->menu_desc }}</small></p>
                                            </a>
                                        </li>
                                    @else
                                        <li class="nav-item">
                                            <a href="{{ $men->url }}" class="nav-link {{ Request::segment(1) == substr($men->url, 1) ? 'active' : null }}">
                                                    <i class="nav-icon fa fa-file-alt"></i>
                                                <p><small>{{ $men->menu_desc }}</small></p>
                                            </a>
                                        </li>
                                    @endif
                                @endif
                            @endforeach 
                        @elseif($m->code == 'EMPLY')
                            <li class="nav-header pt-1 pb-1"><small>{{ $m->menu_desc }}</small></li>
                            @foreach(Auth::user()->user_menu() as $men)
                                @if($men->menu_code == "EMPLY")
                                    <li class="nav-item">
                                        <a href="{{ $men->url }}" class="nav-link {{ Request::segment(1) == substr($men->url, 1) ? 'active' : null }}">
                                            <i class="nav-icon fa fa-users"></i>
                                            <p><small>{{ $men->menu_desc }}</small></p>
                                        </a>
                                    </li>
                                @endif
                            @endforeach
                        @endif
                    @endif
                @endforeach
            </ul>
        </nav>

    </div> {{-- END OF SIDEBAR --}}

</aside>