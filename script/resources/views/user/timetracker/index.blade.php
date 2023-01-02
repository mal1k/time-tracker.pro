@extends('layouts.backend.app')

@section('title','Time Tracker')

@section('head')
@include('layouts.backend.partials.headersection',['title'=>'Time Tracker'])
@endsection

@push('beforestyle.css')
    <link rel="stylesheet" href="{{ asset('backend/admin/assets/css/selectric.css') }}">
@endpush

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <form action="">
                            <div class="single-time-card">
                                <div class="row align-items-center">
                                    <div class="col-lg-4">
                                        <div class="select-project">
                                            <div class="form-group">
                                                <label for="filter_status">{{ __('Select Project') }}</label>
                                                <select name="project" class="form-control selectric" id="projects">
                                                    <option value="">{{ __('Select Project') }}</option>
                                                    @foreach ($projects as $project)
                                                        <option data-gps={{ $project->gps }} data-screenshot={{ $project->screenshot }} value="{{ $project->id }}">{{ $project->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="select-project">
                                            <div class="form-group">
                                                <label for="visibility">{{ __('Tasks') }}</label>
                                                <select name="task" class="form-control selectric" id="tasks">
                                                    <option disabled selected value="">{{ __('Select Task') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2">
                                        <div class="time-count">
                                            <strong id="timer" class="timer px-2">00:00:00</strong>
                                        </div>
                                    </div>
                                    <div class="col-lg-2">
                                        <div class="start-btn">
                                            <button class="btn btn-primary" type="button" id="startButton" data-task="start">
                                                <span class="spinner-border-sm" role="status" aria-hidden="true"></span>
                                                <span class="text">{{ __('START') }}</span>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="row d-none" id="share_screen_enable">
                                            <div class="col-lg-4">
                                                <div class="video-show-area">
                                                    <video id="gum-local" autoplay playsinline muted class="video"></video>
                                                </div>
                                            </div>
                                            <div class="col-lg-8">
                                                <div class="row screenshot-img-show-area" id="screenshot_area"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <canvas id="canvas" class="none"></canvas>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="this-week-info">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="week-header">
                                        <h4>{{ __('This Week Reports') }}</h4>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="project-des showRecentProjects">
                                        <table class="table table-striped">
                                            <thead>
                                              <tr>
                                                <th scope="col">{{ __('Sl.') }}</th>
                                                <th scope="col">{{ __('Project') }}</th>
                                                <th scope="col">{{ __('Total Time Track') }}</th>
                                                <th scope="col">{{ __('Date') }}</th>
                                                <th scope="col">{{ __('Screenshots') }}</th>
                                                <th scope="col">{{ __('Analysics') }}</th>
                                              </tr>
                                            </thead>
                                             <tbody>
                                                 @foreach ($data ?? [] as $item)
                                                <tr>
                                                <th scope="row">{{ $loop->iteration }}</th>
                                                <td><a href="{{ route('user.project.show',$item->project_id) }}">{{ $item->project->name }}</a></td>
                                                <td>{{ date("H:i:s", $item->time ) }}</td>
                                                <td>
                                                    {{ Carbon\Carbon::parse($item->date)->isoFormat('LL') }}    
                                                </td>
                                                <td>
                                                    <a href="{{ route('user.report.user',
                                                    ['id' => Auth::id(),
                                                    'project_id' => $item->project_id]) }}" class="btn btn-primary">Manage ScreenShots</a>
                                                </td>
                                                <td>
                                                    
                                                    @if (in_array($item->project_id, $project_ids))
                                                        @php $is_admin = 1 @endphp
                                                    @else 
                                                        @php $is_admin = 0 @endphp
                                                    @endif
                                                    
                                                    @if ($is_admin == 1)
                                                    <a href="{{ route('user.report.show',
                                                    ['report' => $item->project_id]) }}" 
                                                    class="btn btn-danger">Analysics</a>
                                                    @endif
                                                </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<input type="hidden" id="action_url" value="{{ route('user.time.trackuser') }}">
<input type="hidden" id="project_id" value="">
<input type="hidden" id="task_id" value="">
<input type="hidden" id="gps_store" value="{{ route('user.gps.store') }}">
<input type="hidden" id="time_start" value="{{ route('user.time.start') }}">
<input type="hidden" id="time_stop" value="{{ route('user.time.stop') }}">
<input type="hidden" id="screenshot" value="">
<input type="hidden" id="gps" value="">
<input type="hidden" id="getTasksRoute" value="{{ route('user.get.tasks') }}">
<input type="hidden" id="screenshoturl" value="{{ route('user.upload.screenshot') }}">
<input type="hidden" id="alertfile" value="{{ asset('backend/admin/assets/beep.mp3') }}">
@endsection

@push('js')
<script src="{{ asset('backend/admin/assets/js/jquery.selectric.min.js') }}"></script>
<script src="https://webrtc.github.io/adapter/adapter-latest.js"></script>
<script src="{{ asset('backend/admin/assets/js/gps.js') }}"></script>
<script src="{{ asset('backend/admin/assets/js/timetracker.js?v=1.1.0') }}"></script>
@endpush







