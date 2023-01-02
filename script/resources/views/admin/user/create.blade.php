@extends('layouts.backend.app')

@section('title','Create User')

@section('head')
  @include('layouts.backend.partials.headersection',['title'=>'Create User','prev'=> route('admin.user.index')])
@endsection

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
     <div class="card-header">     
      <div class="col-12">
       <h4>{{ __('Create New User') }}</h4>
       @if(Session::has('message'))
       <p class="alert alert-danger">
        {{ Session::get('message') }}
      </p>
      @endif
    </div>
  </div>   
  <div class="card-body">
    <form method="POST" action="{{ route('admin.user.store') }}" class="basicform_with_reset">
        @csrf
      <div class="form-group row mb-4">
       <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Name') }}</label>
       <div class="col-sm-12 col-md-7">
         <input type="text" class="form-control" placeholder="Name" required name="name">        
      </div>
    </div> 
    <div class="form-group row mb-4">
       <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Email') }}</label>
       <div class="col-sm-12 col-md-7">
          <input type="email" class="form-control" placeholder="Email Address" required name="email">
      </div>
    </div> 
    <div class="form-group row mb-4">
       <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Password') }}</label>
       <div class="col-sm-12 col-md-7">
         <input type="password" class="form-control" placeholder="Password" required name="password">
      </div>
    </div> 
     <div class="form-group row mb-4">
       <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Status') }}</label>
       <div class="col-sm-12 col-md-7">
         <select required name="status" class="form-control">
            <option value="1">{{ __('Active') }}</option>
            <option value="0">{{ __('Inactive') }}</option>
          </select>
        </div>
      </div>  
      <div class="form-group row mb-4">
        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
        <div class="col-sm-12 col-md-7">
          <button type="submit" class="btn btn-primary btn-lg float-right w-100 basicbtn">{{ __('Submit') }}</button>
        </div>
      </div>
    </form>
  </div> 
</div>
@endsection

