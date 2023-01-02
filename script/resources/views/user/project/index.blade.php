@extends('layouts.backend.app')

@section('title','Projects')

@push('css')
    <link rel="stylesheet" href="{{ asset('backend/admin/assets/css/daterangepicker.css') }}">
@endpush

@section('head')
@include('layouts.backend.partials.headersection',['title'=>'Project'])
@endsection

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <div class="row">
        <div class="col-lg-12 mb-4">
          @if (Session::has('success'))
              <div class="alert alert-success">{{ Session::get('success') }}</div>
          @endif
          <div class="row">
            <div class="col-md-4">
              <div class="form">
                <form action="{{ route('user.project.search') }}" method="get">
                  <div class="input-group mb-2">
                    <input type="text" name="q" id="src" class="form-control" placeholder="Search..." required="" autocomplete="off" value="">
                    <select class="form-control" name="type" id="type">
                        <option value="name">{{ __('Search By Name') }}</option>
                    </select>
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
            <div class="col-md-8 text-right">
              <a class="btn btn-primary pull-right" href="#" data-toggle="modal" data-target="#create_project">{{ __('Create New Project') }}</a>
            </div>
          </div> 
        </div>
        <div class="col-lg-12">
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
              <tbody>
              @foreach ($projects ?? [] as $key => $project)
              <tr>
                <th scope="row">{{$key+1}}</th>
                <td><a href="{{ route('user.project.show', $project->id) }}">{{ $project->name }}</a></td>
                <td class="align-middle">
                  @php $percentage = 0; @endphp
                  @if ($project->column_count > 0)
                    @php $percentage = round((($project->completed_column_count / $project->column_count) * 100), 2) @endphp 
                  @endif
                  <div class="progress custom-progress" data-height="6" data-toggle="tooltip" title="" data-original-title="{{ $percentage }}%">
                    <div class="progress-bar bg-success" id="progress_bar_{{ $project->id }}" data-width="{{ $percentage }}"></div>
                  </div>
                </td>
                <td>{{ Str::limit($project->description, 10, ' ...') }}</td>
                <td>
                  @php $total = 0 @endphp
                  @foreach ($project->column as $column)
                  @php $total += $column->pending_task_count @endphp
                  @endforeach 
                  <i class="fas fa-check-circle"></i> {{  $total  }} {{ __('Tasks Left') }}
                  </div>
                    <div>
                      <i class="fas fa-calendar-alt"></i> {{ Carbon\Carbon::now() <= $project->ending_date ? Carbon\Carbon::parse($project->ending_date)->diffForHumans() :  'Expired'  }}
                    </div>
                  </td>
                <td>
                  <div>
                      @php
                      $status = [
                        0 => [ 'color' => 'badge-danger', 'text' =>  'Inactive' ] ,
                        1 => [ 'color' => 'badge-success', 'text' =>  'Finished' ] ,
                        2 => [ 'color' => 'badge-primary', 'text' =>  'Pending' ] 
                      ][$project->status];
                      @endphp
                      <div class="badge {{ $status['color'] }}">{{ $status['text'] }}</div>
                  </div>
                </td>
                <td>
                  @foreach ($project->projectuser as $member)
                  <img data-toggle="tooltip" data-original-title="{{ $member->user->name }}" alt="image" src="{{ $member->user->avatar != null ? asset($member->user->avatar) : 'https://ui-avatars.com/api/?background=random&name='.$member->user->name }}" class="rounded-circle" width="35" data-toggle="tooltip" title="" data-original-title="">
                  @endforeach
                </td>
                <td>
                  <div class="dropdown d-inline">
                    <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      {{ __('Action') }}
                    </button>
                    <div class="dropdown-menu">
                      <a class="dropdown-item has-icon" href="{{ route('user.project.show', $project->id) }}"><i class="fa fa-eye"></i>{{ __('View') }}</a>
                      @if ($project->user_id == Auth::id())
                      <a class="dropdown-item has-icon" href="{{ route('user.project.edit', $project->id) }}"><i class="fa fa-edit"></i>{{ __('edit') }}</a>
                     
                      @endif
                    </div>
                  </div>
                </td>
              </tr>
              @endforeach
             </tbody>
            </table>
            {{ $projects->links('vendor.pagination.bootstrap-4') }}
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!--modal start here.....................-->
<!-- Modal -->
<div class="modal fade" id="create_project" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">  
    @if($total_project <= $project_limit)
    <form method="post"  action="{{ route('user.project.store') }}" class="basicform_with_reload">
      @csrf
      @endif  
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">{{ __('Create New Project') }}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="card-body">
              <ul class="nav nav-pills" id="myTab3" role="tablist">
                <li class="nav-item">
                  <a class="nav-link active" id="home-tab3" data-toggle="tab" href="#home3" role="tab" aria-controls="home" aria-selected="true">{{ __('Project Info') }}</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link team disable_cursor" id="profile-tab3" data-toggle="tab" href="#profile3" role="tab" aria-controls="profile" aria-selected="false">{{ __('Team Members') }}</a>
                </li>
                <li class="nav-item">
                  <a  class="nav-link group disable_cursor" id="contact-tab3" data-toggle="tab" href="#contact3" role="tab" aria-controls="contact" aria-selected="false">{{ __('Group') }}</a>
                </li>
              </ul>
              <div class="tab-content" id="myTabContent2">
                <div class="tab-pane fade show active" id="home3" role="tabpanel" aria-labelledby="home-tab3">
                  <div class="form-group">
                    <label for="name">{{ __('Enter Project Name') }}</label>
                    <input type="text" class="form-control" required name="project_name" placeholder="{{ __('Enter Project Name') }}">
                  </div>
                  <div class="form-group">
                    <label for="name">{{ __('Project Description') }}</label>
                    <input type="text" class="form-control" required name="project_description" placeholder="{{ __('Project Description') }}">
                  </div>
                  <div class="form-group">
                    <label for="name">{{ __('Timeline') }}</label>
                    <input type="text" class="form-control daterange-cus-left" required name="project_time">
                  </div>
                  <div class="section-title"><b>{{ __('Project Type') }}</b></div>
                  <div class="form-group">
                    <div class="custom-control custom-radio">
                      <input type="radio" checked id="me" value="0" name="project_type" class="custom-control-input project_type">
                      <label class="custom-control-label" for="me"><b>{{ __('Only me') }}</b></label>
                  </div>
                    <p class="ml-4">{{ __('Only you can see this project') }}</p>
                  </div>
                  <div class="form-group">
                    <div class="custom-control custom-radio">
                      <input type="radio" id="team" value="2" name="project_type" class="custom-control-input project_type">
                      <label class="custom-control-label" for="team"><b>{{ __('Team Members') }}</b></label>
                  </div>
                    <p class="ml-4">{{ __('Only assigned team members can see this project') }}</p>
                  </div>
                  <div class="form-group">
                    <div class="custom-control custom-radio">
                      <input type="radio" id="group" value="3" name="project_type" class="custom-control-input project_type">
                      <label class="custom-control-label" for="group"><b>{{ __('Group') }}</b></label>
                  </div>
                    <p class="ml-4">{{ __('Only assigned group members can see this project') }}</p>
                  </div>
                  <div class="section-title"><b>{{ __('Users Track') }}</b></div>
                  <div class="form-group">
                    <label>
                      <input type="checkbox" name="screenshot" name="custom-switch-checkbox" class="custom-switch-input" {{ ($percent > 95 || (!empty($plan->screenshot) && $plan->screenshot) == 0 ) ? 'disabled' : '' }}>
                      <span class="custom-switch-indicator"></span>
                      <span class="custom-switch-description mt-2">{{ __('Capture Screenshot') }}</span>
                    </label>
                  </div>
                  <div class="form-group">
                    <label>
                      <input type="checkbox" name="gps" name="custom-switch-checkbox" class="custom-switch-input" {{ (!empty($plan->gps) && $plan->gps) == 0 ? 'disabled' : '' }}>
                      <span class="custom-switch-indicator"></span>
                      <span class="custom-switch-description mt-2">{{ __('User Location (GPS)') }}</span>
                    </label>
                  </div>
                  <div class="form-group">
                    <label>
                      <input type="checkbox" name="mail_activity" name="custom-switch-checkbox" class="custom-switch-input">
                      <span class="custom-switch-indicator"></span>
                      <span class="custom-switch-description mt-2">{{ __('Mail activity') }}</span>
                    </label>
                  </div>
                </div>
                <div class="tab-pane fade" id="profile3" role="tabpanel" aria-labelledby="profile-tab3">
                  <div class="form-group">
                    <input type="text" class="form-control" placeholder="Filter" id="filter">
                  </div> 
                  <div class="user_info">
                  @foreach ($users as $key => $user)
                  <div class="form-group" data-name="{{ $user->user->name }}">
                    <div class="custom-control custom-checkbox">
                      <input type="checkbox" id="a{{ $key }}" value="{{ $user->user->id }}" name="user[]" class="custom-control-input">
                      <label class="custom-control-label" for="a{{ $key }}"><figure class="avatar mr-2 avatar-sm">
                        <img src="{{ asset($user->user->avatar ? null : 'uploads/avatar.png') }}" alt="...">
                      </figure><b>{{ $user->user->name }}</b></label>
                  </div>
                  </div>
                  @endforeach
                  </div>
                </div>
                <div class="tab-pane fade" id="contact3" role="tabpanel" aria-labelledby="contact-tab3">
                  @foreach ($groups as $key => $group)
                  <div class="form-group">
                    <div class="custom-control custom-checkbox">
                      <input type="checkbox" id="g{{ $key }}" value="{{ $group->id }}" name="group[]" class="custom-control-input">
                      <label class="custom-control-label" for="g{{ $key }}"><b>{{ $group->name }}</b></label>
                  </div>
                  </div>
                  @endforeach
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            @if($total_project >= $project_limit) 
            <small class="text-danger text-left float-left">{{ __('Maximum Project Limit Exceeded') }}</small>
            @endif
            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
            <button type="submit" class="btn btn-primary basicbtn"  @if($total_project >= $project_limit) disabled  @endif>{{ __('Create Now') }}</button>
          </div>
           @if($total_project <= $project_limit)
        </form>
      @endif
    </div>
  </div>
</div>
@endsection

@push('js')
<script src="{{ asset('backend/admin/assets/js/daterangepicker.js') }}"></script>
<script src="{{ asset('backend/admin/assets/js/project-edit.js') }}"></script>
@endpush
