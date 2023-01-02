@extends('layouts.backend.app')

@section('title','Edit Project')

@push('css')
    <link rel="stylesheet" href="{{ asset('backend/admin/assets/css/daterangepicker.css') }}">
@endpush

@section('head')
@include('layouts.backend.partials.headersection',['title'=>'Edit Project'])
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
        <div class="card-body">
            <h2 class="text-right"></h2>
            <div class="row">
                <div class="col-lg-12">
                    <form method="post" class="basicform_with_reload" action="{{ route('user.project.update', $project->id) }}" >
                        @csrf
                        @method("PUT")
                        <input type="hidden" value="{{  $project->project_type }}" id="selected_project_type"> 
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
                                    <input type="text" class="form-control" required name="project_name" value="{{ $project->name }}" placeholder="{{ __('Enter Project Name') }}">
                                </div>
                                <div class="form-group">
                                    <label for="name">{{ __('Project Description') }}</label>
                                    <input type="text" class="form-control" required name="project_description" value="{{ $project->description }}" placeholder="{{ __('Project Description') }}">
                                </div>
                                <div class="form-group">
                                    <label for="name">{{ __('Timeline') }}</label>
                                    <input type="text" class="form-control daterange-cus" required name="project_time" data-start="{{ $project->starting_date }}" data-end="{{ $project->ending_date }}">
                                </div>
                            
                                <div class="section-title"><b>{{ __('Project Type') }}</b></div>
                                <div class="form-group">
                                    <div class="custom-control custom-radio">
                                    <input type="radio" {{ $project->project_type == 0 ? 'checked' : '' }} id="me" value="0" name="project_type" class="custom-control-input project_type" >
                                    <label class="custom-control-label" for="me"><b>{{ __('Only me') }}</b></label>
                                    </div>
                                    <p class="ml-4">{{ __('Only you can see this project') }}</p>
                                </div>
                                <div class="form-group">
                                    <div class="custom-control custom-radio">
                                    <input type="radio" id="team"  {{ $project->project_type == 2 ? 'checked' : '' }} value="2" name="project_type" class="custom-control-input project_type">
                                    <label class="custom-control-label" for="team"><b>{{ __('Team Members') }}</b></label>
                                    </div>
                                    <p class="ml-4">{{ __('Only assigned team members can see this project') }}</p>
                                </div>
                                <div class="form-group">
                                    <div class="custom-control custom-radio">
                                    <input type="radio" id="group"  {{ $project->project_type == 3 ? 'checked' : '' }} value="3" name="project_type" class="custom-control-input project_type">
                                    <label class="custom-control-label" for="group"><b>{{ __('Group') }}</b></label>
                                    </div> 
                                    <p class="ml-4">{{ __('Only assigned group members can see this project') }}</p>
                                </div>
                                <div class="section-title"><b>{{ __('Users Track') }}</b></div>
                                <div class="form-group">
                                    <label>
                                    <input type="checkbox" name="screenshot" {{ ($percentage > 95 || $plan->screenshot == 0 ) ? 'disabled' : '' }}  {{ $project->screenshot == 1 && $percentage < 95 ? 'checked' : '' }} name="custom-switch-checkbox" class="custom-switch-input">
                                    <span class="custom-switch-indicator"></span>
                                    <span class="custom-switch-description mt-2">{{ __('Capture Screenshot') }}</span>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label>
                                    <input type="checkbox" name="gps" {{ $plan->gps == 0 ? 'disabled' : '' }} {{ $project->gps == 1 ? 'checked' : '' }} name="custom-switch-checkbox" class="custom-switch-input">
                                    <span class="custom-switch-indicator"></span>
                                    <span class="custom-switch-description mt-2">{{ __('User Location (GPS)') }}</span>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label>
                                    <input type="checkbox" name="mail_activity" {{ $project->mail_activity == 1 ? 'checked' : '' }} name="custom-switch-checkbox" class="custom-switch-input">
                                    <span class="custom-switch-indicator"></span>
                                    <span class="custom-switch-description mt-2">{{ __('Mail Activity') }}</span>
                                    </label>
                                </div>
                                </div>
                                <div class="tab-pane fade" id="profile3" role="tabpanel" aria-labelledby="profile-tab3">
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Filter" id="filter">
                                </div> 
                                <div class="user_info">
                                @foreach ($users as $key => $user)
                                @php $checked = '' @endphp
                                @foreach ($project->projectuser as $project_user)
                                    @if ($project_user->user_id == $user->user->id)
                                        @php $checked = 'checked' @endphp
                                    @endif
                                @endforeach
                                <div class="form-group" data-name="{{ $user->user->name }}">
                                    <div class="custom-control custom-checkbox">
                                    <input type="checkbox" id="a{{ $key }}" {{ $checked }} value="{{ $user->user->id }}" name="user[]" class="custom-control-input">
                                    <label class="custom-control-label" for="a{{ $key }}"><figure class="avatar mr-2 avatar-sm">
                                        <img src="{{ asset($user->user->avatar != null ? $user->user->avatar : 'uploads/avatar.png') }}" alt="...">
                                    </figure><b>{{ $user->user->name }}</b></label>
                                    </div>
                                </div>
                                @endforeach
                                </div>
                                </div>
                                <div class="tab-pane fade" id="contact3" role="tabpanel" aria-labelledby="contact-tab3">
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Filter">
                                </div> 
                                @foreach ($groups as $key => $group)
                                @php $checked = "" @endphp
                                @foreach ($project->projectgroup as $projectgroup)
                                @if ($projectgroup->group_id == $group->id)
                                    @php $checked = "checked" @endphp
                                @endif
                                @endforeach
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                    <input type="checkbox" {{ $checked }} id="g{{ $key }}" value="{{ $group->id }}" name="group[]" class="custom-control-input">
                                    <label class="custom-control-label" for="g{{ $key }}"><b>{{ $group->name }}</b></label>
                                    </div>
                                </div>
                                @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <button type="submit" class="btn btn-primary basicbtn">{{ __('Update') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!--modal start here.....................-->
@endsection

@push('js')
<script src="{{ asset('backend/admin/assets/js/daterangepicker.js') }}"></script>
<script src="{{ asset('backend/admin/assets/js/project-edit.js') }}"></script>
@endpush
