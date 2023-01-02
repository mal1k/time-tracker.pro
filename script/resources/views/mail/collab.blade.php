@component('mail::message')

<p># You are invited to Join <b>{{ $data['name'] }}</b> team click on the link to check <b>{{ env('APP_NAME') }}</b>: <a href="{{ url($data['link']) }}">Click here!</a></p>

Thanks,<br>
{{ config('app.name') }}
@endcomponent