@component('mail::message')
<p># You are invited to Join <b>{{ $data['name'] }}</b> team click on the link to register on <b>{{ env('APP_NAME') }}</b>: <a href="{{ $data['link'] }}">Click here to register!</a></p>
Thanks,<br>
{{ config('app.name') }}
@endcomponent
