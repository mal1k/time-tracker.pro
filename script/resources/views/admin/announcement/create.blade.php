@extends('layouts.backend.app')

@section('title','Add New Announcement')

@section('head')
@include('layouts.backend.partials.headersection',['title'=>'Create Announcement','prev'=>route('admin.announcement.index')])
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="row">
                <div class="col-12">
                @if(Session::has('message'))
                    <p class="alert alert-danger">
                        {{ Session::get('message') }}
                    </p>
                @endif
                </div>
            </div>
            <form method="POST" action="{{ route('admin.announcement.store') }}" class="basicform_with_reset">
              @csrf
              <div class="card-body">
                <div class="form-group">
                    <label>{{ __('Title') }}</label>
                    <input type="text" class="form-control" placeholder="Title" required name="title">
                </div>
                <div class="form-group">
                    <label>{{ __('Description') }}</label>
                    <textarea name="description" id="" cols="30" rows="2" class="form-control"></textarea>
                </div>
                <div class="form-group">
                    <div class="custom-file mb-3">
                      <label>{{ __('Status') }}</label>
                      <select name="status" class="form-control">
                        <option value="1">{{ __('Active') }}</option>
                        <option value="0">{{ __('Inactive') }}</option>
                      </select>
                    </div>
                </div>
                <div class="row">
                  <div class="col-lg-12">
                    <button type="submit" class="btn btn-primary btn-lg float-right w-100 basicbtn">{{ __('Submit') }}</button>
                  </div>
                </div>
              </div>
            </form>
        </div>
    </div>
</div>
@endsection

