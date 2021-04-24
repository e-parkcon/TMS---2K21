<table class="table table-sm table-borderless mt-1" style="width: 100%">
    <tr>
        <td width="25%" class="text-uppercase font-weight-bold">Group Name <span class="float-right">:</span></td>
        <td colspan="2" class="text-uppercase font-weight-bold">{{ $user_app_group->app_desc }}</td>
    </tr>
    @foreach ($app_officers as $key => $officer)
        <tr>
            <td class="text-uppercase">Officer # {{ $key+1 }}<span class="float-right">:</span></td>
            <td class="text-uppercase">{{ $officer['name'] }}<br>email : {{ $officer['email'] }}</td>
        </tr>
    @endforeach
</table>