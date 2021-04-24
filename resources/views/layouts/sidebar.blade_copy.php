<aside class="main-sidebar sidebar-dark-primary elevation-2">
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
            <div class="info">
                <a href="{{ url('/employee/edit_employee/' . Auth::user()->empno) }}" class="d-block text-sm">{{ Auth::user()->lname }}, {{ Auth::user()->fname }}</a>
            </div>
        </div>
        
        <nav class="mt-2">
            <ul class="nav nav-sidebar flex-column nav-flat nav-legacy font-weight-light" data-widget="treeview" role="menu" data-accordion="false">
                @foreach(Auth::user()->user_menu() as $m)
                    @if($m->menu_code == "-")
                        @if($m->code == "OTH")
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link {{ Request::segment(1) == 'others' ? 'active' : null }}">
                                <i class="nav-icon fa fa-ellipsis-v"></i> <p>{{ $m->menu_desc }}<i class="right fa fa-angle-left"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                @foreach(Auth::user()->user_menu() as $men)
                                    @if($men->menu_code == "OTH")
                                    <li class="nav-item">
                                        <a href="{{ $men->url }}" id="{{ $men->code }}" class="nav-link">
                                            <i class="fa fa-chevron-circle-right nav-icon"></i>
                                            <small>{{ $men->menu_desc }}</small>
                                        </a>
                                    </li>
                                        <!-- <li><a href="{{ $men->url }}">{{ $men->menu_desc }}</a></li> -->
                                    @endif
                                @endforeach
                            </ul>
                        </li>
                        @elseif($m->code == "MNTNC")
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link {{ Request::segment(1) == 'mntnc' ? 'active' : null }}">
                                <i class="nav-icon fa fa-cogs"></i> <p>{{ $m->menu_desc }}<i class="right fa fa-angle-left"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                @foreach(Auth::user()->user_menu() as $men)
                                    @if($men->menu_code == "MNTNC")
                                    @if($men->code == "DTR_TR")
                                        <li class="nav-item">
                                            <a href="#" data-toggle="modal" data-target="#{{ $men->code }}" class="nav-link">
                                            <i class="fa fa-chevron-circle-right nav-icon"></i>
                                            <small>{{ $men->menu_desc }}</small>
                                            </a>
                                        </li>
                                    @elseif($men->code == "HLTH_DCLTN")
                                        <li class="nav-item">
                                            <a href="#" id="{{ $men->code }}" class="nav-link">
                                            <i class="fa fa-chevron-circle-right nav-icon"></i>
                                            <small>{{ $men->menu_desc }}</small>
                                            </a>
                                        </li>
                                    @else
                                        <li class="nav-item">
                                            <a href="{{ $men->url }}" class="nav-link">
                                            <i class="fa fa-chevron-circle-right nav-icon"></i>
                                            <small>{{ $men->menu_desc }}</small>
                                            </a>    
                                        </li>
                                    @endif
                                        <!-- <li><a href="{{ $men->url }}">{{ $men->menu_desc }}</a></li> -->
                                    @endif
                                @endforeach
                            </ul>
                        </li>
                        @elseif($m->code == "PND_APP")
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link {{ Request::segment(1) == 'pending' ? 'active' : null }}">
                                <i class="nav-icon fa fa-th-list"></i> <p>{{ $m->menu_desc }}<i class="right fa fa-angle-left"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                @foreach(Auth::user()->user_menu() as $men)
                                    @if($men->menu_code == "PND_APP")
                                    <li class="nav-item">
                                        <a href="{{ $men->url }}" class="nav-link">
                                        <i class="fa fa-chevron-circle-right nav-icon"></i>
                                        <small>{{ $men->menu_desc }}</small>
                                        </a>
                                    </li>
                                        <!-- <li><a href="{{ $men->url }}">{{ $men->menu_desc }}</a></li> -->
                                    @endif
                                @endforeach
                            </ul>
                        </li>
                        @elseif($m->code == "INQ")
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link {{ Request::segment(1) == 'inquiry' ? 'active' : null }}">
                                <i class="nav-icon fa fa-th-list"></i> <p>{{ $m->menu_desc }}<i class="right fa fa-angle-left"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                @foreach(Auth::user()->user_menu() as $men)
                                    @if($men->menu_code == "INQ")
                                    <li class="nav-item">
                                        <a href="{{ $men->url }}" class="nav-link">
                                        <i class="fa fa-chevron-circle-right nav-icon"></i>
                                        <small>{{ $men->menu_desc }}</small>
                                        </a>
                                    </li>
                                        <!-- <li><a href="{{ $men->url }}">{{ $men->menu_desc }}</a></li> -->
                                    @endif
                                @endforeach
                            </ul>
                        </li>
                        @else
                        <li class="nav-item">
                            <a href="{{ $m->url }}" class="nav-link {{ Request::segment(1) == substr($m->url, 1) ? 'active' : null }}">
                                @if($m->code == "LV")
                                    <i class="nav-icon fa fa-calendar"></i>
                                @elseif($m->code == "OT")
                                    <i class="nav-icon fa fa-briefcase"></i>
                                @else
                                    <i></i>
                                @endif
                            <p>{{ $m->menu_desc }}</p>
                            </a>
                        </li>
                        @endif
                    @endif
                @endforeach
            </ul>
        </nav>

    </div> {{-- END OF SIDEBAR --}}

</aside>