@extends('layouts.backend.app')

@section('title','Dashboard')

@push('css')
    <link rel="stylesheet" href="{{ asset('backend/admin/assets/css/daterangepicker.css') }}">
@endpush

@section('head')
@include('layouts.backend.partials.headersection',['title'=>'Dashboard'])
@endsection

@section('content')
<div class="row">
    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
            <div class="card-icon bg-primary">
               <i class="fas fa-tasks"></i>
            </div>
            <div class="card-wrap">
                <div class="card-header">
                    <h4>{{ __('Total Tasks') }}</h4>
                </div>
                <div class="card-body" id="total_task">
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
               <i class="fas fa-tasks"></i>
            </div>
            <div class="card-wrap">
                <div class="card-header">
                    <h4>{{ __('Pending Tasks') }}</h4>
                </div>
                <div class="card-body" id="pending_task">
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
                <i class="fas fa-tasks"></i>
            </div>
            <div class="card-wrap">
                <div class="card-header">
                    <h4>{{ __('Completed Tasks') }}</h4>
                </div>
                <div class="card-body" id="completed_task">
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
                <div class="card-body" id="total_time">
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
                <i class="fas fa-dollar-sign"></i>
            </div>
            <div class="card-wrap">
                <div class="card-header">
                    <h4>{{ __('Total Earn') }}</h4>
                </div>
                <div class="card-body" id="total_earning">
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
                <i class="fas fa-project-diagram"></i>
            </div>
            <div class="card-wrap">
                <div class="card-header">
                    <h4>{{ __('Running Projects') }}</h4>
                </div>
                <div class="card-body" id="running_project">
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
               <i class="fas fa-project-diagram"></i>
            </div>
            <div class="card-wrap">
                <div class="card-header">
                    <h4>{{ __('Completed Projects') }}</h4>
                </div>
                <div class="card-body" id="completed_project">
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
               <i class="far fa-hdd"></i>
            </div>
            <div class="card-wrap">
                <div class="card-header">
                    <h4>{{ __('Storage Used') }}</h4>
                </div>
                <div class="card-body">
                   @php
                   $percentage = 0;
                   $limit=getplandata('storage_size');
                   $used_storage=folderSize('uploads/'.Auth::id());
                   @endphp
                   @if($used_storage > 0 && $limit > 0)
                   @php
                   $percentage = round((($used_storage / $limit) * 100), 2)
                   @endphp
                   {{ $percentage }}
                   @endif
                    <small>{{ $used_storage }}/{{ $limit }}MB</small>
                    <div class="progress custom-progress" data-height="6" data-toggle="tooltip" title="" data-original-title="{{ $percentage }}%">
                    <div class="progress-bar {{ $percentage >= 90 ? 'bg-danger' : 'bg-success' }} " data-width="{{ $percentage }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h4>{{ __('Last 7 days workload') }}</h4>
                            </div>
                            <div class="card-body">
                                <canvas id="myChart" height="100"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="plan_name"></h4>
                                    <span class="plan_expire"></span>
                                    <img src="{{ asset('frontend/assets/img/loader.gif') }}" height="40" class="expire_loader">
                            </div>
                            <div class="card-header">
                                <h4 class="card-header-title">{{ __('Storage Used') }}</h4>
                                <span class="storage"></span>
                            </div>
                            <div class="card-header">
                                <h4 class="card-header-title">{{ __('User Limit') }}</h4>
                                <span class="user_limit"></span>
                            </div>
                            <div class="card-header">
                                <h4 class="card-header-title">{{ __('Project Limit') }}</h4>
                                <span class="project_limit"></span>
                            </div>
                            <div class="card-header">
                                <h4 class="card-header-title">{{ __('Group Limit') }}</h4>
                                <span class="group_limit"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h4>{{ __('Running Projects') }}</h4>
                            </div>
                            <div class="col-md-6">
                                <form method="POST" action="" class="float-right">
                                    @csrf
                                    <div class="input-group col-12">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <i class="fas fa-calendar"></i>
                                                    </div>
                                                </div>
                                                <input type="text" name="daterange" id="daterange" class="form-control"
                                                value="" />
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-lg-12">
                                @if (Session::has('message'))
                                <p class="alert alert-danger">
                                    {{ Session::get('message') }}
                                </p>
                                @endif
                            </div>
                            <div class="col-lg-12">
                                <div class="table-responsive" id="project-list">
                                    <table class="table table-striped" id="table-2">
                                        <thead>
                                            <tr>
                                                <th>{{ __('Project Name') }}</th>
                                                <th>{{ __('Description') }}</th>
                                                <th>{{ __('Starting date') }}</th>
                                                <th>{{ __('Ending date') }}</th>
                                                <th>{{ __('Created At') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody id="projects">
                                            <tr>
                                                <td colspan="7">
                                                    <div class="loader text-center">
                                                        <img src="{{ asset('frontend/assets/img/loader.gif') }}" alt="">
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <div id="pagination-container"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        
  
    </div>
</div>
</div>
<input type="hidden" id="user_details" value="{{ route('user.details') }}">
<input type="hidden" id="recent_stats" value="{{ route('user.recent.stats') }}">
@endsection

@push('js')
<script src="{{ asset('backend/admin/assets/js/daterangepicker.js') }}"></script>
<script src="{{ asset('backend/admin/assets/js/chart.js') }}"></script>
<script src="{{ asset('backend/admin/assets/js/userdashboard.js?v=1.0.0') }}"></script>
@endpush
