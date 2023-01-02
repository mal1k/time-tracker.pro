@extends('layouts.backend.app')

@section('title','Reports')

@section('content')
<div class="row">
    <div class="col-12 col-sm-12 col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="text-center">{{ __('User Details of ') . $user . ' on ' . $project->name }}</h5>
            </div>
            <div class="card-body">
                <div class="row">
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
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-12 col-lg-12">
        <div class="card">
            <div class="card-body">
                <ul class="nav nav-pills" id="myTab3" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="home-tab2" data-toggle="tab" href="#chartstats" role="tab"
                            aria-controls="home" aria-selected="true">{{ __('Workload (Hours)') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="home-tab3" data-toggle="tab" href="#taskscr" role="tab"
                            aria-controls="home" aria-selected="true">{{ __('Task Screenshots') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="profile-tab3" data-toggle="tab" href="#projectscr" role="tab"
                            aria-controls="profile" aria-selected="false">{{ __('Project Screenshots') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="contact-tab3" data-toggle="tab" href="#gps" role="tab"
                            aria-controls="contact" aria-selected="false">{{ __('GPS Location') }}</a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent2">
                    <div class="tab-pane fade show active" id="chartstats" role="tabpanel" aria-labelledby="home-tab2">
                        <canvas id="myChart2" class="p-2"></canvas>
                    </div>
                    <div class="tab-pane fade" id="taskscr" role="tabpanel" aria-labelledby="home-tab3">
                        <div class="row">
                            @if ($project->user_id == Auth::id())
                            <div class="col-md-4 mb-4">
                                <select name="" class="selectric taskdelete">
                                    <option selected>{{ __('Action For Selected') }}</option>
                                    <option value="0">{{ __('Delete') }}</option>
                                </select>
                            </div>
                            @endif
                            <div class="col-md-12">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>
                                                <div class="custom-checkbox custom-control">
                                                    <input type="checkbox" 
                                                        class="custom-control-input checkAlltask" id="taskall">
                                                    <label for="taskall" class="custom-control-label">&nbsp;</label>
                                                </div>
                                            </th>
                                            <th>{{ __('SL.') }}</th>
                                            <th>{{ __('Attachment') }}</th>
                                            <th>{{ __('Created At') }}</th>
                                            <th>{{ __('Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody id="taskscrtable">
                                    </tbody>
                                </table>
                                <nav aria-label="Page navigation example"><ul class="pagination" id="task_paginate"></ul></nav>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="projectscr" role="tabpanel" aria-labelledby="profile-tab3">
                        <div class="row">
                            @if ($project->user_id == Auth::id())
                            <div class="col-md-4 mb-4">
                                <select class="selectric projectdelete">
                                    <option selected>{{ __('Action For Selected') }}</option>
                                    <option value="0">{{ __('Delete') }}</option>
                                </select>
                            </div>
                            @endif
                            <div class="col-md-12">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>
                                                <div class="custom-checkbox custom-control">
                                                    <input type="checkbox" 
                                                        class="custom-control-input checkAllproject" id="projectall">
                                                    <label for="projectall" class="custom-control-label">&nbsp;</label>
                                                </div>
                                            </th>
                                            <th>{{ __('SL.') }}</th>
                                            <th>{{ __('Attachment') }}</th>
                                            <th>{{ __('Created At') }}</th>
                                            <th>{{ __('Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody id="projectscrtable">
                                        
                                    </tbody>
                                </table>
                                <nav aria-label="Page navigation example"><ul class="pagination" id="project_paginate"></ul></nav>
                            </div>
                        </div>
                        {{-- @if ($project->user_id == Auth::id())
                        <button class="deleteScr btn btn-danger">{{ __('Delete') }}</button>
                        @endif --}}
                    </div>
                    <div class="tab-pane fade" id="gps" role="tabpanel" aria-labelledby="contact-tab3">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4>{{ __('Marker') }}</h4>
                                    </div>
                                    <div class="card-body">
                                        <div id="gpsmap"></div>
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

<div class="taskmodalAppend"></div>
<div class="projectmodalAppend"></div>

<input type="hidden" id="project_id" value="{{ $project_id }}">
<input type="hidden" id="user_id" value="{{ $id }}">
<input type="hidden" id="url" value="{{ url('/') }}">
@foreach ($gps as $key => $item)
<input type="hidden" class="gps" name="gps[{{ $key }}]" data-lat="{{ $item->latitude ?? 0 }}" data-lng="{{ $item->longitude ?? 0 }}">

@endforeach

<input type="hidden" id="statsroute" value="{{ route('user.report.user.stats') }}">
<input type="hidden" id="getStats" value="{{ route('user.report.fetch.stats') }}">
<input type="hidden" id="deletescreenshot" value="{{ route('user.report.delete.screenshot') }}">
@endsection

@push('js')
    <script src="{{ asset('backend/admin/assets/js/chart.js') }}"></script>
    <script src="{{ asset('backend/admin/assets/js/report.js') }}"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key={{ env('MAP_API_KEY') }}&callback=initMap&libraries=&v=weekly" async></script>
    <script src="{{ asset('backend/admin/assets/js/user_report.js') }}"></script>
@endpush
