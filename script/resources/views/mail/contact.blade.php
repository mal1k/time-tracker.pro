@component('mail::message')
# NAME: {{ $data['name'] }},
# EMAIL: {{ $data['email'] }},

{{ $data['message'] }}

--------


Thanks,<br>
{{ config('app.name') }}
@endcomponent
