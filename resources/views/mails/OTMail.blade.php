@component('mail::message')
# Hi Sir/Ma'am,

I would like to ask for your permission and approval of my overtime application.

<br>

@component('mail::table')
| Date OT       | Overtime Period         | Client's Name  | Work Done  | Hrs   |
|:-------------:|:-----------------------:| --------------:| ----------:|:-----:|
@foreach ($overtime as $ot)
| {{ date('d-M-Y', strtotime($ot->dateot)) }} | {{ date('d-M-Y H:i', strtotime($ot->timestart)) }} - {{ date('d-M-Y H:i', strtotime($ot->timefinish)) }} | {{ $ot->clientname }} | {{ $ot->workdone }} | {{ $ot->hours }} |
@endforeach
@endcomponent

<br>
@component('mail::button', ['url' => $url])
Visit website for approval
@endcomponent

Thanks,<br>
{{ $name }}

@slot('footer')
@component('mail::footer')
© {{ date('Y') }} {{ config('app.name') }}. @lang('All rights reserved.')
@endcomponent
@endslot

@endcomponent
