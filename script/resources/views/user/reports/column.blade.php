@extends('layouts.backend.app')

@section('title','Reports')

@section('content')
<div class="row">
  <div class="col-12 col-sm-12 col-lg-12">
    <div class="card">
        <div class="card-header">
            @php
                $status = [
                  0 => [ 'color' => 'badge-danger', 'text' =>  'Re-check' ],
                  1 => [ 'color' => 'badge-success', 'text' =>  'Finished' ],
                  2 => [ 'color' => 'badge-primary', 'text' =>  'Pending' ]
                ][$column->status];
                @endphp
            <h3> {{ $column->name }}  </h3>
            <span class="ml-2 badge {{ $status['color'] }}">{{ $status['text'] }}</span>
        </div>
        <div class="card-body">
            <h5 class="text-center">{{ __('Task Details') }}</h5>
        <div class="row">
          <table class="table mb-4">
            <thead>
              <tr>
                <th scope="col">{{ __('Sl.') }}</th>
                <th scope="col">{{ __('Task Name') }}</th>
                <th scope="col">{{ __('Asssigned to') }}</th>
                <th scope="col">{{ __('Status') }}</th>
                <th scope="col">{{ __('Due Date') }}</th>
                <th scope="col">{{ __('Created At') }}</th>
              </tr>
            </thead>
            @foreach ($column->task ?? [] as $key => $task)
            <tbody>
              <tr>
                <td>{{ $key+1 }}</td>
                <td>{{ $task->name }}</td>
                <td>
                  @if ($task->user)
                  <img data-toggle="tooltip" data-original-title="{{ $task->user->user->name ?? ''}}" alt="image" src="{{ $task->user->user->avatar != null ? asset($task->user->user->avatar) : 'https://ui-avatars.com/api/?background=random&name='.$task->user->user->name }}" class="rounded-circle mr-2" width="35" data-toggle="tooltip" title="" data-original-title="">
                  {{ !empty($task->user) ? $task->user->user->name : '' }}
                  @endif
                </td>
                @php
                $status = [
                  0 => [ 'color' => 'badge-danger', 'text' =>  'Re-check' ] ,
                  1 => [ 'color' => 'badge-success', 'text' =>  'Finished' ] ,
                  2 => [ 'color' => 'badge-primary', 'text' =>  'Pending' ]
                ][$task->status];
                @endphp
                <td><div class="badge {{ $status['color'] }}">{{ $status['text'] }}</div></td>
                <td>{{ Carbon\Carbon::parse($task->due_date)->isoFormat('LL') }}</td>
                <td>{{ Carbon\Carbon::parse($task->created_at)->isoFormat('LL') }}</td>
              </tr>
            </tbody>
            @endforeach
          </table>
          </div>
          <div class="row">
            <div class="col-md-12">
                <h5 class="text-center mb-4">{{ __('All attachments') }}</h5>
                <div class="" id="attachments">
                  <div class="loader" id="loader">
                    <img src="{{ asset('frontend/assets/img/loader.gif') }}" alt="">
                  </div>
                </div>
              </div>
          </div>
      </div>
    </div>
  </div>
</div>

<input type="hidden" id="column_id" value="{{ $column->id }}">
<input type="hidden" id="url" value="{{ url('/') }}">
<input type="hidden" id="project_attachments_url" value="{{ route("user.report.attachments") }}">
@endsection

@push('js')
    <script src="{{ asset('backend/admin/assets/js/report.js') }}"></script>
@endpush


