@extends('layouts.backend.app')

@section('title','Edit User')

@section('head')
  @include('layouts.backend.partials.headersection',['title'=>'Edit','prev'=> route('admin.user.index')])
@endsection

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
     <div class="card-header">     
      <div class="col-12">
       <h4>{{ __('Edit User') }}</h4>
       @if(Session::has('message'))
       <p class="alert alert-danger">
        {{ Session::get('message') }}
      </p>
      @endif
    </div>
  </div>   
  <div class="card-body">
   <form method="POST" action="{{ route('admin.user.update', $user->id) }}" class="basicform">
    @csrf
    @method('PUT')
    <div class="form-group row mb-4">
       <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Name') }}</label>
       <div class="col-sm-12 col-md-7">
        <input type="text" class="form-control" placeholder="Name" required name="name" value="{{ $user->name }}">
      </div>
    </div>
    <div class="form-group row mb-4">
       <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Email') }}</label>
       <div class="col-sm-12 col-md-7">
        <input type="email" class="form-control" placeholder="email" required name="email" value="{{ $user->email }}">
      </div>
    </div>
   <div class="form-group row mb-4">
      <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Password') }}</label>
      <div class="col-sm-12 col-md-7">
        <input type="password" class="form-control" placeholder="Password" name="password">
      </div>
    </div>
    <div class="form-group row mb-4">
      <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Status') }}</label>
        <div class="col-sm-12 col-md-7">
          <select required name="status" class="form-control">          
            <option value="1" {{ $user->status == 1 ? 'selected' : '' }}>
            {{ __('Active') }}</option>
            <option value="0" {{ $user->status == 0 ? 'selected' : '' }}>
            {{ __('Disabled') }}</option>
          </select>
        </div>
      </div>
      <div class="form-group row mb-4">
        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
        <div class="col-sm-12 col-md-7">
          <button type="submit" class="btn btn-primary basicbtn">{{ __('Update User') }}</button>
        </div>
      </div>
    </form>
  </div> 
</div>
@endsection
