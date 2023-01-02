@extends('layouts.backend.app')

@section('title','Reports')

@push('css')
    <link rel="stylesheet" href="{{ asset('backend/admin/assets/css/daterangepicker.css') }}">
@endpush

@section('head')
    @include('layouts.backend.partials.headersection',['title'=>'Reports'])
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12 text-right mb-4">
                            <div class="row">
                                <div class="col-sm-12 col-md-4">
                                    <div class="d-flex">
                                        <a href="{{ route('user.report.index','q=all') }}" class="mr-2 btn btn-outline-primary {{ $q == 'all' ? 'active' : '' }}">
                                         All {{ "(".$count['all'].")" }}
                                        </a>
                                        <a href="{{ route('user.report.index','q=2') }}" class="mr-2 btn btn-outline-warning {{ $q == '2' ? 'active' : '' }}">
                                            {{ __('Pending') }}{{ "(".$count['pending'].")" }}
                                        </a>
                                        <a href="{{ route('user.report.index','q=1') }}" class="mr-2 btn btn-outline-success {{ $q == '1' ? 'active' : '' }}">
                                            {{ __('Completed') }}{{ "(".$count['completed'].")" }}
                                        </a>  
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-4">
                                    <form action="{{ route('user.report.search') }}" method="get">
                                      <div class="form-group">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <i class="fas fa-calendar"></i>
                                                    </div>
                                                </div>
                                                <input type="hidden" name="type" value="date">
                                                <input type="text" name="q" id="daterange" class="form-control"
                                                    value=""/>
                                                <div class="input-group-append">
                                                    <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-sm-12 col-md-4">
                                    <div class="form">
                                      <form action="{{ route('user.report.search') }}" method="get">
                                        <div class="input-group mb-2">
                                            <input type="text" name="q" id="src" class="form-control" placeholder="Search..."
                                                required="" name="src" autocomplete="off" value="">
                                            <select class="form-control selectric" name="type" id="type">
                                                <option value="project">{{ __('Search By Project') }}</option>
                                            </select>
                                            <div class="input-group-append">
                                                <button class="btn btn-primary" type="submit"><i
                                                        class="fas fa-search"></i></button>
                                            </div>
                                        </div>
                                      </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th scope="col">{{ __('Sl.') }}</th>
                                            <th scope="col">{{ __('Name') }}</th>
                                            <th scope="col">{{ __('Progress') }}</th>
                                            <th scope="col">{{ __('Description') }}</th>
                                            <th scope="col">{{ __('Details') }}</th>
                                            <th scope="col">{{ __('Status') }}</th>
                                            <th scope="col">{{ __('Members') }}</th>
                                            <th scope="col">{{ __('Action') }}</th>
                                        </tr>
                                    </thead>
                                    @foreach ($projects ?? [] as $key => $project)
                                        <tbody>
                                            <tr>
                                                <th scope="row">{{ $key + 1 }}</th>
                                                <td>{{ $project->name }}</td>
                                                <td class="align-middle">
                                                    @php $percentage = 0; @endphp
                                                    @if ($project->column_count > 0)
                                                        @php $percentage = round((($project->completed_column_count / $project->column_count) * 100), 2) @endphp
                                                    @endif
                                                    <div class="progress custom-progress" data-height="6"
                                                        data-toggle="tooltip" title=""
                                                        data-original-title="{{ $percentage }}%">
                                                        <div class="progress-bar bg-success"
                                                            id="progress_bar_{{ $project->id }}"
                                                            data-width="{{ $percentage }}"></div>
                                                    </div>
                                                </td>
                                                <td>{!! Str::limit($project->description, 10, ' ...') !!}</td>
                                                <td>
                                                    @php $total = 0 @endphp
                                                    @foreach ($project->column as $column)
                                                        @php $total += $column->pending_task_count @endphp
                                                    @endforeach
                                                    <div>
                                                        <i class="fas fa-check-circle"></i> {{ $total }} {{ __('Tasks Left') }}
                                                    </div>
                                                    <div>
                                                        <i class="fas fa-calendar-alt"></i>
                                                        {{ Carbon\Carbon::now() <= $project->ending_date ? Carbon\Carbon::parse($project->ending_date)->diffForHumans() : 'Expired' }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        @php
                                                            $statusColor = [
                                                                0 => 'badge-danger',
                                                                1 => 'badge-success',
                                                                2 => 'badge-warning',
                                                            ][$project->status];
                                                            
                                                            $status = [
                                                                0 => 'Inactive',
                                                                1 => 'Finished',
                                                                2 => 'Pending',
                                                            ][$project->status];
                                                        @endphp
                                                        <div class="badge {{ $statusColor }}">{{ $status }}</div>
                                                    </div>
                                                </td>
                                                <td>
                                                    @foreach ($project->projectuser as $member)
                                                        <img data-toggle="tooltip" data-original-title="{{ $member->user->name }}"
                                                            alt="image"
                                                            src="{{ $member->user->avatar != null ? asset($member->user->avatar) : 'https://ui-avatars.com/api/?background=random&name=' . $member->user->name }}"
                                                            class="rounded-circle" width="35" data-toggle="tooltip" title=""
                                                            data-original-title="">

                                                    @endforeach
                                                </td>
                                                <td>
                                                    <a class="btn btn-primary" href="{{ route('user.report.show', $project->id) }}">{{ __('Analysis') }}</a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    @endforeach
                                </table>
                            {{ $projects->links('vendor.pagination.bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
    <script src="{{ asset('backend/admin/assets/js/daterangepicker.js') }}"></script>
    <script src="{{ asset('backend/admin/assets/js/report.js') }}"></script>
@endpush
