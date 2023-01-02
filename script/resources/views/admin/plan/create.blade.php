@extends('layouts.backend.app')

@section('title','Create Plan')

@section('head')
    @include('layouts.backend.partials.headersection',['title'=>'Create Plan','prev'=>route('admin.plan.index')])
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="col-12">
                    <h4>{{ __('Create New Plan') }}</h4>
                    @if (Session::has('message'))
                    <p class="alert alert-danger">
                        {{ Session::get('message') }}
                    </p>
                    @endif
                </div>
            </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.plan.store') }}" class="basicform_with_reset">
                @csrf
                <div class="form-group row mb-4">
                    <label
                    class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Plan Name') }}</label>
                    <div class="col-sm-12 col-md-7">
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                        placeholder="Name" required name="name">
                        @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="form-group row mb-4">
                    <label
                    class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Duration') }}</label>
                    <div class="col-sm-12 col-md-7">
                        <input type="number" class="form-control @error('duration') is-invalid @enderror"
                        placeholder="Duration" required name="duration">
                        @error('duration')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="form-group row mb-4">
                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">
                    {{ __('Price') }}</label>
                    <div class="col-sm-12 col-md-7">
                        <input type="text" step="any" class="form-control @error('price') is-invalid @enderror"
                        placeholder="Price" required name="price">
                        @error('price')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="form-group row mb-4">
                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">
                    {{ __('Storage Limit') }}</label>
                    <div class="col-sm-12 col-md-7">
                        <input type="number" class="form-control @error('storage_size') is-invalid @enderror"
                        placeholder="Storage Size" required name="storage_size">
                        @error('storage_size')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="form-group row mb-4">
                    <label
                    class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Users Limit') }}</label>
                    <div class="col-sm-12 col-md-7">
                        <input type="number" class="form-control @error('user_limit') is-invalid @enderror"
                        placeholder="Users Limit" required name="user_limit">
                        @error('user_limit')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="form-group row mb-4">
                    <label
                    class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Project Limit') }}</label>
                    <div class="col-sm-12 col-md-7">
                        <input type="number" class="form-control @error('project_limit') is-invalid @enderror"
                        placeholder="Project Limit" required name="project_limit">
                        @error('project_limit')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="form-group row mb-4">
                    <label
                    class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Group Limit') }}</label>
                    <div class="col-sm-12 col-md-7">
                        <input name="group_limit" type="number" placeholder="Enter Groups Limit"
                        class="form-control @error('group_limit') is-invalid @enderror">
                        @error('group_limit')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="form-group row mb-4">
                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">
                    {{ __('Screen Shot') }}</label>
                    <div class="col-sm-12 col-md-7">
                        <select name="screenshot" class="form-control @error('screenshot') is-invalid @enderror">
                            <option value="1">{{ __('Active') }}</option>
                            <option value="0">{{ __('Inactive') }}</option>
                        </select>
                        @error('screenshot')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="form-group row mb-4">
                    <label
                    class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Gps tracking') }}</label>
                    <div class="col-sm-12 col-md-7">
                        <select class="form-control @error('gps') is-invalid @enderror" name="gps">

                            <option value="1">{{ __('On') }}</option>
                            <option value="0">{{ __('off') }}</option>
                        </select>
                        @error('gps')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="form-group row mb-4">
                    <label
                    class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Is featured') }}</label>
                    <div class="col-sm-12 col-md-7">
                        <select name="is_featured" class="form-control">
                            <option value="1">{{ __('Active') }}</option>
                            <option value="0">{{ __('Inactive') }}</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row mb-4">
                    <label
                    class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Is Trial') }}</label>
                    <div class="col-sm-12 col-md-7">
                        <select name="is_trial" class="form-control">
                            <option value="1">{{ __('Yes') }}</option>
                            <option value="0">{{ __('No') }}</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row mb-4">
                    <label
                    class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Adminable Project') }}</label>
                    <div class="col-sm-12 col-md-7">
                        <select name="adminable_project" class="form-control">
                            <option value="1">{{ __('Yes') }}</option>
                            <option value="0">{{ __('No') }}</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row mb-4">
                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Mail Activity') }}</label>
                    <div class="col-sm-12 col-md-7">
                        <select name="mail_activity" class="form-control">
                            <option value="1">{{ __('Yes') }}</option>
                            <option value="0">{{ __('No') }}</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row mb-4">
                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Status') }}</label>
                    <div class="col-sm-12 col-md-7">
                        <select name="status" class="form-control">
                            <option value="1">{{ __('Active') }}</option>
                            <option value="0">{{ __('Inactive') }}</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row mb-4">
                    <label
                    class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Is Default ?') }}</label>
                    <div class="col-sm-12 col-md-7">
                        <select name="is_default" class="form-control">
                            <option value="1">{{ __('Yes') }}</option>
                            <option value="0">{{ __('No') }}</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row mb-4">
                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
                    <div class="col-sm-12 col-md-7">
                        <button type="submit" class="btn btn-primary basicbtn">{{ __('Create Post') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
