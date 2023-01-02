@extends('layouts.backend.app')
@section('title','Edit User Plan')
@section('head')
@include('layouts.backend.partials.headersection',['title'=>'Edit User Plan'])
@endsection
@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
     <div class="card-header">     
      <div class="col-12">
       <h4>{{ __('Edit Plan') }}</h4>
       @if(Session::has('message'))
       <p class="alert alert-danger">
        {{ Session::get('message') }}
      </p>
      @endif
    </div>
  </div>   
  <div class="card-body">
   <form method="POST" action="{{ route('admin.user.editplan.update', $info->id) }}" class="basicform">
    @csrf
      
   <div class="form-group row mb-4">
      <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Plan Name') }}</label>
    <div class="col-sm-12 col-md-7">
        <input type="text" required="" class="form-control" value="{{ $info->name }}" name="name">
    </div>
    </div>

    <div class="form-group row mb-4">
      	<label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Storage Limit') }} (MB)</label>
      	<div class="col-sm-12 col-md-7">
      		<input type="number" step="any" required="" class="form-control" value="{{ $info->storage_size }}" name="storage_size">
      	</div>
     </div>

     <div class="form-group row mb-4">
     	<label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Project Limit') }}</label>
     	<div class="col-sm-12 col-md-7">
     		<input type="number"  required="" class="form-control" value="{{ $info->project_limit }}" name="project_limit">
     	</div>
     </div>

    <div class="form-group row mb-4">
     	<label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('User Limit') }}</label>
     	<div class="col-sm-12 col-md-7">
     		<input type="number"  required="" class="form-control" value="{{ $info->user_limit }}" name="user_limit">
     	</div>
     </div>

    <div class="form-group row mb-4">
     	<label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Group Limit') }}</label>
     	<div class="col-sm-12 col-md-7">
     		<input type="number"  required="" class="form-control" value="{{ $info->group_limit }}" name="group_limit">
     	</div>
     </div>

     <div class="form-group row mb-4">
     	<label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('User Tracking') }}</label>
     	<div class="col-sm-12 col-md-7">
     		<select class="form-control" name="gps">
     			<option value="1" @if($info->gps == 1) selected=""  @endif>{{ __('Enable') }}</option>
     			<option value="0" @if($info->gps == 0) selected=""  @endif>{{ __('Disabled') }}</option>
     		</select>
     	</div>
     </div>

    <div class="form-group row mb-4">
     	<label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Mail Activity') }}</label>
     	<div class="col-sm-12 col-md-7">
     		<select class="form-control" name="mail_activity">
     			<option value="1" @if($info->mail_activity == 1) selected=""  @endif>{{ __('Enable') }}</option>
     			<option value="0" @if($info->mail_activity == 0) selected=""  @endif>{{ __('Disabled') }}</option>
     		</select>
     	</div>
     </div>

    <div class="form-group row mb-4">
     	<label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Multi Adminable Project') }}</label>
     	<div class="col-sm-12 col-md-7">
     		<select class="form-control" name="adminable_project">
     			<option value="1" @if($info->adminable_project == 1) selected=""  @endif>{{ __('Enable') }}</option>
     			<option value="0" @if($info->adminable_project == 0) selected=""  @endif>{{ __('Disabled') }}</option>
     		</select>
     	</div>
     </div>

    <div class="form-group row mb-4">
     	<label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Screenshot Capture') }}</label>
     	<div class="col-sm-12 col-md-7">
     		<select class="form-control" name="screenshot">
     			<option value="1" @if($info->screenshot == 1) selected=""  @endif>{{ __('Enable') }}</option>
     			<option value="0" @if($info->screenshot == 0) selected=""  @endif>{{ __('Disabled') }}</option>
     		</select>
     	</div>
     </div>

    
    
      <div class="form-group row mb-4">
        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
        <div class="col-sm-12 col-md-7">
          <button type="submit" class="btn btn-primary basicbtn">{{ __('Update plan') }}</button>
        </div>
      </div>
    
    </form>
  </div> 
</div>

@endsection

