@component('mail::message')
# {{ $data['title'] }}

Message: {{ $data['message'] }}<br>
<a href="{{ $data['link'] }}">Click here!</a>
<br>
Thanks,<br>

{{ config('app.name') }}
@endcomponent
