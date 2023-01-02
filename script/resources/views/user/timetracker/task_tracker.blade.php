@extends('layouts.backend.app')

@section('title','Time Tracker')

@section('head')
@include('layouts.backend.partials.headersection',['title'=>'Time Tracker'])
@endsection

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
                                    <div class="col-lg-6">
                                        <div class="project-title-label">
                                            <span>{{ __('Name') }}</span>
                                        </div>
                                        <div class="project-title-box">
                                            <h5>{{ $task->name }}</h5>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
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
                                                    <video id="gum-local" autoplay playsinline muted class="video" height="30%"  ></video>
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
           </div>
       </div>
   </div>
</div>

<input type="hidden" id="action_url" value="{{ route('user.time.trackuser') }}">
<input type="hidden" id="project_id" value="{{ $project->id }}">
<input type="hidden" id="task_id" value="{{ $task->id }}">
<input type="hidden" id="gps_store" value="{{ route('user.gps.store') }}">
<input type="hidden" id="time_start" value="{{ route('user.time.start') }}">
<input type="hidden" id="time_stop" value="{{ route('user.time.stop') }}">
<input type="hidden" id="screenshot" value="{{ $project->screenshot }}">
<input type="hidden" id="gps" value="{{ $project->gps }}">
<input type="hidden" id="screenshoturl" value="{{ route('user.upload.screenshot') }}">
<input type="hidden" id="alertfile" value="{{ asset('backend/admin/assets/beep.mp3') }}">
@endsection

@push('js')
<script src="https://webrtc.github.io/adapter/adapter-latest.js"></script>
<script src="{{ asset('backend/admin/assets/js/gps.js') }}"></script>
<script src="{{ asset('backend/admin/assets/js/timetracker.js') }}"></script>
@endpush




