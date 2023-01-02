@component('mail::message')
# {{ $data['title'] }}

Message: {{ $data['message'] }}<br>
<a href="{{ route('user.project.show.usermail', [ $data['project_id'], $data['task_id'], $data['seen'] ]) }}">Click here!</a>
<br>
Thanks,<br>

{{ config('app.name') }}
@endcomponent
