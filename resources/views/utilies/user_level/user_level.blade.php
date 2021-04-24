@extends('layouts.app')

@section('title', ' - User Role')

@section('content')
    
<div class="row g-2 mt-0">

    @component('components.content_header')
        @slot('title')
            User Roles
        @endslot
    @endcomponent

    <div class="col-md-12">
        <div class="row g-3">

            <div class="col-md-12">

                <div class="card">
                    <div class="card-body">
                        {{-- <a href="#" class="btn btn-sm btn-primary"><small>Add New Role</small></a> --}}

                        <div class="table-responsive mt-3">
                            <table class="table table-sm table-hover">
                                <caption>
                                    <i>Legends : </i>
                                    <small class="badge badge-dark">Navigation Header</small>
                                    <small class="badge badge-info">Navigation Link</small>
                                </caption>
                                <thead>
                                    <tr>
                                        <th width="15%">User Role</th>
                                        <th>User Menus</th>
                                        <th width="10%" class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($userlevel as $level)
                                        <tr>
                                            <td>{{ $level->description }}</td>
                                            <td>
                                                @foreach (App\Models\UserLevel_Menu::user_menus($level->code) as $m)
                                                    @if($m->menu_code == "-")
                                                        @if($m->code == "SETT")
                                                        <span class="badge badge-dark">{{ $m->menu_desc }}</span>
                                                        <span class="badge badge-dark">{{ $m->menu_desc }}</span>
                                                        @foreach(App\Models\UserLevel_Menu::user_menus($level->code) as $men)
                                                            @if($men->menu_code == "MNTNC")
                                                                <span class="badge badge-info">{{ $men->menu_desc }}</span>
                                                            @endif
                                                        @endforeach
                                                        @elseif($m->code == "PND_APP")
                                                            <span class="badge badge-dark">{{ $m->menu_desc }}</span>
                                                            @foreach(App\Models\UserLevel_Menu::user_menus($level->code) as $men)
                                                                @if($men->menu_code == "PND_APP")
                                                                    <span class="badge badge-info">{{ $men->menu_desc }}</span>
                                                                @endif
                                                            @endforeach
                                                        @elseif($m->code == "INQ")
                                                            <span class="badge badge-dark">{{ $m->menu_desc }}</span>
                                                            @foreach(App\Models\UserLevel_Menu::user_menus($level->code) as $men)
                                                                @if($men->menu_code == "INQ")
                                                                    <span class="badge badge-info">{{ $men->menu_desc }}</span>
                                                                @endif
                                                            @endforeach
                                                        @elseif($m->code == 'APP')
                                                            <span class="badge badge-dark">{{ $m->menu_desc }}</span>
                                                            @foreach(App\Models\UserLevel_Menu::user_menus($level->code) as $men)
                                                                @if($men->menu_code == "APP")
                                                                    <span class="badge badge-info">{{ $men->menu_desc }}</span>
                                                                @endif
                                                            @endforeach    
                                                        @elseif($m->code == 'LOG')  
                                                            <span class="badge badge-dark">{{ $m->menu_desc }}</span>
                                                            @foreach(App\Models\UserLevel_Menu::user_menus($level->code) as $men)
                                                                @if($men->menu_code == "LOG")
                                                                    @if($men->code == "DTR_TR")
                                                                        <span class="badge badge-info">{{ $men->menu_desc }}</span>
                                                                    @elseif($men->code == "INQ")
                                                                        <span class="badge badge-dark">{{ $men->menu_desc }}</span>
                                                                        @foreach(App\Models\UserLevel_Menu::user_menus($level->code) as $test)
                                                                            @if($test->menu_code == "INQ")
                                                                                <span class="badge badge-info">{{ $test->menu_desc }}</span>
                                                                            @endif
                                                                        @endforeach
                                                                    @else
                                                                        <span class="badge badge-info">{{ $men->menu_desc }}</span>
                                                                    @endif
                                                                @endif
                                                            @endforeach 
                                                        @elseif($m->code == 'RPRT')
                                                            <span class="badge badge-dark">{{ $m->menu_desc }}</span>
                                                            @foreach(App\Models\UserLevel_Menu::user_menus($level->code) as $men)
                                                                @if($men->menu_code == "RPRT")
                                                                    <span class="badge badge-info">{{ $men->menu_desc }}</span>
                                                                @endif
                                                            @endforeach 
                                                        @elseif($m->code == 'EMPLY')
                                                            <span class="badge badge-dark">{{ $m->menu_desc }}</span>
                                                            @foreach(App\Models\UserLevel_Menu::user_menus($level->code) as $men)
                                                                @if($men->menu_code == "EMPLY")
                                                                    <span class="badge badge-info">{{ $men->menu_desc }}</span>
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('user_level.show', [$level->code]) }}" class="btn btn-sm btn-primary"><small>Edit</small></a>
                                                {{-- <a href="#" id="remove_role" level="{{ $level->code }}" class="btn btn-sm btn-danger"><small>Remove</small></a> --}}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

</div>


<script>
// $(document).ready(function(){
    // $('a#edit_menus').click(function(){
    //     var level   =   $(this).attr('level');

    //     $.ajax({
    //         methog: 'GET',
    //         url: '/utilities/user_menus/'+level,
    //         success: function(res){
    //             // console.log(res);
    //             $.confirm({
    //                 columnClass: 'col-xs-12 col-sm-4 col-md-4',
    //                 containerFluid: true, // this will add 'container-fluid' instead of 'container'
    //                 animation: 'zoom',
    //                 animateFromElement: false,
    //                 draggable: false,
    //                 type: 'dark',
    //                 icon: 'fa fa-user-cog',
    //                 title: 'Edit Role Menus',
    //                 content: 
    //                     '<form>'+
    //                         res+
    //                     '</form>',
    //                 buttons:{
    //                     formSubmit:{
    //                         text: 'Save Changes',
    //                         btnClass: 'btn-primary',
    //                         action: function(){

    //                         }
    //                     },
    //                     close:{
    //                         btnClass: 'btn-danger',
    //                         action: function(){
                                
    //                         }
    //                     }
    //                 }
    //             });
    //         }
    //     });

    // });
// });
</script>
@endsection