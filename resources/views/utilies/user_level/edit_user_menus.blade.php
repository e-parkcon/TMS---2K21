@extends('layouts.app')

@section('title', ' - User Menus')

@section('content')
    
<div class="row g-2 mt-0">

    @component('components.content_header')
        @slot('title')
            User Roles
        @endslot
    @endcomponent
    
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">

                <div class="row g-3">
                    <div class="col-md-12">
                        <a href="{{ route('user_level.index') }}" class="btn btn-sm btn-link"><< User Roles</a>
                    </div>

                    <div class="col-md-3">
                        <form action="{{ route('user_level.update', [$level]) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <div class="row g-2">
                                <div class="col-md-12">
                                    <label class="form-label text-uppercase text-sm">Select Menus</label>
                                    <select name="menu" id="menu" class="selectpicker form-control form-control-sm" data-size="15">
                                        <option selected hidden disabled>Choose Menu</option>
                                        @foreach ($web_menu as $menus)
                                            <option value="{{ $menus->menus_code }}" code="{{ $menus->grp_code }}">{{ $menus->menus_desc }}</option>
                                        @endforeach
                                    </select>

                                    <input type="hidden" name="code" id="code" class="form-control" readonly>
                                </div>
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-sm btn-primary btn-block"><small>Add menu</small></button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="col-md-9">
                        <label class="form-label text-uppercase text-primary">Menus</label>
                        <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
                            <table class="table table-sm" width="100%">
                                @foreach ($user_menus as $m)
                                    @if($m->menu_code == "-")
                                        @if($m->code == "SETT")
                                        <tr>
                                            <td colspan="3">{{ $m->menu_desc }}</td>
                                            <td width="5%" class="p-2">
                                                <a href="#" class="btn btn-sm btn-danger m-0" id="delete_menu"
                                                code="{{ $m->code }}" menu_code="{{ $m->menu_code }}"><span class="fa fa-times"></span></a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td colspan="2">{{ $m->menu_desc }}</td>
                                            <td width="5%" class="p-2">
                                                <a href="#" class="btn btn-sm btn-danger m-0" id="delete_menu"
                                                code="{{ $m->code }}" menu_code="{{ $m->menu_code }}"><span class="fa fa-times"></span></a>
                                            </td>
                                        </tr>
                                        @foreach($user_menus as $men)
                                            @if($men->menu_code == "MNTNC")
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td>{{ $men->menu_desc }}</td>
                                                    <td width="5%" class="p-2">
                                                        <a href="#" class="btn btn-sm btn-danger m-0" id="delete_menu"
                                                        code="{{ $men->code }}" menu_code="{{ $men->menu_code }}"><span class="fa fa-times"></span></a>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                        @elseif($m->code == "PND_APP")

                                            <tr>
                                                <td colspan="3">{{ $m->menu_desc }}</td>
                                                <td width="5%" class="p-2">
                                                    <a href="#" class="btn btn-sm btn-danger m-0" id="delete_menu"
                                                    code="{{ $m->code }}" menu_code="{{ $m->menu_code }}"><span class="fa fa-times"></span></a>
                                                </td>
                                            </tr>
                                            @foreach($user_menus as $men)
                                                @if($men->menu_code == "PND_APP")

                                                    <tr>
                                                        <td></td>
                                                        <td colspan="2">{{ $men->menu_desc }}</td>
                                                        <td width="5%" class="p-2">
                                                            <a href="#" class="btn btn-sm btn-danger m-0" id="delete_menu"
                                                            code="{{ $men->code }}" menu_code="{{ $men->menu_code }}"><span class="fa fa-times"></span></a>
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        {{-- @elseif($m->code == "INQ")
                                            <span class="badge badge-dark">{{ $m->menu_desc }}</span>
                                            @foreach($user_menus as $men)
                                                @if($men->menu_code == "INQ")
                                                    <span class="badge badge-info">{{ $men->menu_desc }}</span>
                                                @endif
                                            @endforeach --}}
                                        @elseif($m->code == 'APP')
                                            <tr>
                                                <td colspan="3">{{ $m->menu_desc }}</td>
                                                <td width="5%" class="p-2">
                                                    <a href="#" class="btn btn-sm btn-danger m-0" id="delete_menu"
                                                    code="{{ $m->code }}" menu_code="{{ $m->menu_code }}"><span class="fa fa-times"></span></a>
                                                </td>
                                            </tr>

                                            @foreach($user_menus as $men)
                                                @if($men->menu_code == "APP")

                                                    <tr>
                                                        <td></td>
                                                        <td colspan="2">{{ $men->menu_desc }}</td>
                                                        <td width="5%" class="p-2">
                                                            <a href="#" class="btn btn-sm btn-danger m-0" id="delete_menu"
                                                            code="{{ $men->code }}" menu_code="{{ $men->menu_code }}"><span class="fa fa-times"></span></a>
                                                        </td>   
                                                    </tr>
                                                @endif
                                            @endforeach    
                                        @elseif($m->code == 'LOG')  

                                            <tr>
                                                <td colspan="3">{{ $m->menu_desc }}</td>
                                                <td width="5%" class="p-2">
                                                    <a href="#" class="btn btn-sm btn-danger m-0" id="delete_menu"
                                                    code="{{ $m->code }}" menu_code="{{ $m->menu_code }}"><span class="fa fa-times"></span></a>
                                                </td>
                                            </tr>
                                            @foreach($user_menus as $men)
                                                @if($men->menu_code == "LOG")
                                                    @if($men->code == "DTR_TR")
    
                                                        <tr>
                                                            <td></td>
                                                            <td colspan="2">{{ $men->menu_desc }}</td>
                                                            <td width="5%" class="p-2">
                                                                <a href="#" class="btn btn-sm btn-danger m-0" id="delete_menu"
                                                                code="{{ $men->code }}" menu_code="{{ $men->menu_code }}"><span class="fa fa-times"></span></a>
                                                            </td>   
                                                        </tr>
                                                    @elseif($men->code == "INQ")
                                                        <tr>
                                                            <td></td>
                                                            <td colspan="2">{{ $men->menu_desc }}</td>
                                                            <td width="5%" class="p-2">
                                                                <a href="#" class="btn btn-sm btn-danger m-0" id="delete_menu"
                                                                code="{{ $men->code }}" menu_code="{{ $men->menu_code }}"><span class="fa fa-times"></span></a>
                                                            </td>   
                                                        </tr>
                                                        @foreach($user_menus as $test)
                                                            @if($test->menu_code == "INQ")
                                                                <tr>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td>{{ $test->menu_desc }}</td>
                                                                    <td width="5%" class="p-2">
                                                                        <a href="#" class="btn btn-sm btn-danger m-0" id="delete_menu"
                                                                        code="{{ $test->code }}" menu_code="{{ $test->menu_code }}"><span class="fa fa-times"></span></a>
                                                                    </td>   
                                                                </tr>
                                                            @endif
                                                        @endforeach
                                                    @else
    
                                                        <tr>
                                                            <td></td>
                                                            <td colspan="2">{{ $men->menu_desc }}</td>
                                                            <td width="5%" class="p-2">
                                                                <a href="#" class="btn btn-sm btn-danger m-0" id="delete_menu"
                                                                code="{{ $men->code }}" menu_code="{{ $men->menu_code }}"><span class="fa fa-times"></span></a>
                                                            </td>   
                                                        </tr>
                                                    @endif
                                                @endif
                                            @endforeach 
                                        @elseif($m->code == 'RPRT')

                                            <tr>
                                                <td colspan="3">{{ $m->menu_desc }}</td>
                                                <td width="5%" class="p-2">
                                                    <a href="#" class="btn btn-sm btn-danger m-0" id="delete_menu"
                                                    code="{{ $m->code }}" menu_code="{{ $m->menu_code }}"><span class="fa fa-times"></span></a>
                                                </td>
                                            </tr>
                                            @foreach($user_menus as $men)
                                                @if($men->menu_code == "RPRT")

                                                    <tr>
                                                        <td></td>
                                                        <td colspan="2">{{ $men->menu_desc }}</td>
                                                        <td width="5%" class="p-2">
                                                            <a href="#" class="btn btn-sm btn-danger m-0" id="delete_menu"
                                                            code="{{ $men->code }}" menu_code="{{ $men->menu_code }}"><span class="fa fa-times"></span></a>
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach 
                                        @elseif($m->code == 'EMPLY')

                                            <tr>
                                                <td colspan="3">{{ $m->menu_desc }}</td>
                                                <td width="5%" class="p-2">
                                                    <a href="#" class="btn btn-sm btn-danger m-0" id="delete_menu"
                                                        code="{{ $m->code }}" menu_code="{{ $m->menu_code }}"><span class="fa fa-times"></span></a>
                                                </td>
                                            </tr>
                                            @foreach($user_menus as $men)
                                                @if($men->menu_code == "EMPLY")

                                                    <tr>
                                                        <td></td>
                                                        <td colspan="2">{{ $men->menu_desc }}</td>
                                                        <td width="5%" class="p-2">
                                                            <a href="#" class="btn btn-sm btn-danger m-0" id="delete_menu" 
                                                                code="{{ $men->code }}" menu_code="{{ $men->menu_code }}"><span class="fa fa-times"></span></a>
                                                        </td>   
                                                    </tr>
                                                @endif
                                            @endforeach
                                        @endif
                                    @endif
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>

