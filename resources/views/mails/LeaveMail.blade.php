@component('mail::message')
# Hi Sir/Ma'am,

I would like to ask for your permission and approval for my leave application.

Leave Period : {{ date('M. d, Y', strtotime($leave['fromdate'])) }} to {{ date('M. d, Y', strtotime($leave['todate'])) }}
<br>
Leave Type &nbsp;&nbsp;&nbsp;: {{ $leave['leavecode'] }}
<br>
Reason &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : {{ $leave['reason'] }}
<br> 
<br>
<br>

@component('mail::button', ['url' => $url])
<small>Visit website for approval</small>
@endcomponent

<br>

Thanks,<br>
{{ $name }}

@slot('footer')
@component('mail::footer')
© {{ date('Y') }} {{ config('app.name') }}. @lang('All rights reserved.')
@endcomponent
@endslot

@endcomponent
