@extends('layouts.backend.app')

@section('title','Reports')

@push('beforestyle.css')
<link rel="stylesheet" href="{{ asset('backend/admin/assets/css/fullcalendar.min.css') }}">
@endpush

@push('css')
<link rel="stylesheet" href="{{ asset('backend/admin/assets/css/daterangepicker.css') }}">
@endpush

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <div class="row">
          <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                  <h5><span class="mr-2">{{ $project->name }}</span>
                    @php
                    $status = [
                      0 => [ 'color' => 'badge-danger', 'text' =>  'Error' ] ,
                      1 => [ 'color' => 'badge-success', 'text' =>  'Finished' ] ,
                      2 => [ 'color' => 'badge-primary', 'text' =>  'Pending' ]
                    ][$project->status];
                    @endphp
                    <div class="badge {{ $status['color'] }}">{{ $status['text'] }}</div>
                  </h5>
                </div>
                <div class="card-body">
                  <p>{{ $project->description }}</p>
                </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
              <div class="card-icon bg-primary">
                <i class="far fa-user"></i>
              </div>
              <div class="card-wrap">
                <div class="card-header">
                  <h4>{{ __('Total Members') }}</h4>
                </div>
                <div class="card-body" id="totalMember">
                  <div class="loader project-stats">
                    <img src="{{ asset('frontend/assets/img/loader.gif') }}" alt="">
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
              <div class="card-icon bg-warning">
                <i class="far fa-file"></i>
              </div>
              <div class="card-wrap">
                <div class="card-header">
                  <h4>{{ __('Total Columns') }}</h4>
                </div>
                <div class="card-body" id="totalColumn">
                  <div class="loader project-stats">
                    <img src="{{ asset('frontend/assets/img/loader.gif') }}" alt="">
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
              <div class="card-icon bg-primary">
                <i class="fas fa-circle"></i>
              </div>
              <div class="card-wrap">
                <div class="card-header">
                  <h4>{{ __('Total Tasks') }}</h4>
                </div>
                <div class="card-body" id="total_task_count">
                  <div class="loader project-stats">
                    <img src="{{ asset('frontend/assets/img/loader.gif') }}" alt="">
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
              <div class="card-icon bg-warning">
                <i class="fas fa-circle"></i>
              </div>
              <div class="card-wrap">
                <div class="card-header">
                  <h4>{{ __('Pending Tasks') }}</h4>
                </div>
                <div class="card-body" id="pending_task_count">
                  <div class="loader project-stats">
                    <img src="{{ asset('frontend/assets/img/loader.gif') }}" alt="">
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
              <div class="card-icon bg-success">
                <i class="fas fa-circle"></i>
              </div>
              <div class="card-wrap">
                <div class="card-header">
                  <h4>{{ __('Completed Tasks') }}</h4>
                </div>
                <div class="card-body" id="completed_task_count">
                  <div class="loader project-stats">
                    <img src="{{ asset('frontend/assets/img/loader.gif') }}" alt="">
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
              <div class="card-icon bg-success">
                <i class="fas fa-circle"></i>
              </div>
              <div class="card-wrap">
                <div class="card-header">
                  <h4>{{ __('Completed Columns') }}</h4>
                </div>
                <div class="card-body" id="completed_column_count">
                  <div class="loader project-stats">
                    <img src="{{ asset('frontend/assets/img/loader.gif') }}" alt="">
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
              <div class="card-icon bg-success">
                <i class="fas fa-clock"></i>
              </div>
              <div class="card-wrap">
                <div class="card-header">
                  <h4>{{ __('Total Hours') }}</h4>
                </div>
                <div class="card-body" id="total_hours">
                  <div class="loader project-stats">
                    <img src="{{ asset('frontend/assets/img/loader.gif') }}" alt="">
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
              <div class="card-icon bg-success">
                <i class="fas fa-dollar-sign"></i>
              </div>
              <div class="card-wrap">
                <div class="card-header">
                  <h4>{{ __('Total Spends') }}</h4>
                </div>
                <div class="card-body" id="total_spent">
                  <div class="loader project-stats">
                    <img src="{{ asset('frontend/assets/img/loader.gif') }}" alt="">
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
              <div class="card-icon bg-success">
                <i class="fas fa-spinner"></i>
              </div>
              <div class="card-wrap">
                <div class="card-header">
                  <h4>{{ __('Completed (Percentage)') }}</h4>
                </div>
                <div class="card-body">
                  <div id="percentage"><div class="loader project-stats">
                    <img src="{{ asset('frontend/assets/img/loader.gif') }}" alt="">
                  </div></div>
                  <div class="progress custom-progress" id="progress_project" data-height="6" data-toggle="tooltip" title="" data-original-title="">
                    <div class="progress-bar bg-success" id="progress_width" data-width=""></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
              <div class="card-icon bg-danger">
                <i class="fas fa-hdd"></i>
              </div>
              <div class="card-wrap">
                <div class="card-header">
                  <h4>{{ __('Storage used') }}</h4>
                </div>
                <div class="card-body">
                  <div id="percentage">
                    <div class="loader project-stats" id="storage">
                        <img src="{{ asset('frontend/assets/img/loader.gif') }}" alt="">
                    </div>
                  </div>
                </div>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
              <a href="{{ route('user.report.attachment.list', $project->id) }}">
              <div class="card card-statistic-1">
                <div class="card-icon bg-danger">
                  <i class="fas fa-image"></i>
                </div>
                <div class="card-wrap">
                  <div class="card-header">
                    <h4>{{ __('Total Files') }}</h4>
                  </div>
                  <div class="card-body">
                    <div id="percentage"><div class="loader project-stats" id="attachment">
                      <img src="{{ asset('frontend/assets/img/loader.gif') }}" alt="">
                    </div></div>
                  </div>
                  </div>
                </div>
                </a>
              </div>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-12 col-md-12 col-12 col-sm-12">
            <div class="card">
              <div class="card-header">
                <h4>{{ __('Columns') }}</h4>
              </div>
              <div class="card-body">
                <ul class="list-unstyled list-unstyled-border row" id="columns">
                  <div class="loader project-stats">
                    <img src="{{ asset('frontend/assets/img/loader.gif') }}" alt="">
                  </div>
                </ul>
              </div>
            </div>
          </div>
          <div class="col-lg-8 col-md-12 col-12 col-sm-12">
            <div class="card">
              <div class="card-header">
                <h4>{{ __('Hours') }}</h4>
              </div>
              <div class="card-body">
                <canvas id="myChart2"></canvas>
              </div>
            </div>
          </div>
          <div class="col-lg-4 col-md-12 col-12 col-sm-12">
            <div class="card">
              <div class="card-header">
                <h4>{{ __('Members') }}</h4>
              </div>
              <div class="card-body">
                <ul class="list-unstyled list-unstyled-border" id="members">
                  <div class="loader project-stats">
                    <img src="{{ asset('frontend/assets/img/loader.gif') }}" alt="">
                  </div>
                </ul>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-12 col-md-12 col-12 col-sm-12">
            <div class="card">
            <div class="card-header">
              <h4><h4>{{ __('Tasks') }}</h4></h4>
            </div>
            <div class="card-body">
              <div id="myEvent"></div>
            </div>
            </div>
         </div>
        </div>
      </div>
    </div>
  </div>
</div>

<input type="hidden" id="project_id" value="{{ $project->id }}">
<input type="hidden" id="statslink" value="{{ route('user.report.stats') }}">
<input type="hidden" id="url" value="{{ url('/') }}">
<input type="hidden" id="renderTaskRoute" value="{{ route('user.report.rendertask') }}">

@endsection
@push('js')
<script src="{{ asset('backend/admin/assets/js/chart.js') }}"></script>
<script src="{{ asset('backend/admin/assets/js/fullcalendar.min.js') }}"></script>
<script src="{{ asset('backend/admin/assets/js/project_report.js') }}"></script>
@endpush