</div>

<script>
$(document).ready(function(){
    $('#menu').change(function(event){
        var code = $('#menu').find(':selected').attr('code');
        $('#code').val(code);
    })
});

//DELETE TASK
$('a#delete_menu').click(function(){

    var code    =   $(this).attr('code')
    var menu_code    =   $(this).attr('menu_code')

    console.log(code, menu_code);

    $.confirm({
        columnClass: 'col-xs-12 col-sm-6 col-md-3',
        containerFluid: true, // this will add 'container-fluid' instead of 'container'
        animation: 'top',
        animateFromElement: false,
        type: 'red',
        icon: 'fa fa-trash',
        title: 'Delete Menu',
        buttons: {
            confirm:{
                btnClass: 'btn-primary',
                action: function(){
                    // window.location.href = '/others/delete/menu/'+code+'/'+menu_code+'/'+user_lvl;

                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: "DELETE",
                        data:{
                            code    :   code,
                            menu_code   :   menu_code
                        },
                        url: '/utilities/user_level/'+'{{ $level }}',
                        success: function(){

                            toastr.success('Menu deleted');

                            location.reload();

                        },
                        error: function(xhr, status, error){
                            var errorMessage = xhr.status + ': ' + xhr.statusText
                            console.log(status, xhr, error);
                            $.alert('Error - ' + errorMessage);
                        }
                    });

                }
            },
            cancel:{
                btnClass: 'btn-danger',
                action: function(){

                }
            }
        }
    });

});
</script>
@endsection